<?php
session_start();

error_reporting(E_ALL);
ini_set("display_errors", 1);

// DB connection parameters
$host = "localhost";
$port = 3307;
$username = "root";
$password = "";
$database = "prayer_db";

$conn = new mysqli($host, $username, $password, $database, $port);
if ($conn->connect_error) {
    die("Connection failed: {$conn->connect_error}");
}

// Check if ID is sent via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Delete the devotional from DB
    $stmt = $conn->prepare("DELETE FROM devotions WHERE id = ?");
    if ($stmt === false) {
        echo "Prepare failed: {$conn->error}";
        $conn->close();
        exit();
    }
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        // Redirect back to the dashboard or devotionals page
        header("Location: dashboard.php?message=deleted");
        exit();
    } else {
        echo "Error deleting devotional: {$stmt->error}";
        $stmt->close();
        $conn->close();
    }
} else {
    echo "Invalid request.";
}
