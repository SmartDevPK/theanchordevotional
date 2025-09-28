<?php
header('Content-Type: application/json'); // Important for JSON output
error_reporting(E_ALL);
ini_set("display_errors", 1);

// DB Config
$host = "localhost";
$port = 3307;
$username = "root";
$password = "";
$database = "prayer_db";

// DB Connection
$conn = new mysqli($host, $username, $password, $database, $port);

// Connection Check
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed."]);
    exit;
}

// Query to fetch testimonies
$sql = "SELECT name, initials, message, DATE_FORMAT(date, '%M %d, %Y') AS date 
        FROM testimonies 
        ORDER BY date DESC 
        LIMIT 6";

$result = $conn->query($sql);

// Check query result
$testimonies = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $testimonies[] = $row;
    }
}

// Close connection
$conn->close();

// Return as JSON
echo json_encode($testimonies);
?>