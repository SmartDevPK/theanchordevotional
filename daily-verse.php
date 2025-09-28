<?php
// --- Database connection (if using DB) ---
$host = "localhost";
$port = 3307; // your port
$username = "root";
$password = "";
$database = "prayer_db"; // or your database

$conn = new mysqli($host, $username, $password, $database, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Fetch a random or latest verse ---
$sql = "SELECT verse_text, verse_reference FROM daily_verses ORDER BY RAND() LIMIT 1";
$result = $conn->query($sql);

if ($result && $row = $result->fetch_assoc()) {
    $verseText = $row['verse_text'];
    $verseRef  = $row['verse_reference'];
} else {
    // fallback verse if table empty
    $verseText = "And we know that in all things God works for the good of those who love him, who have been called according to his purpose.";
    $verseRef  = "Romans 8:28 (NIV)";
}

$conn->close();

// --- Current date ---
$today = date("l, F j, Y");
?>