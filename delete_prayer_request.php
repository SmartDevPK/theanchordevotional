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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id'])) {
        echo "No ID provided.";
        exit();
    }

    $id = intval($_POST['id']);
    if ($id <= 0) {
        echo "Invalid ID.";
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM prayer_requests WHERE id = ?");
    if ($stmt === false) {
        echo "Prepare failed: {$conn->error}";
        $conn->close();
        exit();
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        // Redirect with success message
        header("Location: dashboard.php?message=deleted");
        exit();
    } else {
        echo "Error deleting prayer request: {$stmt->error}";
        $stmt->close();
        $conn->close();
        exit();
    }
} else {
    echo "Invalid request method.";
    exit();
}
?>