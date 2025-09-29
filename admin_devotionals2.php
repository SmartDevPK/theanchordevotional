<?php
require_once 'db.php'; // your DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $excerpt = $_POST['excerpt'];
    $read_more = $_POST['read_more'];

    // Upload image
    $imageName = $_FILES['image']['name'];
    $tmpName = $_FILES['image']['tmp_name'];
    $targetDir = "uploads/";
    $imagePath = $targetDir . basename($imageName);

    if (move_uploaded_file($tmpName, $imagePath)) {
        $stmt = $conn->prepare("INSERT INTO devotional2 (title, devotion_date, excerpt, read_more_link, image_path) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $date, $excerpt, $read_more, $imagePath);

        if ($stmt->execute()) {
            header("Location: admin_devotionals2.php?success=Devotional 2 added successfully");
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Failed to upload image.";
    }
}
?>