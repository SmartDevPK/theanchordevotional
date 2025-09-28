<?php
// Start session and security checks at the very top
session_start();

// 1. Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// 2. Verify IP address (optional security measure)
if ($_SESSION['user_ip'] !== $_SERVER['REMOTE_ADDR']) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// 3. Check session timeout (10 minutes)
if (time() - $_SESSION['last_activity'] > 600) {
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Include database connection after security checks
require 'db.php';

// Initialize messages
$errorMessage = '';
$successMessage = '';

// Fetch all dashboard data
try {
    // 1. Get counts for all tables
    $counts = [];
    $tables = ['devotion', 'devotions', 'prayer_requests', 'testimonies', 'subscribers', 'comments'];

    foreach ($tables as $table) {
        $query = $mysqli->prepare("SELECT COUNT(*) AS total FROM $table");
        $query->execute();
        $result = $query->get_result();
        $row = $result->fetch_assoc();
        $counts[$table] = $row['total'] ?? 0;
        $query->close();
    }

    // 2. Fetch devotion data
    $devotion = [];
    $devotionQuery = $mysqli->prepare("SELECT * FROM devotion ORDER BY date LIMIT 5");
    $devotionQuery->execute();
    $devotionResult = $devotionQuery->get_result();
    while ($row = $devotionResult->fetch_assoc()) {
        $devotion[] = $row;
    }
    $devotionQuery->close();

    // 3. Fetch devotions with pagination
    $limit = 10;
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $count_sql = $mysqli->prepare("SELECT COUNT(*) as total FROM devotions");
    $count_sql->execute();
    $total_rows = $count_sql->get_result()->fetch_assoc()['total'];
    $total_pages = ceil($total_rows / $limit);
    $count_sql->close();

    $devotions = [];
    $sql = $mysqli->prepare("SELECT id, title, devotion_date, image, excerpt FROM devotions ORDER BY devotion_date DESC LIMIT ? OFFSET ?");
    $sql->bind_param("ii", $limit, $offset);
    $sql->execute();
    $result = $sql->get_result();
    while ($row = $result->fetch_assoc()) {
        $devotions[] = $row;
    }
    $sql->close();

    // 4. Fetch prayer requests
    $prayerRequests = [];
    $prayerQuery = $mysqli->prepare("SELECT name, email, prayer AS title, request_date AS created_at FROM prayer_requests ORDER BY created_at DESC LIMIT 20");
    $prayerQuery->execute();
    $prayerResult = $prayerQuery->get_result();
    while ($row = $prayerResult->fetch_assoc()) {
        $prayerRequests[] = $row;
    }
    $prayerQuery->close();

    // 5. Fetch testimonies
    $testimonies = [];
    $testimonyQuery = $mysqli->prepare("SELECT * FROM testimonies ORDER BY date DESC LIMIT 20");
    $testimonyQuery->execute();
    $testimonyResult = $testimonyQuery->get_result();
    while ($row = $testimonyResult->fetch_assoc()) {
        $testimonies[] = $row;
    }
    $testimonyQuery->close();

    // 6. Fetch subscribers
    $subscribers = [];
    $subscriberQuery = $mysqli->prepare("SELECT * FROM subscribers ORDER BY subscribed_at DESC LIMIT 20");
    $subscriberQuery->execute();
    $subscriberResult = $subscriberQuery->get_result();
    while ($row = $subscriberResult->fetch_assoc()) {
        $subscribers[] = $row;
    }
    $subscriberQuery->close();

    // 7. Fetch comments
    $comments = [];
    $commentQuery = $mysqli->prepare("SELECT * FROM comments ORDER BY created_at DESC LIMIT 50");
    $commentQuery->execute();
    $commentResult = $commentQuery->get_result();
    while ($row = $commentResult->fetch_assoc()) {
        $comments[] = $row;
    }
    $commentQuery->close();

} catch (Exception $e) {
    $errorMessage = "Database error: " . htmlspecialchars($e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Anchor Devotional - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #ad3128;
            --secondary: #2c3e50;
            --light: #f8f9fa;
            --dark: #212529;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #f5f5f5;
        }

        .sidebar {
            min-height: 100vh;
            background-color: var(--dark);
            color: white;
            width: 250px;
            position: fixed;
            transition: all 0.3s;
        }

        .sidebar-header {
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.2);
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
        }

        .sidebar-menu li a {
            padding: 12px 20px;
            display: block;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .main-content {
            margin-left: 250px;
            transition: all 0.3s;
            padding: 20px;
        }

        .dashboard-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .card-header {
            padding: 15px 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-card {
            text-align: center;
            padding: 20px;
        }

        .stat-card i {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .stat-card h2 {
            font-size: 2rem;
            margin: 10px 0;
        }

        .page-content {
            display: none;
        }

        .page-content.active {
            display: block;
        }

        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }

            .sidebar.active {
                margin-left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .main-content.active {
                margin-left: 250px;
            }
        }

        .badge {
            font-weight: 500;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>The Anchor</h3>
            <small>Admin Dashboard</small>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="#" class="active" data-page="dashboard">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="#" data-page="devotionals">
                    <i class="fas fa-book-open"></i> Devotionals
                    <span class="badge bg-primary float-end">
                        <?= $counts['devotions'] ?? 0 ?>
                    </span>
                </a>
            </li>
            <li>
                <a href="#" data-page="prayer-requests">
                    <i class="fas fa-pray"></i> Prayer Requests
                    <span class="badge bg-danger float-end">
                        <?= $counts['prayer_requests'] ?? 0 ?>
                    </span>
                </a>
            </li>
            <li>
                <a href="#" data-page="testimonies">
                    <i class="fas fa-comment-alt"></i> Testimonies
                    <span class="badge bg-success float-end">
                        <?= $counts['testimonies'] ?? 0 ?>
                    </span>
                </a>
            </li>

            <li>
                <a href="admin_dashboard.php">
                    <i class="fas fa-comment-alt"></i> Approve Testimonies

                </a>
            </li>

            <li>
                <a href="#" data-page="comments">
                    <i class="fas fa-comments"></i> Comments
                    <span class="badge bg-info float-end">
                        <?= $counts['comments'] ?? 0 ?>
                    </span>
                </a>
            </li>

            <li>
                <a href="#" data-page="subscribers">
                    <i class="fas fa-users"></i> Subscribers
                    <span class="badge bg-success float-end">
                        <?= $counts['subscribers'] ?? 0 ?>
                    </span>
                </a>
            </li>
            <li>
                <a href="Today_Devotion.php">
                    <i class="fas fa-sign-out-alt"></i> Today Devotion
                </a>
            </li>
            <li>
                <a href="today_dasborad.php">
                    <i class="fas fa-sign-out-alt"></i> Past Devotionals
                </a>
            </li>
            <li>
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <div class="container-fluid">
                <button class="btn btn-outline-secondary d-md-none" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </nav>

        <!-- Dashboard Page -->
        <div class="page-content active" id="dashboard-page">
            <div class="container-fluid">
                <h4 class="mb-4">Dashboard Overview</h4>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="dashboard-card stat-card">
                            <i class="fas fa-book-open text-primary"></i>
                            <h2><?= $counts['devotions'] ?? 0 ?></h2>
                            <p>Devotionals</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="dashboard-card stat-card">
                            <i class="fas fa-pray text-danger"></i>
                            <h2><?= $counts['prayer_requests'] ?? 0 ?></h2>
                            <p>Prayer Requests</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="dashboard-card stat-card">
                            <i class="fas fa-comment-alt text-warning"></i>
                            <h2><?= $counts['testimonies'] ?? 0 ?></h2>
                            <p>Testimonies</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="dashboard-card stat-card">
                            <i class="fas fa-users text-success"></i>
                            <h2><?= $counts['subscribers'] ?? 0 ?></h2>
                            <p>Subscribers</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Devotionals -->
                <div class="dashboard-card mb-4">
                    <div class="card-header">
                        <span>Recent Devotionals</span>
                        <a href="AddDevotion.php" class="btn btn-sm btn-primary">Add New</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Cover</th>
                                        <th>Title</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($devotion)): ?>
                                        <?php foreach ($devotion as $dev): ?>
                                            <tr>
                                                <td>
                                                    <img src="<?= htmlspecialchars($dev['image_path'] ?? '') ?>"
                                                        style="width:50px; height:50px; object-fit:cover;" alt="Cover">
                                                </td>
                                                <td><?= htmlspecialchars($dev['topic'] ?? '') ?></td>
                                                <td><?= date("F j, Y", strtotime($dev['date'])) ?></td>
                                                <td>
                                                    <a href="edit_devotional.php?id=<?= $dev['id'] ?>"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" action="delete_devotion.php" style="display:inline;">
                                                        <input type="hidden" name="id"
                                                            value="<?= htmlspecialchars($dev['id']) ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Are you sure you want to delete this devotional?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-4">No devotionals found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Devotionals Page -->
        <div class="page-content" id="devotionals-page">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Manage Devotionals</h4>
                    <a href="add_devotional.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New
                    </a>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <span>All Devotionals</span>
                        <div class="input-group" style="width: 300px;">
                            <input type="text" class="form-control" placeholder="Search...">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Cover</th>
                                        <th>Title</th>
                                        <th>Date</th>
                                        <th>Excerpt</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($devotions)): ?>
                                        <?php foreach ($devotions as $row): ?>
                                            <tr>
                                                <td>
                                                    <img src="<?= htmlspecialchars($row['image'] ?? 'default-image.jpg') ?>"
                                                        style="width:50px; height:50px; object-fit:cover;" alt="Cover">
                                                </td>
                                                <td><?= htmlspecialchars($row['title'] ?? '') ?></td>
                                                <td><?= date("F j, Y", strtotime($row['devotion_date'])) ?></td>
                                                <td><?= htmlspecialchars($row['excerpt'] ?? '') ?></td>
                                                <td>
                                                    <a href="Edevotional.php?id=<?= $row['id'] ?>"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" action="delete_devotional.php" style="display:inline;">
                                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Are you sure?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4">No devotionals found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <nav aria-label="Page navigation" class="mt-3">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
                                </li>
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prayer Requests Page -->
        <div class="page-content" id="prayer-requests-page">
            <div class="container-fluid">
                <h4 class="mb-4">Prayer Requests</h4>
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['success_message'] ?></div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>
                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error_message'] ?></div>
                    <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>
                <div class="dashboard-card">
                    <div class="card-header">
                        <span>All Prayer Requests</span>
                        <div class="input-group" style="width: 300px;">
                            <input type="text" class="form-control" placeholder="Search..." id="prayerSearch">
                            <button class="btn btn-outline-secondary" type="button" id="searchPrayer">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="prayerTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Request</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($prayerRequests)): ?>
                                        <?php foreach ($prayerRequests as $request): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($request['name']) ?></td>
                                                <td><?= htmlspecialchars($request['email']) ?></td>
                                                <td><?= htmlspecialchars($request['title']) ?></td>
                                                <td><?= date('M j, Y g:i a', strtotime($request['created_at'])) ?></td>
                                                <td>
                                                    <form action="delete_prayer_request.php" method="POST"
                                                        style="display:inline;">
                                                        <input type="hidden" name="id"
                                                            value="<?= isset($request['id']) ? (int) $request['id'] : '' ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Are you sure you want to delete this prayer request?')">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4">No prayer requests found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Testimonies Page -->
        <div class="page-content" id="testimonies-page">
            <div class="container-fluid">
                <h4 class="mb-4">Testimonies</h4>
                <div class="dashboard-card">
                    <div class="card-header">
                        <span>All Testimonies</span>
                        <div class="input-group" style="width: 300px;">
                            <input type="text" class="form-control" placeholder="Search..." id="testimonySearch">
                            <button class="btn btn-outline-secondary" type="button" id="searchTestimony">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="testimonyTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Initials</th>
                                        <th>Testimony</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($testimonies)): ?>
                                        <?php foreach ($testimonies as $testimony): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($testimony['name']) ?></td>
                                                <td><?= htmlspecialchars($testimony['initials']) ?></td>
                                                <td><?= htmlspecialchars($testimony['message']) ?></td>
                                                <td><?= date('M j, Y g:i a', strtotime($testimony['date'])) ?></td>
                                                <td>
                                                    <?php $statusClass = [
                                                        'pending' => 'bg-warning',
                                                        'approved' => 'bg-success',
                                                        'rejected' => 'bg-danger'
                                                    ][$testimony['status']] ?? 'bg-secondary'; ?>
                                                    <span class="badge <?= $statusClass ?>">
                                                        <?= ucfirst($testimony['status']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($testimony['status'] === 'pending'): ?>
                                                        <form action="approve_testimony.php" method="POST" style="display:inline;">
                                                            <input type="hidden" name="id" value="<?= $testimony['id'] ?>">
                                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                    <form action="delete_testimony.php" method="GET" style="display:inline;">
                                                        <input type="hidden" name="id" value="<?= $testimony['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Are you sure?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-4">No testimonies found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Comments Page -->
        <div class="page-content" id="comments-page">
            <div class="container-fluid">
                <h4 class="mb-4">Manage Comments</h4>
                <div class="dashboard-card">
                    <div class="card-header">
                        <span>All Comments</span>
                        <div class="input-group" style="width: 300px;">
                            <input type="text" class="form-control" placeholder="Search..." id="commentSearch">
                            <button class="btn btn-outline-secondary" type="button" id="searchComment">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="commentTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Comment</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>



                                    <?php if (!empty($comments)): ?>
                                        <?php foreach ($comments as $comment): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($comment['id']) ?></td>
                                                <td><?= htmlspecialchars($comment['name']) ?></td>
                                                <td><?= htmlspecialchars($comment['comment']) ?></td>
                                                <td><?= date('M j, Y g:i a', strtotime($comment['created_at'])) ?></td>
                                                <td>
                                                    <form action="delete_comment.php" method="POST" style="display:inline;">
                                                        <input type="hidden" name="id" value="<?= $comment['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Are you sure you want to delete this comment?')">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4">No comments found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscribers Page -->
        <div class="page-content" id="subscribers-page">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Subscribers</h4>
                    <button class="btn btn-primary">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
                <div class="dashboard-card">
                    <div class="card-header">
                        <span>All Subscribers</span>
                        <div class="input-group" style="width: 300px;">
                            <input type="text" class="form-control" placeholder="Search..." id="subscriberSearch">
                            <button class="btn btn-outline-secondary" type="button" id="searchSubscriber">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="subscriberTable">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($subscribers)): ?>
                                        <?php foreach ($subscribers as $subscriber): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($subscriber['email']) ?></td>
                                                <td><?= date('M j, Y', strtotime($subscriber['subscribed_at'])) ?></td>
                                                <td>
                                                    <span
                                                        class="badge <?= $subscriber['status'] === 'Active' ? 'bg-success' : 'bg-secondary' ?>">
                                                        <?= htmlspecialchars($subscriber['status']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="mailto:<?= htmlspecialchars($subscriber['email']) ?>"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-envelope"></i>
                                                    </a>
                                                    <form action="delete_subscriber.php" method="POST" style="display:inline;">
                                                        <input type="hidden" name="id" value="<?= $subscriber['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Are you sure?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-4">No subscribers found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sidebar toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');

            sidebarToggle.addEventListener('click', function () {
                sidebar.classList.toggle('active');
                mainContent.classList.toggle('active');
            });

            // Page navigation
            const menuLinks = document.querySelectorAll('.sidebar-menu a[data-page]');
            menuLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();

                    // Remove active class from all links
                    menuLinks.forEach(item => item.classList.remove('active'));

                    // Add active to clicked link
                    this.classList.add('active');

                    // Hide all pages
                    document.querySelectorAll('.page-content').forEach(page => {
                        page.classList.remove('active');
                    });

                    // Show selected page
                    const pageId = this.getAttribute('data-page') + '-page';
                    document.getElementById(pageId).classList.add('active');
                });
            });

            // Simple search functionality
            function setupSearch(inputId, tableId) {
                const searchInput = document.getElementById(inputId);
                const table = document.getElementById(tableId);

                if (searchInput && table) {
                    searchInput.addEventListener('input', function () {
                        const searchTerm = this.value.toLowerCase();
                        const rows = table.querySelectorAll('tbody tr');

                        rows.forEach(row => {
                            const text = row.textContent.toLowerCase();
                            row.style.display = text.includes(searchTerm) ? '' : 'none';
                        });
                    });
                }
            }

            // Initialize search for all tables
            setupSearch('prayerSearch', 'prayerTable');
            setupSearch('testimonySearch', 'testimonyTable');
            setupSearch('subscriberSearch', 'subscriberTable');
        });

        setupSearch('commentSearch', 'commentTable');
    </script>
</body>

</html>