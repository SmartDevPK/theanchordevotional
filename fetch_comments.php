<?php
// fetch_comments.php

header('Content-Type: application/json');

// DB config
$host = "localhost";
$port = 3307;
$username = "root";
$password = "";
$database = "prayer_db";

// DB connection
$conn = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["message" => "Database connection failed"]);
    exit;
}

$sql = "SELECT name, comment, created_at FROM comments ORDER BY created_at DESC";
$result = $conn->query($sql);

$comments = [];

while ($row = $result->fetch_assoc()) {
    $comments[] = [
        "name" => htmlspecialchars($row['name'], ENT_QUOTES),
        "comment" => htmlspecialchars($row['comment'], ENT_QUOTES),
        "created_at" => date("F j, Y, g:i a", strtotime($row['created_at']))
    ];
}

echo json_encode($comments);

$conn->close();
?>