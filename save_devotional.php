<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Validate required fields
        $required = ['title', 'excerpt', 'devotional_date'];
        $errors = [];

        foreach ($required as $field) {
            if (empty(trim($_POST[$field]))) {
                $errors[] = ucfirst($field) . " is required";
            }
        }

        // Check if image was uploaded
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Image is required";
        }

        // If errors exist, redirect back with errors
        if (!empty($errors)) {
            header("Location: devotional_form.php?error=" . urlencode(implode(", ", $errors)));
            exit;
        }

        // Database connection settings
        $host = "localhost";
        $port = 3307;
        $username = "root";
        $password = "";
        $database = "prayer_db";

        // Connect to MySQL
        $conn = new mysqli($host, $username, $password, $database, $port);
        if ($conn->connect_error) {
            throw new Exception("Connection failed: {$conn->connect_error}");
        }

        // Process image upload
        $image_path = "";
        if ($_FILES["image"]["error"] === UPLOAD_ERR_OK) {
            // Validate image
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $file_type = $_FILES['image']['type'];

            if (!in_array($file_type, $allowed_types)) {
                throw new Exception("Only JPG, PNG, and GIF files are allowed.");
            }

            $target_dir = "uploads/";
            if (!is_dir($target_dir)) {
                if (!mkdir($target_dir, 0777, true)) {
                    throw new Exception("Failed to create upload directory.");
                }
            }

            $image_name = basename($_FILES["image"]["name"]);
            $new_image_name = time() . "_" . preg_replace("/[^a-zA-Z0-9\.]/", "_", $image_name);
            $target_file = $target_dir . $new_image_name;

            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                throw new Exception("Failed to move uploaded file.");
            }
            $image_path = $target_file;
        }

        // Prepare and execute SQL using prepared statements
        $stmt = $conn->prepare("INSERT INTO devotions (title, excerpt, devotion_date, image) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Prepare failed: {$conn->error}");
        }

        $stmt->bind_param(
            "ssss",
            $_POST['title'],
            $_POST['excerpt'],
            $_POST['devotional_date'],
            $image_path
        );

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: {$stmt->error}");
        }

        // Success - redirect with success message
        header("Location: dashboard.php?message=updated");
        ;
        exit;

    } catch (Exception $e) {
        // Handle any errors that occurred
        header("Location: dashboard.php" . urlencode($e->getMessage()));
        exit;
    } finally {
        // Close connections if they exist
        if (isset($stmt))
            $stmt->close();
        if (isset($conn))
            $conn->close();
    }
} else {
    // Not a POST request, redirect
    header("Location: dashboard.php");
    exit;
}
?>