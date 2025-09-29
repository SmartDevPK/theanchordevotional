<?php
include("db.php");

// --- Handle Deletion ---
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $mysqli->prepare("DELETE FROM devotions WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "<script>
            alert('Devotion deleted successfully');
            window.location.href='manage_devotions.php';
          </script>";
    exit;
}

// If ?edit=ID is set, fetch that devotion; otherwise fetch the latest
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $res = $mysqli->query("SELECT * FROM devotions WHERE id=$id");
} else {
    $res = $mysqli->query("SELECT * FROM devotions ORDER BY devotion_date DESC LIMIT 1");
}
$editDevotion = $res->fetch_assoc();

// --- Handle Update ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id      = (int)$_POST['id'];
    $topic   = $_POST['topic'];
    $date    = $_POST['devotion_date'];
    $verse   = $_POST['devotion_verse'];
    $ref     = $_POST['verse_reference'];
    $intro   = $_POST['devotion_intro'];
    $content = $_POST['devotion_content'];
    $prayer  = $_POST['devotion_prayer'];
    $tags    = $_POST['devotion_tags'];

    // Handle image upload
    $image_path = null;
    if (!empty($_FILES['devotion_image']['name'])) {
        $upload_dir = "uploads/";
        $image_path = $upload_dir . basename($_FILES['devotion_image']['name']);
        move_uploaded_file($_FILES['devotion_image']['tmp_name'], $image_path);
    } else {
        $res = $mysqli->query("SELECT devotion_image FROM devotions WHERE id=$id");
        $row = $res->fetch_assoc();
        $image_path = $row['devotion_image'];
    }

    // Update the devotion
    $stmt = $mysqli->prepare("
        UPDATE devotions 
        SET topic=?, devotion_date=?, devotion_image=?, devotion_verse=?, verse_reference=?, devotion_intro=?, devotion_content=?, devotion_prayer=?, devotion_tags=? 
        WHERE id=?
    ");
    $stmt->bind_param("sssssssssi", $topic, $date, $image_path, $verse, $ref, $intro, $content, $prayer, $tags, $id);
    $stmt->execute();

    echo "<script>
            alert('Devotion updated successfully');
            window.location.href='manage_devotions.php';
          </script>";
    exit;
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Edit Devotion Entry</h2>

    <?php if ($editDevotion): ?>
    <div class="card">
        <div class="card-header bg-primary text-white">
            Editing: <?php echo htmlspecialchars($editDevotion['topic']); ?>
        </div>
        <div class="card-body">

            <!-- Update Form -->
            <form method="POST" enctype="multipart/form-data" id="devotion-form">
                <input type="hidden" name="id" value="<?php echo $editDevotion['id']; ?>">

                <!-- Topic & Date -->
                <div class="form-row mb-3">
                    <div class="form-group col-md-6">
                        <label>Topic / Title</label>
                        <input type="text" name="topic" class="form-control" value="<?php echo $editDevotion['topic']; ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Date</label>
                        <input type="date" name="devotion_date" class="form-control" value="<?php echo $editDevotion['devotion_date']; ?>" required>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="form-group mb-3">
                    <label>Devotion Image</label>
                    <input type="file" name="devotion_image" class="form-control">
                    <?php if(!empty($editDevotion['devotion_image'])): ?>
                        <img src="<?php echo $editDevotion['devotion_image']; ?>" style="width:150px;margin-top:10px;">
                    <?php endif; ?>
                </div>

                <!-- Scripture & Reference -->
                <div class="form-group mb-3">
                    <label>Scripture Verse</label>
                    <textarea name="devotion_verse" class="form-control" rows="2" required><?php echo $editDevotion['devotion_verse']; ?></textarea>
                </div>
                <div class="form-group mb-3">
                    <label>Verse Reference</label>
                    <input type="text" name="verse_reference" class="form-control" value="<?php echo $editDevotion['verse_reference']; ?>" required>
                </div>

                <!-- Introduction -->
                <div class="form-group mb-3">
                    <label>Introduction</label>
                    <textarea name="devotion_intro" class="form-control" rows="3" required><?php echo $editDevotion['devotion_intro']; ?></textarea>
                </div>

                <!-- Full Content -->
                <div class="form-group mb-3">
                    <label>Full Content</label>
                    <div id="devotion-content" contenteditable="true" class="border p-2" style="min-height:150px;"><?php echo $editDevotion['devotion_content']; ?></div>
                    <textarea name="devotion_content" id="devotion-content-hidden" style="display:none;" required><?php echo $editDevotion['devotion_content']; ?></textarea>
                </div>

                <!-- Closing Prayer -->
                <div class="form-group mb-3">
                    <label>Closing Prayer (Optional)</label>
                    <textarea name="devotion_prayer" class="form-control" rows="2"><?php echo $editDevotion['devotion_prayer']; ?></textarea>
                </div>

                <!-- Tags -->
                <div class="form-group mb-3">
                    <label>Tags (comma-separated)</label>
                    <input type="text" name="devotion_tags" class="form-control" value="<?php echo $editDevotion['devotion_tags']; ?>">
                </div>

                <!-- Update Button -->
                <button type="submit" class="btn btn-success btn-lg">Update Devotion</button>
            </form>

            <!-- Delete Form -->
            <form method="GET" onsubmit="return confirm('Are you sure you want to delete this devotion?');" class="mt-3">
                <input type="hidden" name="delete" value="<?php echo $editDevotion['id']; ?>">
                <button type="submit" class="btn btn-danger btn-lg">Delete Devotion</button>
            </form>

        </div>
    </div>
    <?php else: ?>
        <p class="text-muted">No devotion found to edit.</p>
    <?php endif; ?>
</div>

<script>
const editor = document.getElementById('devotion-content');
const hidden = document.getElementById('devotion-content-hidden');

document.getElementById('devotion-form')?.addEventListener('submit', () => {
    hidden.value = editor.innerHTML;
});

editor?.addEventListener('focus', () => {
    document.execCommand('defaultParagraphSeparator', false, 'p');
});
</script>
