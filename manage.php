<?php
// Start session and check authentication
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Verify authentication (added security)


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

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepare and execute delete statement
    $stmt = $conn->prepare("DELETE FROM todayDevotions WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $success = "Devotion deleted successfully!";
    } else {
        $error = "Error deleting devotion: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch all devotions
$stmt = $conn->prepare("SELECT * FROM todayDevotions ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$devotions = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Devotions - The Anchor Devotional</title>
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

        .admin-header {
            background-color: var(--secondary);
            color: white;
        }

        .sidebar {
            background-color: var(--dark);
            min-height: 100vh;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.7);
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .table-responsive {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .table th {
            background-color: var(--secondary);
            color: white;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .action-btns .btn {
            margin-right: 5px;
        }

        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar p-0">
                <div class="d-flex flex-column flex-shrink-0 p-3">
                    <a href="#"
                        class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <span class="fs-4">Admin Panel</span>
                    </a>
                    <hr>
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item">
                            <a href="past_dasborad.php" class="nav-link">
                                <i class="fas fa-plus-circle me-2"></i>
                                Add Past Devotion
                            </a>
                        </li>
                        <li>
                            <a href="manage.php" class="nav-link active">
                                <i class="fas fa-list me-2"></i>
                                Manage Devotions
                            </a>
                        </li>


                    </ul>
                    <hr>
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                            id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-2" style="font-size: 1.5rem;"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">

                            <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 ms-sm-auto px-md-4 py-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Manage Devotions</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="today_dasborad.php" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> Add New
                        </a>
                    </div>
                </div>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Verse Reference</th>
                                <th>Topic</th>
                                <th>Date</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($devotions)): ?>
                              
                            <?php else: ?>
                                <?php foreach ($devotions as $devotion): ?>
                                    <tr>
                                        <td><?= $devotion['id'] ?></td>
                                        <td><?= htmlspecialchars($devotion['verse_reference']) ?></td>
                                        <td><?= htmlspecialchars($devotion['topic'] ?? 'Untitled') ?></td>
                                        <td><?= date('M j, Y', strtotime($devotion['updated_at'])) ?></td>
                                        <td><?= date('M j, Y', strtotime($devotion['created_at'])) ?></td>
                                        <td class="action-btns">
                                            <a href="view.php?id=<?= $devotion['id'] ?>" class="btn btn-sm btn-info"
                                                title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="edit.php?id=<?= $devotion['id'] ?>" class="btn btn-sm btn-warning"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="manage.php?action=delete&id=<?= $devotion['id'] ?>"
                                                class="btn btn-sm btn-danger" title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this devotion?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mt-4">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Confirm before deleting
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                if (!confirm('Are you sure you want to delete this devotion?')) {
                    e.preventDefault();
                }
            });
        });

        // Make table rows clickable
        document.querySelectorAll('tbody tr').forEach(row => {
            row.addEventListener('click', (e) => {
                // Don't navigate if they clicked on an action button
                if (!e.target.closest('.action-btns')) {
                    window.location = `edit.php?id=${row.cells[0].textContent}`;
                }
            });

            // Add hover effect
            row.style.cursor = 'pointer';
            row.addEventListener('mouseenter', () => {
                row.style.backgroundColor = '#f8f9fa';
            });
            row.addEventListener('mouseleave', () => {
                row.style.backgroundColor = '';
            });
        });
    </script>
</body>

</html>

<?php
// Close database connection
$conn->close();
?>