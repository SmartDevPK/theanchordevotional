<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Database configuration
$config = [
    'host' => 'localhost',
    'port' => 3307,
    'username' => 'root',
    'password' => '',
    'database' => 'prayer_db'
];

// Only allow POST or admin approval
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_GET['admin_approve'])) {
    header("Location: index.php?error=invalid_access");
    exit;
}

try {
    // Connect to database
    $conn = new mysqli(
        $config['host'],
        $config['username'],
        $config['password'],
        $config['database'],
        $config['port']
    );

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    /** ============================
     *  ADMIN APPROVAL SECTION
     *  ============================ */
    if (isset($_GET['admin_approve'])) {
        $token = $_GET['token'] ?? '';

        // Fetch pending testimony by token
        $stmt = $conn->prepare("SELECT * FROM testimonies_pending WHERE approval_token = ? AND status = 'pending'");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $pending = $result->fetch_assoc();

            // Insert into approved testimonies table
            $stmt = $conn->prepare("INSERT INTO testimonies (name, initials, message) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $pending['name'], $pending['initials'], $pending['message']);

            if ($stmt->execute()) {
                // Mark as approved
                $stmt = $conn->prepare("UPDATE testimonies_pending SET status = 'approved' WHERE id = ?");
                $stmt->bind_param("i", $pending['id']);
                $stmt->execute();

                header("Location: index.php?success=approved");
                exit;
            }
        }

        header("Location: index.php?error=invalid_token");
        exit;
    }

    /** ============================
     *  USER SUBMISSION SECTION
     *  ============================ */

    // Collect and sanitize input
    $name = trim($_POST['name'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $approval_token = bin2hex(random_bytes(16));

    if (empty($name) || empty($message)) {
        header("Location: index.php?error=empty_fields");
        exit;
    }

    // Generate initials from name
    $initials = '';
    $parts = explode(' ', $name);
    foreach ($parts as $part) {
        if (!empty($part)) {
            $initials .= strtoupper($part[0]);
        }
    }

    // Email approval link
    $approval_link = "https://yoursite.com/approve_testimony.php?admin_approve=1&token=" . urlencode($approval_token);
    $to = "admin@yoursite.com";
    $subject = "New Testimony Approval Request";
    $email_body = "A new testimony requires approval:\n\n";
    $email_body .= "Name: " . htmlspecialchars($name) . "\n";
    $email_body .= "Initials: " . htmlspecialchars($initials) . "\n\n";
    $email_body .= "Click here to approve:\n" . $approval_link . "\n\n";
    $email_body .= "This link expires in 48 hours.";

    $headers = "From: no-reply@yoursite.com\r\n";

    if (!mail($to, $subject, $email_body, $headers)) {
        error_log("Failed to send approval email for testimony.");
    }

    // Insert into pending table
    $stmt = $conn->prepare("
        INSERT INTO testimonies_pending (name, initials, message, approval_token, status)
        VALUES (?, ?, ?, ?, 'pending')
    ");
    $stmt->bind_param("ssss", $name, $initials, $message, $approval_token);

    if ($stmt->execute()) {
        header("Location: index.php?success=submitted");
        exit;
    } else {
        header("Location: index.php?error=submission_failed");
        exit;
    }

} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    header("Location: index.php?error=server_error");
    exit;
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>
