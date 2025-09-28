<?php
// --- Database connection ---
$host     = "localhost";
$port     = 3307;
$username = "root";
$password = "";
$database = "prayer_db";

$conn = new mysqli($host, $username, $password, $database, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Fetch a random verse from the table today_Devotion ---
$sql = "SELECT topic, verse_text, devotion_date FROM daily_verse ORDER BY RAND() LIMIT 1";
$result = $conn->query($sql);

if ($result && $row = $result->fetch_assoc()) {
    $verseRef  = $row['topic'] ?? "Unknown Reference";
    $verseText = $row['verse_text'] ?? "No verse found.";
    $today     = $row['devotion_date'] ?? date("l, F j, Y");
} else {
    // fallback verse if table empty
    $verseText = "And we know that in all things God works for the good of those who love him, who have been called according to his purpose.";
    $verseRef  = "Romans 8:28 (NIV)";
    $today     = date("l, F j, Y");
}

$conn->close();
?>
