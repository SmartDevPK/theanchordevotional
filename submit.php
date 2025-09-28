<?php
// Enable error reporting for debugging
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
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Only handle POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Please submit the form properly.");
}

// Safely fetch values from $_POST with defaults
$name   = trim($_POST['name']   ?? '');
$email  = trim($_POST['email']  ?? '');
$prayer = trim($_POST['prayer'] ?? '');
$share  = !empty($_POST['sharePublicly']) ? 1 : 0;

if ($name === '' || $prayer === '') {
    die("Name and prayer are required fields.");
}

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format.");
}

// prepare and bind — include sharePublicly if you have a column for it
$stmt = $conn->prepare("INSERT INTO prayer_requests (name, email, prayer, share_publicly) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $name, $email, $prayer, $share);

if ($stmt->execute()) {
    echo "Thank you for your prayer request $name. Your prayer request has been received. Our prayer team will lift up your request to God. You will be in our prayers.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
