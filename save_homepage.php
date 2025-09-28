<?php
include("db.php");

// upload the image file if provided
$cover_path = null;
if (!empty($_FILES['cover_image']['name'])) {
    $upload_dir = "uploads/";
    $cover_path = $upload_dir . basename($_FILES['cover_image']['name']);
    move_uploaded_file($_FILES['cover_image']['tmp_name'], $cover_path);
}

// insert or update content
$stmt = $mysqli->prepare("
  INSERT INTO landing_page_content
  (hero_title, hero_subtitle, hero_description, cover_image, featured_topic, featured_date, featured_intro, verse_of_day, verse_reference)
  VALUES (?,?,?,?,?,?,?,?,?)
  ON DUPLICATE KEY UPDATE
  hero_title=VALUES(hero_title),
  hero_subtitle=VALUES(hero_subtitle),
  hero_description=VALUES(hero_description),
  cover_image=VALUES(cover_image),
  featured_topic=VALUES(featured_topic),
  featured_date=VALUES(featured_date),
  featured_intro=VALUES(featured_intro),
  verse_of_day=VALUES(verse_of_day),
  verse_reference=VALUES(verse_reference)
");

$stmt->bind_param(
  "sssssssss",
  $_POST['hero-title'],
  $_POST['hero-subtitle'],
  $_POST['hero-description'],
  $cover_path,
  $_POST['featured_topic'],
  $_POST['featured_date'],
  $_POST['featured_intro'],
  $_POST['verse_of_day'],
  $_POST['verse_reference']
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
?>
