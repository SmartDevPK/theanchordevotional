topic<?php
// Display errors for debugging (disable in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// DB Config
$config = [
    'host' => 'localhost',
    'port' => 3307,
    'username' => 'root',
    'password' => '',
    'database' => 'prayer_db'
];

$successMessage = '';
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $topic = trim($_POST['topic'] ?? '');
    $date = $_POST['date'] ?? '';
    $image = $_FILES['image'] ?? null;
    $pdf = $_FILES['pdf'] ?? null;

    try {
        if (!$topic || !$date || !$image) {
            throw new Exception("Title, date, and image are required.");
        }

        // Connect to DB
        $conn = new mysqli($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);
        if ($conn->connect_error) {
            throw new Exception("Database connection failed: " . $conn->connect_error);
        }

        // Upload image
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $imageMime = mime_content_type($image['tmp_name']);
        if (!in_array($imageMime, $allowedTypes)) {
            throw new Exception("Invalid image format. Only JPG, PNG, and GIF are allowed.");
        }

        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $imageName = "devotion_" . time() . "_" . basename($image['name']);
        $imagePath = $uploadDir . $imageName;

        if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
            throw new Exception("Failed to upload image.");
        }

        // Upload PDF (optional)
        $pdfPath = null;
        if ($pdf && $pdf['error'] === UPLOAD_ERR_OK) {
            if (mime_content_type($pdf['tmp_name']) !== 'application/pdf') {
                throw new Exception("Only PDF files are allowed.");
            }

            $pdfName = "devotion_" . time() . "_" . basename($pdf['name']);
            $pdfPath = $uploadDir . $pdfName;

            if (!move_uploaded_file($pdf['tmp_name'], $pdfPath)) {
                throw new Exception("Failed to upload PDF.");
            }
        }

        // Insert into DB
        $stmt = $conn->prepare("INSERT INTO devotion (topic, date, image_path, pdf_path) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $topic, $date, $imagePath, $pdfPath);
        if (!$stmt->execute()) {
            throw new Exception("Database insert failed: " . $stmt->error);
        }

        $successMessage = "Devotional added successfully!";
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add New Devotional</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 60px auto;
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .form-header h2 {
            margin-bottom: 5px;
            font-size: 28px;
            color: #2c3e50;
        }

        .form-header p {
            color: #7f8c8d;
            font-size: 15px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #34495e;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 20px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #3498db;
            outline: none;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            gap: 15px;
        }

        .btn {
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        .btn-success {
            background-color: #27ae60;
            color: white;
        }

        .btn-success:hover {
            background-color: #219150;
        }

        .btn-secondary {
            background-color: #bdc3c7;
            color: #2c3e50;
        }

        .btn-secondary:hover {
            background-color: #a6acaf;
        }

        .message {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="form-header">
            <h2>Add New Devotional</h2>
            <p>Share your spiritual insights with the community</p>
        </div>

        <?php if ($successMessage): ?>
            <div class="message success"><?= htmlspecialchars($successMessage) ?></div>
        <?php elseif ($errorMessage): ?>
            <div class="message error"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label for="topic" class="form-label">Title / Topic</label>
            <input type="text" class="form-control" name="topic" id="topic" required>

            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" name="date" id="date" required>

            <label for="image" class="form-label">Cover Image</label>
            <input type="file" class="form-control" name="image" id="image" accept="image/*" required>

            <label for="pdf" class="form-label">Upload PDF (optional)</label>
            <input type="file" class="form-control" name="pdf" id="pdf" accept="application/pdf">

            <div class="button-group">
                <button type="submit" class="btn btn-success">Save Devotional</button>
                <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('date').valueAsDate = new Date();
    </script>

</body>

</html>