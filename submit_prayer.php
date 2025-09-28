<?php
// --- Database config ---
$host     = "localhost";    // your DB host
$user     = "root";         // your DB user
$password = "";             // your DB password
$dbname   = "prayer_db";    // your DB name

// --- Connect to DB ---
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Collect and sanitize form data ---
$name  = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$prayer = isset($_POST['prayer']) ? trim($_POST['prayer']) : '';
$sharePublicly = isset($_POST['sharePublicly']) ? 1 : 0;

if (empty($name) || empty($prayer)) {
    // Required fields missing
    echo "Name and Prayer Request are required.";
    exit;
}

// --- Prepare and execute insert ---
$stmt = $conn->prepare("INSERT INTO prayer_requests (name, email, prayer, share_publicly) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("sssi", $name, $email, $prayer, $sharePublicly);

if ($stmt->execute()) {
    echo "Thank you! Your prayer request has been submitted.";
} else {
    echo "Error: " . $stmt->error;
}

// --- Cleanup ---
$stmt->close();
$conn->close();
?>
