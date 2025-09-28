<?php
// submit_comment.php
header('Content-Type: application/json');
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

// Get POST data
$name = $_POST['name'] ?? '';
$comment = $_POST['comment'] ?? '';

if (empty($name) || empty($comment)) {
    // Redirect back with error message (optional)
    header("Location: index.php?error=emptyfields");
    exit;
}

// Sanitize input
$name = htmlspecialchars($name, ENT_QUOTES);
$comment = htmlspecialchars($comment, ENT_QUOTES);

// Insert into database
$stmt = $conn->prepare("INSERT INTO comments (name, comment) VALUES (?, ?)");
$stmt->bind_param("ss", $name, $comment);

if ($stmt->execute()) {
    // Redirect to comment section on success
    $stmt->close();
    $conn->close();
    header("Location: index.php#commentsList");
    exit;
} else {
    // Redirect back with error (optional)
    $stmt->close();
    $conn->close();
    header("Location: index.php?error=submitfail");
    exit;
}
