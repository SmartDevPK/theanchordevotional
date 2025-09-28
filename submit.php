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

// check connection
if ($conn->connect_error) {
    die("connection failed:" . $conn->connect_error);
}

// sanitize and validate input
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$prayer = trim($_POST['prayer']);
$share = isset($_POST['sharePublicly']) ? 1 : 0;




if (empty($name) || empty($prayer)) {
    die("Name and prayer are required fields.");
}
;

if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format.");
}

// prepare and bind
$stmt = $conn->prepare("INSERT INTO prayer_requests (name, email, prayer) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $prayer);

if ($stmt->execute()) {
    echo "Thank you for your prayer request $name. Our team will pray for this need.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

?>