<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$config = [
    'host' => 'localhost',
    'port' => 3307,
    'username' => 'root',
    'password' => '',
    'database' => 'prayer_db'
];

// Initialize variables
$title = $excerpt = $devotional_date = $current_image = '';
$errors = [];
$success_message = '';

// Establish database connection
try {
    $conn = new mysqli(
        $config['host'],
        $config['username'],
        $config['password'],
        $config['database'],
        $config['port']
    );

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Check if ID parameter exists and is valid
    $id = (int) ($_GET['id'] ?? 0);
    if (!$id) {
        throw new Exception("Invalid or missing ID");
    }
    $stmt = $conn->prepare("SELECT title, excerpt, devotion_date, image FROM devotions WHERE id = ?");
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    $result = $stmt->get_result();
    if (!$result) {
        throw new Exception("get_result() failed — maybe mysqlnd missing");
    }
    if ($result->num_rows === 0) {
        throw new Exception("No devotional found with ID = $id");
    }

    $devotional = $result->fetch_assoc();
    $title = $devotional['title'];
    $excerpt = $devotional['excerpt'];
    $devotional_date = $devotional['devotion_date'];
    $current_image = $devotional['image'];

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate inputs
        $title = trim($_POST['title'] ?? '');
        $excerpt = trim($_POST['excerpt'] ?? '');
        $devotional_date = $_POST['devotional_date'] ?? '';

        if (empty($title)) {
            $errors[] = "Title is required";
        }

        if (empty($excerpt)) {
            $errors[] = "Excerpt is required";
        }

        if (empty($devotional_date)) {
            $errors[] = "Date is required";
        }

        // Process image upload if new file was uploaded
        $image_path = $current_image;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // Validate image
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $file_type = $_FILES['image']['type'];

            if (!in_array($file_type, $allowed_types)) {
                $errors[] = "Only JPG, PNG, and GIF files are allowed";
            } elseif ($_FILES['image']['size'] > 2097152) { // 2MB
                $errors[] = "Image size must be less than 2MB";
            } else {
                $target_dir = "uploads/";
                if (!is_dir($target_dir)) {
                    if (!mkdir($target_dir, 0777, true)) {
                        $errors[] = "Failed to create upload directory";
                    }
                }

                $image_name = basename($_FILES["image"]["name"]);
                $new_image_name = time() . "_" . preg_replace("/[^a-zA-Z0-9\.]/", "_", $image_name);
                $target_file = $target_dir . $new_image_name;

                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image_path = $target_file;
                    // Delete old image file if it exists and is different from new one
                    if (!empty($current_image) && file_exists($current_image) && $current_image !== $image_path) {
                        unlink($current_image);
                    }
                } else {
                    $errors[] = "Failed to upload image";
                }
            }
        }

        // Update database if no errors
        if (empty($errors)) {
            $stmt = $conn->prepare("
                UPDATE devotions 
                SET title = ?, excerpt = ?, devotion_date = ?, image = ? 
                WHERE id = ?
            ");

            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param(
                "ssssi",
                $title,
                $excerpt,
                $devotional_date,
                $image_path,
                $id
            );

            if ($stmt->execute()) {
                $success_message = "Devotional updated successfully";
                // Redirect after successful update
                header('Location: dashboard.php?message=updated');
                exit;
            } else {
                throw new Exception("Update failed: " . $stmt->error);
            }
        }
    }
} catch (Exception $e) {
    $errors[] = $e->getMessage();
} finally {
    // Close connection if it exists
    if (isset($conn)) {
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Devotional</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .form-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .current-image {
            max-width: 200px;
            max-height: 200px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2 class="mb-4">Edit Devotional</h2>

            <!-- Display error messages if any -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <form action="Edevotional.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" class="form-control"
                            value="<?php echo htmlspecialchars($title); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="excerpt">Excerpt</label>
                        <textarea name="excerpt" id="excerpt" class="form-control" rows="3" required><?php
                        echo htmlspecialchars($excerpt);
                        ?></textarea>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="devotional_date">Date</label>
                    <input type="date" name="devotional_date" id="devotional_date" class="form-control"
                        value="<?php echo htmlspecialchars($devotional_date); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="image">Cover Image</label>
                    <?php if (!empty($current_image)): ?>
                        <div>
                            <p>Current Image:</p>
                            <img src="<?php echo htmlspecialchars($current_image); ?>" class="current-image img-thumbnail">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                    <small class="text-muted">Leave blank to keep current image. Only JPG, PNG, GIF allowed (Max
                        2MB)</small>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" name="submit" class="btn btn-primary">Update Devotional</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>