<?php
ob_start();

session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

// DB connection
$host = "localhost";
$port = 3307;
$username = "root";
$password = "";
$database = "prayer_db";

$conn = new mysqli($host, $username, $password, $database, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM devotions ORDER BY devotion_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Daily Devotions</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f9f9f9;
            padding: 40px;
            margin: 0;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 40px;
        }

        .container {
            max-width: 900px;
            margin: auto;
        }

        .devotion-card {
            background: #fff;
            border-radius: 8px;
            margin-bottom: 25px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s ease;
        }

        .devotion-card:hover {
            transform: scale(1.01);
        }

        .devotion-image {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 6px;
            margin-bottom: 15px;
            display: block;
        }

        .devotion-card h3 {
            color: #007BFF;
            margin-bottom: 10px;
        }

        .devotion-card p {
            color: #555;
            line-height: 1.6;
        }

        .devotion-date {
            font-size: 14px;
            color: #888;
            margin-bottom: 15px;
        }

        .read-more {
            display: inline-block;
            margin-top: 10px;
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
        }

        .read-more:hover {
            text-decoration: underline;
        }

        .no-data {
            text-align: center;
            color: #888;
            font-size: 18px;
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

        .delete-button {
            margin-top: 15px;
            background-color: #dc3545;
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .delete-button:hover {
            background-color: #c82333;
        }

        .update-link {
            display: inline-block;
            padding: 8px 15px;
            background: #007BFF;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            margin-left: 10px;
        }

        .update-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Past Devotions</h1>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='devotion-card'>";

                if (!empty($row['image'])) {
                    echo "<img class='devotion-image' src='" . htmlspecialchars($row['image']) . "' alt='Devotion Image'>";
                }

                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<div class='devotion-date'>" . htmlspecialchars(date("F j, Y", strtotime($row['devotion_date']))) . "</div>";
                echo "<p>" . nl2br(htmlspecialchars($row['excerpt'])) . "</p>";

                if (!empty($row['read_more_link'])) {
                    echo "<a class='read-more' href='" . htmlspecialchars($row['read_more_link']) . "' target='_blank'>Read More &raquo;</a>";
                }

                // Delete form
                echo "<form method='POST' action='delete_devotions.php' onsubmit='return confirm(\"Are you sure you want to delete this devotion?\");' style='display: inline-block;'>";
                echo "<input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "' />";
                echo "<input type='hidden' name='table' value='devotions' />";
                echo "<button type='submit' class='delete-button'>Delete</button>";
                echo "</form>";

                // Update link
                echo "<a href='update_devotion.php?id=" . urlencode($row['id']) . "' class='update-link'>Update</a>";

                echo "</div>";
            }
        } else {
            echo "<p class='no-data'>No devotions found.</p>";
        }

        $conn->close();
        ?>
        <li><a href="dashboard.php">Return to Dashboard</a></li>
    </div>
</body>

</html>

<?php ob_end_flush(); ?>