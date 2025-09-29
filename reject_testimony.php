<?php
// Start session and enable error reporting
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$config = [
    'host' => 'localhost',
    'port' => 3307,
    'username' => 'root',
    'password' => '',
    'database' => 'prayer_db'
];

try {
    // Create database connection
    $conn = new mysqli(
        $config['host'],
        $config['username'],
        $config['password'],
        $config['database'],
        $config['port']
    );

    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    // Generate CSRF token if not exists (optional here — usually done at login or page load)
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    // Only accept POST requests
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            header("Location: admin_dashboard.php?");
            exit;
        }

        // Get and validate inputs
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);

        if (!$id || empty($token)) {
            header("Location: admin_dashboard.php?reject=invalid_input");
            exit;
        }

        // Prepare and execute update query to reject the testimony
        $stmt = $conn->prepare("UPDATE testimonies_pending SET status = 'rejected' WHERE id = ? AND approval_token = ?");
        $stmt->bind_param("is", $id, $token);

        if ($stmt->execute()) {
            header("Location: admin_dashboard.php?reject=success");
            exit;
        } else {
            header("Location: admin_dashboard.php?reject=database_error");
            exit;
        }
    } else {
        // Invalid request method
        header("Location: admin_dashboard.php?reject=invalid_method");
        exit;
    }

} catch (Exception $e) {
    error_log("Testimony rejection error: " . $e->getMessage());
    header("Location: admin_dashboard.php?reject=server_error");
    exit;
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>