<?php
session_start();

error_reporting(E_ALL);
ini_set("display_errors", 1);

require 'vendor/autoload.php';

// Database connection parameters
$host = "localhost";
$port = 3307;
$username = "root";
$password = "";
$database = "prayer_db";

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Only POST requests are allowed');
    }

    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        throw new Exception('Invalid CSRF token');
    }

    if (!isset($_POST['id'])) {
        throw new Exception('No ID provided');
    }

    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    if ($id === false || $id <= 0) {
        throw new Exception('Invalid subscriber ID');
    }

    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $checkStmt = $pdo->prepare("SELECT email FROM subscribers WHERE id = ?");
    $checkStmt->execute([$id]);
    if ($checkStmt->rowCount() === 0) {
        throw new Exception('Subscriber not found');
    }

    $deleteStmt = $pdo->prepare("DELETE FROM subscribers WHERE id = ?");
    $deleteStmt->execute([$id]);

    if ($deleteStmt->rowCount() > 0) {
        // Redirect on success
        header("Location: dashboard.php?message=deleted");
        exit();
    } else {
        throw new Exception('Delete operation failed');
    }
} catch (Exception $e) {
    // You can pass error messages via query string or session
    header("Location: dashboard.php?error=" . urlencode($e->getMessage()));
    exit();
}
