<?php
include("db.php"); // $mysqli connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $topic = $_POST['topic'];
    $devotion_date = $_POST['devotion_date'];
    $devotion_verse = $_POST['devotion_verse'];
    $verse_reference = $_POST['verse_reference'];
    $devotion_intro = $_POST['devotion_intro'];
    $devotion_content = $_POST['devotion_content'];
    $devotion_prayer = $_POST['devotion_prayer'] ?? null;
    $devotion_tags = $_POST['devotion_tags'] ?? null;

    // handle file upload
    $devotion_image = null;
    if (!empty($_FILES['devotion_image']['name'])) {
        $upload_dir = "uploads/";
        $devotion_image = $upload_dir . basename($_FILES['devotion_image']['name']);
        move_uploaded_file($_FILES['devotion_image']['tmp_name'], $devotion_image);
    }

    $stmt = $mysqli->prepare("
        INSERT INTO devotions 
        (topic, devotion_date, devotion_image, devotion_verse, verse_reference, devotion_intro, devotion_content, devotion_prayer, devotion_tags) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "sssssssss",
        $topic,
        $devotion_date,
        $devotion_image,
        $devotion_verse,
        $verse_reference,
        $devotion_intro,
        $devotion_content,
        $devotion_prayer,
        $devotion_tags
    );

    if ($stmt->execute()) {
    // success alert
    echo "<script>
            alert('Homepage updated successfully!');
            window.location.href = 'dashboard.php'; 
          </script>";
} else {
    // error alert
    $error = addslashes($mysqli->error); 
    echo "<script>
            alert('Error updating homepage: $error');
            window.history.back(); 
          </script>";
}
}

$devotion = [];
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $mysqli->prepare("SELECT * FROM devotions WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $devotion = $result->fetch_assoc();
}
?>