<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Database connection settings
$host = "localhost";
$port = 3307;
$username = "root";
$password = "";
$database = "prayer_db";

// Initialize variables
$devotions = [];
$month = isset($_GET['month']) ? intval($_GET['month']) : '';
$year = isset($_GET['year']) ? intval($_GET['year']) : '';

// Pagination settings
$per_page = 6; // Number of items per page
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($current_page - 1) * $per_page;

// Check if this is an AJAX request
$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

try {
    // Connect to MySQL
    $conn = new mysqli($host, $username, $password, $database, $port);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: {$conn->connect_error}");
    }

    // Build the base query for counting total records
    $count_sql = "SELECT COUNT(*) as total FROM devotions WHERE 1";
    // Build the base query for fetching data
    $sql = "SELECT id, title, excerpt, devotion_date, image FROM devotions WHERE 1";

    if (!empty($month)) {
        $count_sql .= " AND MONTH(devotion_date) = $month";
        $sql .= " AND MONTH(devotion_date) = $month";
    }

    if (!empty($year)) {
        $count_sql .= " AND YEAR(devotion_date) = $year";
        $sql .= " AND YEAR(devotion_date) = $year";
    }

    $sql .= " ORDER BY devotion_date DESC LIMIT $offset, $per_page";

    // Get total count for pagination
    $count_result = $conn->query($count_sql);
    $total_rows = $count_result->fetch_assoc()['total'];
    $total_pages = ceil($total_rows / $per_page);

    // Execute query for data
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $devotions[] = $row;
        }
    }

} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    // You might want to show a user-friendly message
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}

// If this is an AJAX request, return just the results HTML
if ($is_ajax) {
    ob_start(); // Start output buffering
    ?>
    <div class="devotions-grid">
        <?php if (!empty($devotions)): ?>
            <?php foreach ($devotions as $devotion): ?>
                <div class="devotion-card">
                    <img src="<?= htmlspecialchars($devotion['image'] ?? 'default-devotion.jpg') ?>"
                        alt="<?= htmlspecialchars($devotion['title']) ?>" class="devotion-image">
                    <div class="devotion-content">
                        <div class="devotion-date">
                            <?= date('F j, Y', strtotime($devotion['devotion_date'])) ?>
                        </div>
                        <h3 class="devotion-title"><?= htmlspecialchars($devotion['title']) ?></h3>
                        <p class="devotion-excerpt"><?= htmlspecialchars($devotion['excerpt']) ?></p>
                        <a href="past-devotion.php?id=<?= $devotion['id'] ?>" class="read-more">
                            Read More <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No devotions found for the selected filter.</p>
        <?php endif; ?>
    </div>

    <div class="pagination">
        <?php if ($current_page > 1): ?>
            <a href="?page=<?= $current_page - 1 ?><?= !empty($month) ? "&month=$month" : '' ?><?= !empty($year) ? "&year=$year" : '' ?>"
                class="page-link"><i class="fas fa-chevron-left"></i></a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?><?= !empty($month) ? "&month=$month" : '' ?><?= !empty($year) ? "&year=$year" : '' ?>"
                class="page-link <?= $i == $current_page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($current_page < $total_pages): ?>
            <a href="?page=<?= $current_page + 1 ?><?= !empty($month) ? "&month=$month" : '' ?><?= !empty($year) ? "&year=$year" : '' ?>"
                class="page-link"><i class="fas fa-chevron-right"></i></a>
        <?php endif; ?>
    </div>
    <?php
    $html = ob_get_clean(); // Get the buffered output
    echo $html;
    exit; // Stop further execution for AJAX requests
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Past Devotions - The Anchor Devotional</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* All your existing CSS styles */
        :root {
            --primary: #2c3e50;
            --secondary: #ad3128;
            --accent: #2c3e50;
            --light: #f8f9fa;
            --dark: #343a40;
            --success: #28a745;
            --warning: #ffc107;
            --font-main: 'Segoe UI', system-ui, -apple-system, sans-serif;
            --font-heading: 'Georgia', serif;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        /* Basic styles for the past devotions page */
        body {
            font-family: var(--font-main);
            line-height: 1.6;
            color: var(--dark);
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        header {
            background-color: var(--primary);
            color: white;
            padding: 20px 0;
            box-shadow: var(--shadow);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
        }

        .logo span {
            color: var(--secondary);
        }

        .back-btn {
            color: white;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .back-btn:hover {
            color: var(--secondary);
        }

        main {
            padding: 60px 0;
        }

        .page-title {
            text-align: center;
            margin-bottom: 40px;
            color: var(--primary);
            font-family: var(--font-heading);
            position: relative;
        }

        .page-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: var(--secondary);
            margin: 15px auto;
            border-radius: 2px;
        }

        .filter-section {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: var(--shadow);
            margin-bottom: 40px;
        }

        .filter-title {
            margin-bottom: 20px;
            color: var(--primary);
        }

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--primary);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: var(--font-main);
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(173, 49, 40, 0.2);
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            text-align: center;
            cursor: pointer;
            border: none;
            font-size: 1rem;
        }

        .btn-primary {
            background-color: var(--secondary);
            color: white;
        }

        .btn-primary:hover {
            background-color: #8c2720;
            transform: translateY(-3px);
            box-shadow: var(--shadow);
        }

        .devotions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }

        .devotion-card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .devotion-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .devotion-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .devotion-content {
            padding: 20px;
        }

        .devotion-date {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .devotion-title {
            font-size: 1.3rem;
            margin-bottom: 15px;
            color: var(--primary);
            font-family: var(--font-heading);
        }

        .devotion-excerpt {
            color: #495057;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .read-more {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .read-more:hover {
            text-decoration: underline;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 50px;
            gap: 10px;
        }

        .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 5px;
            background-color: white;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .page-link:hover,
        .page-link.active {
            background-color: var(--secondary);
            color: white;
        }

        footer {
            background-color: var(--primary);
            color: white;
            padding: 30px 0;
            text-align: center;
        }

        @media (max-width: 768px) {
            .filter-form {
                flex-direction: column;
            }

            .devotions-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <div class="header-content">
                <a href="index.php" class="logo">The <span>Anchor Devotional</span></a>
                <a href="index.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <h1 class="page-title">Past Devotions</h1>

            <div class="filter-section">
                <h2 class="filter-title">Filter Devotions</h2>
                <form class="filter-form" id="filterForm">
                    <div class="form-group">
                        <label for="month">Month</label>
                        <select id="month" name="month" class="form-control">
                            <option value="">All Months</option>
                            <?php
                            for ($m = 1; $m <= 12; $m++) {
                                $selected = ($month == $m) ? 'selected' : '';
                                $monthName = date("F", mktime(0, 0, 0, $m, 1));
                                echo "<option value=\"$m\" $selected>$monthName</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="year">Year</label>
                        <select id="year" name="year" class="form-control">
                            <option value="">All Years</option>
                            <?php
                            for ($y = 2025; $y <= 2050; $y++) {
                                $selected = ($year == $y) ? 'selected' : '';
                                echo "<option value=\"$y\" $selected>$y</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Filter Devotions</button>
                    </div>
                </form>

                <!-- Where the filtered devotion results will appear -->
                <div id="devotion-results">
                    <div class="devotions-grid">
                        <?php if (!empty($devotions)): ?>
                            <?php foreach ($devotions as $devotion): ?>
                                <div class="devotion-card">
                                    <img src="<?= htmlspecialchars($devotion['image'] ?? 'default-devotion.jpg') ?>"
                                        alt="<?= htmlspecialchars($devotion['title']) ?>" class="devotion-image">
                                    <div class="devotion-content">
                                        <div class="devotion-date">
                                            <?= date('F j, Y', strtotime($devotion['devotion_date'])) ?>
                                        </div>
                                        <h3 class="devotion-title"><?= htmlspecialchars($devotion['title']) ?></h3>
                                        <p class="devotion-excerpt"><?= htmlspecialchars($devotion['excerpt']) ?></p>
                                        <a href="past-devotion.php?id=<?= $devotion['id'] ?>" class="read-more">
                                            Read More <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No devotions found. Please check back later.</p>
                        <?php endif; ?>
                    </div>

                    <div class="pagination">
                        <?php if ($current_page > 1): ?>
                            <a href="?page=<?= $current_page - 1 ?><?= !empty($month) ? "&month=$month" : '' ?><?= !empty($year) ? "&year=$year" : '' ?>"
                                class="page-link"><i class="fas fa-chevron-left"></i></a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=<?= $i ?><?= !empty($month) ? "&month=$month" : '' ?><?= !empty($year) ? "&year=$year" : '' ?>"
                                class="page-link <?= $i == $current_page ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>

                        <?php if ($current_page < $total_pages): ?>
                            <a href="?page=<?= $current_page + 1 ?><?= !empty($month) ? "&month=$month" : '' ?><?= !empty($year) ? "&year=$year" : '' ?>"
                                class="page-link"><i class="fas fa-chevron-right"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 The Anchor Devotional. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        document.getElementById('filterForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // Get selected values
            const month = document.getElementById('month').value;
            const year = document.getElementById('year').value;

            // Show loading message
            const resultDiv = document.getElementById('devotion-results');
            resultDiv.innerHTML = "<p>Loading devotions...</p>";

            // Build query string with pagination reset to page 1
            const query = new URLSearchParams();
            if (month) query.append('month', month);
            if (year) query.append('year', year);
            query.append('page', 1); // Reset to first page when filtering

            // Fetch filtered devotions
            fetch(window.location.pathname + '?' + query.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.text())
                .then(html => {
                    resultDiv.innerHTML = html;
                    // Update browser URL without reloading
                    history.pushState(null, '', window.location.pathname + '?' + query.toString());
                })
                .catch(error => {
                    console.error('Error:', error);
                    resultDiv.innerHTML = "<p>Something went wrong. Please try again.</p>";
                });
        });

        // Handle back/forward navigation
        window.addEventListener('popstate', function () {
            // Reload the page to reflect URL changes
            window.location.reload();
        });
    </script>
</body>

</html>