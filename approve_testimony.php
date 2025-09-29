<?php
// Database connection
include 'db.php';

error_reporting(E_ALL);
ini_set("display_errors", 1);// Assuming db.php contains the database connection code

$conn = new mysqli('localhost', 'root', '', 'prayer_db', 3307);

if (!isset($_GET['token']) || empty($_GET['token'])) {
    // Redirect with error message
    header("Location: dashboard.php?error=token_missing");
    exit;
}

$token = $_GET['token'];

try {
    // 1. Check if token exists in pending testimonies
    $stmt = $conn->prepare("SELECT * FROM testimonies_pending WHERE approval_token = ? AND status = 'pending'");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        header("Location: dashboard.php?error=invalid_token");
        exit;
    }

    $testimony = $result->fetch_assoc();

    // 2. Move to approved table
    $stmt = $conn->prepare("INSERT INTO testimonies (name, initials, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $testimony['name'], $testimony['initials'], $testimony['message']);
    $stmt->execute();

    // 3. Update status in pending table
    $stmt = $conn->prepare("UPDATE testimonies_pending SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $testimony['id']);
    $stmt->execute();

    // 4. Redirect to success page
    header("Location: dashboard.php?success=approved&id=" . $testimony['id']);
    exit;

} catch (Exception $e) {
    header("Location: dashboard.php?error=database_error");
    exit;
}
?>