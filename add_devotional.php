<?php
// Enable error reporting for development
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Initialize messages
$errorMessage = '';
$successMessage = '';

// DB Configuration
$config = [
    'host' => 'localhost',
    'port' => 3307,
    'username' => 'root',
    'password' => '',
    'database' => 'prayer_db'
];

// Default values
$title = $excerpt = $devotion_date = $read_more_link = $current_image = '';

// Main logic
try {
    // Connect to DB
    $conn = new mysqli($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    // Get devotional ID (required for editing)
    $devotionId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if ($devotionId <= 0) {
        throw new Exception("Invalid or missing devotional ID.");
    }

    // Fetch existing data
    $stmt = $conn->prepare("SELECT * FROM devotions WHERE id = ?");
    $stmt->bind_param("i", $devotionId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception("Devotional with ID = $devotionId not found.");
    }

    $devotionId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if ($devotionId <= 0) {
        throw new Exception("Invalid or missing devotional ID.");
    }

    $devotion = $result->fetch_assoc();
    $title = $devotion['title'];
    $excerpt = $devotion['excerpt'];
    $devotion_date = $devotion['devotion_date'];
    $read_more_link = $devotion['read_more_link'];
    $current_image = $devotion['image'];

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $title = trim($_POST['title'] ?? '');
        $excerpt = trim($_POST['excerpt'] ?? '');
        $devotion_date = $_POST['devotion_date'] ?? '';
        $read_more_link = trim($_POST['read_more_link'] ?? '');
        $image_path = $current_image;

        if (!$title || !$excerpt || !$devotion_date || !$read_more_link) {
            throw new Exception("All fields are required.");
        }

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $file_type = mime_content_type($_FILES['image']['tmp_name']);

            if (!in_array($file_type, $allowed_types)) {
                throw new Exception("Only JPG, PNG, and GIF images are allowed.");
            }

            $upload_dir = "uploads";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $new_image = "devotion_" . time() . "_" . basename($_FILES['image']['name']);
            $image_path = "$upload_dir/$new_image";

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                throw new Exception("Failed to upload image.");
            }

            if ($current_image && file_exists($current_image) && $current_image !== $image_path) {
                unlink($current_image);
            }
        }

        // Update DB
        $stmt = $conn->prepare("UPDATE devotions SET image=?, title=?, devotion_date=?, excerpt=?, read_more_link=? WHERE id=?");
        $stmt->bind_param("sssssi", $image_path, $title, $devotion_date, $excerpt, $read_more_link, $devotionId);

        if (!$stmt->execute()) {
            throw new Exception("Update failed: " . $stmt->error);
        }

        // Redirect
        header("Location: dashboard.php?message=Devotional+Updated");
        exit;
    }

} catch (Exception $e) {
    $errorMessage = $e->getMessage();
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Devotional</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 800px;
            margin: 2rem auto;
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 0.5rem;
        }

        .preview-image {
            max-width: 200px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2 class="form-title text-center">Edit Devotional</h2>

            <?php if ($errorMessage): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <!-- Image -->
                <div class="mb-4">
                    <label for="image" class="form-label">Devotional Image</label>
                    <?php if ($current_image): ?>
                        <div>
                            <img src="<?= htmlspecialchars($current_image) ?>" class="img-thumbnail preview-image"
                                alt="Current Image">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                    <small class="text-muted">Leave blank to keep current image.</small>
                </div>

                <!-- Title -->
                <div class="mb-4">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control"
                        value="<?= htmlspecialchars($title) ?>" required>
                </div>

                <!-- Date -->
                <div class="mb-4">
                    <label for="devotion_date" class="form-label">Date</label>
                    <input type="date" name="devotion_date" id="devotion_date" class="form-control"
                        value="<?= htmlspecialchars($devotion_date) ?>" required>
                </div>

                <!-- Excerpt -->
                <div class="mb-4">
                    <label for="excerpt" class="form-label">Excerpt</label>
                    <textarea name="excerpt" id="excerpt" class="form-control" rows="4"
                        required><?= htmlspecialchars($excerpt) ?></textarea>
                </div>

                <!-- Read More Link -->
                <div class="mb-4">
                    <label for="read_more_link" class="form-label">Read More Link</label>
                    <input type="url" name="read_more_link" id="read_more_link" class="form-control"
                        value="<?= htmlspecialchars($read_more_link) ?>" required>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="dashboard.php" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Devotional</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>