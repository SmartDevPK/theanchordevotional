<?php
include("db.php")

session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    // Handle thumbnail upload
    $thumbnail = $_FILES['thumbnail'];
    $thumbnail_name = time() . '_' . $thumbnail['name'];
    move_uploaded_file($thumbnail['tmp_name'], 'uploads/thumbnails/' . $thumbnail_name);

    // Handle resource file upload
    $file = $_FILES['resource_file'];
    $file_name = time() . '_' . $file['name'];
    move_uploaded_file($file['tmp_name'], 'uploads/files/' . $file_name);

    // Insert into database
    $stmt = $mysqli->prepare("INSERT INTO family_resources (name, category, description, thumbnail, file) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $category, $description, $thumbnail_name, $file_name);
    $stmt->execute();

    header("Location: family_resources.php");
}
?>
