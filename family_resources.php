<!-- <?php
include("db.php"); // your mysqli connection

// --- Handle Form Submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    // Handle thumbnail upload
    $thumbnail_path = null;
    if (!empty($_FILES['thumbnail']['name'])) {
        $upload_dir = "uploads/thumbnails/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $thumbnail_path = $upload_dir . basename($_FILES['thumbnail']['name']);
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnail_path);
    }

    // Handle resource file upload
    $file_path = null;
    if (!empty($_FILES['resource_file']['name'])) {
        $upload_dir = "uploads/resources/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $file_path = $upload_dir . basename($_FILES['resource_file']['name']);
        move_uploaded_file($_FILES['resource_file']['tmp_name'], $file_path);
    }

    $stmt = $mysqli->prepare("
        INSERT INTO family_resources (name, category, description, thumbnail, file_path)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssss", $name, $category, $description, $thumbnail_path, $file_path);
    if ($stmt->execute()) {
        echo "<script>alert('Resource uploaded successfully'); window.location.href='family_resources.php';</script>";
    } else {
        $error = addslashes($mysqli->error);
        echo "<script>alert('Error uploading resource: $error'); window.history.back();</script>";
    }
    exit;
}

// --- Fetch All Resources ---
$resources = $mysqli->query("SELECT * FROM family_resources ORDER BY uploaded_at DESC");
?> -->
