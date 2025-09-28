<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Database configuration
$host = "localhost";
$port = 3307;
$username = "root";
$password = "";
$database = "prayer_db";

// Connect to database
$conn = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is set and is numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepare delete statement
    $stmt = $conn->prepare("DELETE FROM testimonies WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['message'] = "Testimony deleted successfully.";
            } else {
                $_SESSION['error'] = "No testimony found with the given ID.";
            }
        } else {
            $_SESSION['error'] = "Error executing delete: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Failed to prepare statement: " . $conn->error;
    }
} else {
    $_SESSION['error'] = "Invalid or missing testimony ID.";
}

$conn->close();

// Redirect back to admin/testimony management page
header("Location: dashboard.php");
exit();
?>