<?php
require_once 'db_connection.php';

session_start();

// Check admin authentication
function isAdmin()
{
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Handle hero section update
function updateHeroSection($title, $description, $buttonText, $image = null)
{
    global $pdo;

    try {
        $data = [
            'title' => $title,
            'description' => $description,
            'button_text' => $buttonText
        ];

        if ($image) {
            // Handle image upload
            $uploadDir = 'uploads/hero/';
            $fileName = uniqid() . '_' . basename($image['name']);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($image['tmp_name'], $targetPath)) {
                $data['image_path'] = $targetPath;
            }
        }

        $sql = "UPDATE hero_section SET 
                title = :title, 
                description = :description, 
                button_text = :button_text" .
            (isset($data['image_path']) ? ", image_path = :image_path" : "") .
            " WHERE id = 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);

        return true;
    } catch (PDOException $e) {
        error_log("Hero section update failed: " . $e->getMessage());
        return false;
    }
}

// Add new resource
function addResource($title, $category, $description, $image, $file)
{
    global $pdo;

    try {
        // Handle file uploads
        $imagePath = uploadFile($image, 'uploads/resources/images/');
        $filePath = uploadFile($file, 'uploads/resources/files/');

        if (!$imagePath || !$filePath) {
            return false;
        }

        $stmt = $pdo->prepare("INSERT INTO resources 
                              (title, category, description, image_path, file_path, file_size) 
                              VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $title,
            $category,
            $description,
            $imagePath,
            $filePath,
            $file['size']
        ]);

        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Resource addition failed: " . $e->getMessage());
        return false;
    }
}

// Helper function for file uploads
function uploadFile($file, $targetDir)
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    $fileName = uniqid() . '_' . basename($file['name']);
    $targetPath = $targetDir . $fileName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        return false;
    }

    return $targetPath;
}

// Get all resources
function getResources($category = null)
{
    global $pdo;

    try {
        $sql = "SELECT * FROM resources";
        if ($category) {
            $sql .= " WHERE category = ?";
        }
        $sql .= " ORDER BY created_at DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($category ? [$category] : []);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Failed to fetch resources: " . $e->getMessage());
        return [];
    }
}

// Update resource
function updateResource($id, $title, $category, $description, $image = null, $file = null)
{
    global $pdo;

    try {
        $data = [
            'id' => $id,
            'title' => $title,
            'category' => $category,
            'description' => $description
        ];

        $sql = "UPDATE resources SET 
                title = :title, 
                category = :category, 
                description = :description";

        if ($image) {
            $imagePath = uploadFile($image, 'uploads/resources/images/');
            if ($imagePath) {
                $data['image_path'] = $imagePath;
                $sql .= ", image_path = :image_path";
            }
        }

        if ($file) {
            $filePath = uploadFile($file, 'uploads/resources/files/');
            if ($filePath) {
                $data['file_path'] = $filePath;
                $data['file_size'] = $file['size'];
                $sql .= ", file_path = :file_path, file_size = :file_size";
            }
        }

        $sql .= " WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute($data);
    } catch (PDOException $e) {
        error_log("Resource update failed: " . $e->getMessage());
        return false;
    }
}

// Delete resource
function deleteResource($id)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("DELETE FROM resources WHERE id = ?");
        return $stmt->execute([$id]);
    } catch (PDOException $e) {
        error_log("Resource deletion failed: " . $e->getMessage());
        return false;
    }
}

// Category management functions would follow similar patterns
?>