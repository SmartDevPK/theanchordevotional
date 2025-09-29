<?php
include("db.php");

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

    // Check if a devotion already exists
    $result = $mysqli->query("SELECT id, devotion_image FROM devotions LIMIT 1");
    if ($result->num_rows > 0) {
        // Update the existing devotion
        $row = $result->fetch_assoc();
        $id = $row['id'];

        // Keep old image if no new image uploaded
        if (!$devotion_image) {
            $devotion_image = $row['devotion_image'];
        }

        $stmt = $mysqli->prepare("
            UPDATE devotions 
            SET topic=?, devotion_date=?, devotion_image=?, devotion_verse=?, verse_reference=?, devotion_intro=?, devotion_content=?, devotion_prayer=?, devotion_tags=? 
            WHERE id=?
        ");
        $stmt->bind_param(
            "sssssssssi",
            $topic,
            $devotion_date,
            $devotion_image,
            $devotion_verse,
            $verse_reference,
            $devotion_intro,
            $devotion_content,
            $devotion_prayer,
            $devotion_tags,
            $id
        );
        $action = "updated";
    } else {
        // Insert new devotion
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
        $action = "added";
    }

    if ($stmt->execute()) {
        echo "<script>
                alert('Devotion $action successfully!');
                window.location.href = 'dashboard.php'; 
              </script>";
    } else {
        $error = addslashes($mysqli->error); 
        echo "<script>
                alert('Error: $error');
                window.history.back(); 
              </script>";
    }
}

// Fetch the single devotion for editing/viewing
$devotion = [];
$result = $mysqli->query("SELECT * FROM devotions LIMIT 1");
if ($result->num_rows > 0) {
    $devotion = $result->fetch_assoc();
}
?>
