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

// Connect to the database
$mysqli = new mysqli($host, $username, $password, $database, $port);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Handle file upload
$image_path = '';
if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === 0) {
    $upload_dir = "uploads/";
    $filename = basename($_FILES["cover_image"]["name"]);
    $unique_name = time() . '_' . $filename;
    $target_path = $upload_dir . $unique_name;

    if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $target_path)) {
        $image_path = $target_path;
    } else {
        die("Image upload failed.");
    }
}

// Get and sanitize form inputs
$title = trim($_POST['title'] ?? '');
$excerpt = trim($_POST['excerpt'] ?? '');
$devotion_date = $_POST['devotion_date'] ?? '';

// Validate required fields
if ($title && $excerpt && $devotion_date && $image_path) {
    // Insert into the database
    $stmt = $mysqli->prepare("INSERT INTO devotions (title, excerpt, devotion_date, image_path) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $excerpt, $devotion_date, $image_path);

    if ($stmt->execute()) {
        header("Location: dashboard.php?message=success");
        exit();
    } else {
        echo "Database error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "All fields including image are required.";
}

$mysqli->close();
?>