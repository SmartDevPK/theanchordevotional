<?php
include("db.php");

// --- Handle delete FIRST ---
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $mysqli->prepare("DELETE FROM landing_page_content WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        // redirect after delete
        header("Location:dashboard.php");
        exit;
    } else {
        die("Error deleting: " . $mysqli->error);
    }
}

// --- Handle update ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_homepage'])) {
    $id = (int)$_POST['id'];

    // Handle file upload
    $cover_path = null;
    if (!empty($_FILES['cover_image']['name'])) {
        $upload_dir = "uploads/";
        $cover_path = $upload_dir . basename($_FILES['cover_image']['name']);
        move_uploaded_file($_FILES['cover_image']['tmp_name'], $cover_path);
    }

    // Keep old image if new one not uploaded
    if (!$cover_path) {
        $result = $mysqli->query("SELECT cover_image FROM landing_page_content WHERE id=$id");
        $row = $result->fetch_assoc();
        $cover_path = $row['cover_image'];
    }

    $stmt = $mysqli->prepare("
        UPDATE landing_page_content 
        SET hero_title=?, hero_subtitle=?, hero_description=?, cover_image=?, 
            featured_topic=?, featured_date=?, featured_intro=?, 
            verse_of_day=?, verse_reference=?
        WHERE id=?
    ");
    $stmt->bind_param(
        "sssssssssi",
        $_POST['hero_title'],
        $_POST['hero_subtitle'],
        $_POST['hero_description'],
        $cover_path,
        $_POST['featured_topic'],
        $_POST['featured_date'],
        $_POST['featured_intro'],
        $_POST['verse_of_day'],
        $_POST['verse_reference'],
        $id
    );

    if ($stmt->execute()) {
        echo "<script>alert('Homepage updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating: " . addslashes($mysqli->error) . "');</script>";
    }
}

// --- Fetch existing content ---
$sql = "SELECT * FROM landing_page_content LIMIT 1";
$result = $mysqli->query($sql);
$content = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Homepage Content</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Manage Homepage Content</h2>

    <?php if ($content): ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $content['id']; ?>">
            <input type="hidden" name="update_homepage" value="1">

            <div class="form-group">
                <label>Main Hero Title</label>
                <input type="text" class="form-control" name="hero_title"
                       value="<?php echo htmlspecialchars($content['hero_title']); ?>" required>
            </div>

            <div class="form-group">
                <label>Hero Subtitle</label>
                <input type="text" class="form-control" name="hero_subtitle"
                       value="<?php echo htmlspecialchars($content['hero_subtitle']); ?>" required>
            </div>

            <div class="form-group">
                <label>Hero Description</label>
                <textarea class="form-control" name="hero_description" required><?php echo htmlspecialchars($content['hero_description']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Cover Image</label>
                <input type="file" class="form-control" name="cover_image">
                <?php if ($content['cover_image']): ?>
                    <img src="<?php echo $content['cover_image']; ?>" alt="Cover" style="width:150px;margin-top:10px;">
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Featured Topic</label>
                <input type="text" class="form-control" name="featured_topic"
                       value="<?php echo htmlspecialchars($content['featured_topic']); ?>" required>
            </div>

            <div class="form-group">
                <label>Publication Date</label>
                <input type="date" class="form-control" name="featured_date"
                       value="<?php echo $content['featured_date']; ?>" required>
            </div>

            <div class="form-group">
                <label>Featured Devotion Intro</label>
                <textarea class="form-control" name="featured_intro" required><?php echo htmlspecialchars($content['featured_intro']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Verse of the Day</label>
                <textarea class="form-control" name="verse_of_day" required><?php echo htmlspecialchars($content['verse_of_day']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Verse Reference</label>
                <input type="text" class="form-control" name="verse_reference"
                       value="<?php echo htmlspecialchars($content['verse_reference']); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Homepage</button>
            <!-- Delete button -->
            <a href="edit_homepage.php?delete=<?php echo $content['id']; ?>"
               class="btn btn-danger"
               onclick="return confirm('Are you sure you want to delete this content?');">
                Delete
            </a>
        </form>
    <?php else: ?>
        <p>No homepage content found.</p>
    <?php endif; ?>
 <a href="dashboard.php">Return To Dashboard</a>

</div>
</body>
</html>
