<?php
// subscribers.php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

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

// Handle subscriber deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM subscribers WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $delete_id);
        if ($stmt->execute()) {
            $message = "Subscriber deleted successfully.";
        } else {
            $message = "Error deleting subscriber: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Export functionality
if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="subscribers_' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Email', 'Subscribed Date']);
    
    $stmt = $conn->prepare("SELECT * FROM subscribers ORDER BY created_at DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['id'],
            $row['email'],
            date("Y-m-d H:i:s", strtotime($row['created_at']))
        ]);
    }
    
    fclose($output);
    $stmt->close();
    $conn->close();
    exit();
}

// Fetch all subscribers with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// Get total count
$count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM subscribers");
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_subscribers = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_subscribers / $limit);
$count_stmt->close();

// Fetch subscribers for current page
$stmt = $conn->prepare("SELECT * FROM subscribers ORDER BY created_at DESC LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
$subscribers = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get subscriber count by month for chart
$chart_stmt = $conn->prepare("
    SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
    FROM subscribers 
    GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
    ORDER BY month DESC 
    LIMIT 12
");
$chart_stmt->execute();
$chart_result = $chart_stmt->get_result();
$monthly_data = $chart_result->fetch_all(MYSQLI_ASSOC);
$chart_stmt->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribers List - The Anchor Devotional</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .action-buttons {
            margin-bottom: 20px;
        }
        .table-actions {
            white-space: nowrap;
        }
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-anchor"></i> The Anchor Devotional
            </a>
            <div class="navbar-nav">
                <a class="nav-link" href="manage.php">Manage Devotions</a>
                <a class="nav-link active" href="subscribers.php">Subscribers</a>
                <a class="nav-link" href="devotions.php">View Devotions</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($message)): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <h3><i class="fas fa-users"></i></h3>
                    <h4><?php echo number_format($total_subscribers); ?></h4>
                    <p>Total Subscribers</p>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="mb-0"><i class="fas fa-users"></i> Subscribers List</h2>
                        <div class="action-buttons">
                            <a href="?export=csv" class="btn btn-success btn-sm">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($subscribers)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Email</th>
                                            <th>Subscribed On</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($subscribers as $index => $subscriber): ?>
                                            <tr>
                                                <td><?= $offset + $index + 1 ?></td>
                                                <td>
                                                    <i class="fas fa-envelope me-2"></i>
                                                    <?= htmlspecialchars($subscriber['email']) ?>
                                                </td>
                                                <td>
                                                    <i class="fas fa-calendar me-2"></i>
                                                    <?= date("F j, Y H:i", strtotime($subscriber['created_at'])) ?>
                                                </td>
                                                <td class="table-actions">
                                                    <a href="mailto:<?= htmlspecialchars($subscriber['email']) ?>" 
                                                       class="btn btn-primary btn-sm" 
                                                       title="Send Email">
                                                        <i class="fas fa-paper-plane"></i>
                                                    </a>
                                                    <a href="?delete_id=<?= $subscriber['id'] ?>" 
                                                       class="btn btn-danger btn-sm" 
                                                       onclick="return confirm('Are you sure you want to delete this subscriber?')"
                                                       title="Delete Subscriber">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <?php if ($total_pages > 1): ?>
                                <nav aria-label="Subscribers pagination">
                                    <ul class="pagination">
                                        <?php if ($page > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
                                            </li>
                                        <?php endif; ?>

                                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                            </li>
                                        <?php endfor; ?>

                                        <?php if ($page < $total_pages): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            <?php endif; ?>

                        <?php else: ?>
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle fa-2x mb-3"></i>
                                <h4>No subscribers found.</h4>
                                <p class="mb-0">Subscribers will appear here once they sign up.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Growth Chart -->
        <?php if (!empty($monthly_data)): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-chart-line"></i> Subscriber Growth</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="growthChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
     <a href="dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>

    <footer class="bg-dark text-light text-center py-3 mt-5">
        <div class="container">
            <p>&copy; 2025 The Anchor Devotional. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php if (!empty($monthly_data)): ?>
    <script>
        const monthlyData = <?php echo json_encode(array_reverse($monthly_data)); ?>;
        
        const ctx = document.getElementById('growthChart').getContext('2d');
        const growthChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthlyData.map(item => {
                    const date = new Date(item.month + '-01');
                    return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
                }),
                datasets: [{
                    label: 'New Subscribers',
                    data: monthlyData.map(item => item.count),
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Monthly Subscriber Growth'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>