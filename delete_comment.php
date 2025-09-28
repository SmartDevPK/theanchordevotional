<?php
// delete_comment.php

session_start();
header('Content-Type: text/html');
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Database config
$host = "localhost";
$port = 3307;
$username = "root";
$password = "";
$database = "prayer_db";

// Create DB connection
$conn = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: {$conn->connect_error}");
}

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

// Handle POST request to delete comment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $commentId = (int) $_POST['id'];

    try {
        $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->bind_param("i", $commentId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['success'] = "Comment deleted successfully";
        } else {
            $_SESSION['error'] = "Comment not found or already deleted";
        }

        $stmt->close();
    } catch (Exception $e) {
        $_SESSION['error'] = "Error deleting comment: " . $e->getMessage();
    }

    header("Location: admin_dashboard.php");
    exit();
} else {
    // Invalid request, redirect back
    header("Location: admin_dashboard.php");
    exit();
}
?>