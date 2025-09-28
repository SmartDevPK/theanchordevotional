<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

$host = "localhost";
$port = 3307;
$username = "root";
$password = "";
$database = "prayer_db";

// Create connection
$conn = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM prayer_requests";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Prayer Requests</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f4f8;
            padding: 40px;
            margin: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        .prayer-request {
            background: #ffffff;
            border: 1px solid #ddd;
            padding: 20px 25px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.3s ease;
        }

        .prayer-request:hover {
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        .prayer-request h3 {
            color: #007BFF;
            margin-bottom: 10px;
        }

        .prayer-request p {
            color: #444;
            line-height: 1.6;
        }

        .no-data {
            text-align: center;
            color: #999;
            font-size: 18px;
        }

        .delete-button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .delete-button:hover {
            background-color: #c82333;
        }

        li a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: background-color 0.3s ease;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        li a:hover,
        li a:focus {
            background-color: #0056b3;
            outline: none;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Prayer Requests</h1>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='prayer-request'>";
                echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
                echo "<p><strong>Prayer Request:</strong> " . nl2br(htmlspecialchars($row['prayer'])) . "</p>";
                $shared = isset($row['sharePublicly']) && $row['sharePublicly'] ? 'Yes' : 'No';
                echo "<p><strong>Shared Publicly:</strong> $shared</p>";

                // Delete form
                echo "<form method='POST' action='delete_prayer.php' onsubmit='return confirm(\"Are you sure you want to delete this prayer request?\");'>";
                echo "<input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "' />";
                echo "<button type='submit' class='delete-button'>Delete</button>";
                echo "</form>";

                echo "</div>";
            }
        } else {
            echo "<p class='no-data'>No prayer requests found.</p>";
        }

        $conn->close();
        ?>

        <li><a href="dashboardForm.php">Return to Dashboard</a></li>
    </div>

</body>

</html>