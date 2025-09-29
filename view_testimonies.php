<?php
// Database connection
include 'db.php'; // Assuming db.php contains the database connection code

$conn = new mysqli('localhost', 'root', '', 'prayer_db', 3307);

// Get approved testimonies
$result = $conn->query("SELECT * FROM testimonies ORDER BY date DESC");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Our Testimonies</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .testimony {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
        }

        .initials {
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
        }
    </style>
</head>

<body>
    <h1>Shared Testimonies</h1>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="testimony">
                <div class="initials"><?= htmlspecialchars($row['initials']) ?></div>
                <h3><?= htmlspecialchars($row['name']) ?></h3>
                <p><?= nl2br(htmlspecialchars($row['message'])) ?></p>
                <small><?= $row['created_at'] ?></small>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No testimonies yet. Be the first to share!</p>
    <?php endif; ?>
</body>

</html>