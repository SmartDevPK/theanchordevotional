<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection config
$host = "localhost";
$port = 3307;
$username = "root";
$password = "";
$database = "prayer_db";

try {
    // Create connection
    $conn = new mysqli($host, $username, $password, $database, $port);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Validate request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
        header("Location: dashboard.php?delete=invalid");
        exit;
    }

    $id = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM testimonies WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $redirect = "dashboard.php?delete=success";
    } else {
        $redirect = "dashboard.php?delete=error";
    }

    // Close resources properly
    $stmt->close();
    $conn->close();

    // Redirect after cleanup
    header("Location: " . $redirect);
    exit;

} catch (Exception $e) {
    // Log error and redirect
    error_log($e->getMessage());
    header("Location: dashboard.php?delete=error");
    exit;
}
?>