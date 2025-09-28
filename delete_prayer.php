<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Database connection parameters
$host = "localhost";
$port = 3307;
$username = "root";
$password = "";
$database = "prayer_db";

// Create connection
$conn = new mysqli($host, $username, $password, $database, $port);

// check connection
if ($conn->connect_error) {
    die("connection failed:" . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $stmt = $conn->prepare("DELETE FROM prayer_request WHERE id = ?");
    $stmt->bind_param("i", $id);
}

if ($stmt->execute()) {
    header("Location: prayer_request.php");
    exit();
} else {
    echo "Error deleting record: " . $stmt->error;
}

$conn->close();
?>