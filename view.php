<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Database connection
$host = "localhost";
$port = 3307;
$username = "root";
$password = "";
$database = "prayer_db";

$conn = new mysqli($host, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get devotion ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch devotion data
$stmt = $conn->prepare("SELECT * FROM todayDevotions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$devotion = $result->fetch_assoc();
$stmt->close();

// Decode JSON sections
$sections = $devotion ? json_decode($devotion['sections'], true) : [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Devotion - The Anchor Devotional</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #ad3128;
            --secondary: #2c3e50;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #f5f5f5;
        }

        .devotion-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
        }

        .section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .section-header {
            color: var(--primary);
            margin-bottom: 15px;
        }

        .verse-reference {
            font-weight: bold;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Devotion Details</h1>
            <a href="manage.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <?php if ($devotion): ?>
            <div class="devotion-container">
                <h2 class="mb-4"><?= htmlspecialchars($devotion['verse_reference']) ?></h2>

                <div class="verse-text mb-4">
                    <p><?= nl2br(htmlspecialchars($devotion['verse_text'])) ?></p>
                </div>

                <div class="introduction section">
                    <h4 class="section-header">Introduction</h4>
                    <p><?= nl2br(htmlspecialchars($devotion['introduction_text'])) ?></p>
                </div>

                <?php foreach ($sections as $key => $section): ?>
                    <div class="section">
                        <h4 class="section-header"><?= htmlspecialchars($section['heading']) ?></h4>
                        <p><?= nl2br(htmlspecialchars($section['content'])) ?></p>
                    </div>
                <?php endforeach; ?>

                <div class="mt-4 pt-3 border-top">
                    <small class="text-muted">
                        Created: <?= date('F j, Y \a\t g:i a', strtotime($devotion['created_at'])) ?>
                        <?php if ($devotion['created_at'] != $devotion['updated_at']): ?>
                            <br>Last updated: <?= date('F j, Y \a\t g:i a', strtotime($devotion['updated_at'])) ?>
                        <?php endif; ?>
                    </small>
                </div>
            </div>

            <div class="text-end">
                <a href="edit.php?id=<?= $devotion['id'] ?>" class="btn btn-primary me-2">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
                <a href="manage.php?action=delete&id=<?= $devotion['id'] ?>" class="btn btn-danger"
                    onclick="return confirm('Are you sure you want to delete this devotion?')">
                    <i class="fas fa-trash-alt me-1"></i> Delete
                </a>
            </div>
        <?php else: ?>
            <div class="alert alert-danger">
                Devotion not found. <a href="manage.php">Return to list</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php $conn->close(); ?>