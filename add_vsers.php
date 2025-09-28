<?php
// add_verse.php (single file)

// Show errors in development
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Database configuration — adjust port, username, password if needed
$host     = "localhost";
$port     = 3307;            
$username = "root";
$password = "";
$database = "prayer_db";

// Connect to the database
$conn = new mysqli($host, $username, $password, $database, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $topic = trim($_POST['topic'] ?? '');
    $verse = trim($_POST['verse'] ?? '');
    $date  = trim($_POST['date'] ?? '');

    if (empty($date)) {
        $date = date('Y-m-d');
    }

    if (empty($topic) || empty($verse)) {
        $message = "Topic and verse are required.";
    } else {
$stmt = $conn->prepare("INSERT INTO daily_verse (topic, verse_text, devotion_date) VALUES (?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param("sss", $topic, $verse, $date);
            if ($stmt->execute()) {
                $message = "Daily verse added successfully!";
            } else {
                $message = "Database error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Database error: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Daily Verse</title>
</head>
<body>
  <h2>Add a Daily Verse / Devotion</h2>

  <?php if (!empty($message)): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
  <?php endif; ?>

  <form action="" method="POST">
    <label for="topic">Topic:</label><br>
    <input type="text" name="topic" id="topic" required placeholder="e.g. Faith and Hope"><br><br>

    <label for="verse">Verse / Content:</label><br>
    <textarea name="verse" id="verse" rows="5" cols="50" required placeholder="Enter the verse or devotional content here"></textarea><br><br>

    <label for="date">Date (optional):</label><br>
    <input type="date" name="date" id="date"><br><br>

    <button type="submit">Save Verse</button>
  </form>
</body>
</html>
