<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Database connection
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

// Handle prayer request deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM prayer_requests WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        $message = "Prayer request deleted successfully.";
    } else {
        $message = "Error deleting prayer request: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch prayer requests
$sql = "SELECT * FROM prayer_requests ORDER BY created_at DESC";
$result = $conn->query($sql);

// Get statistics
$stats_sql = "SELECT 
    COUNT(*) as total_requests,
    COUNT(CASE WHEN sharePublicly = 1 THEN 1 END) as public_requests,
    COUNT(CASE WHEN sharePublicly = 0 THEN 1 END) as private_requests
    FROM prayer_requests";
$stats_result = $conn->query($stats_sql);
$stats = $stats_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prayer Requests - The Anchor Devotional</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #ad3128;
            --secondary: #2c3e50;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 80px auto 40px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
            background: var(--light);
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border-left: 4px solid var(--primary);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .stat-label {
            color: var(--dark);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .content {
            padding: 30px;
        }

        .prayer-requests-grid {
            display: grid;
            gap: 25px;
        }

        .prayer-request {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
        }

        .prayer-request:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .prayer-request.public {
            border-left: 4px solid var(--success);
        }

        .prayer-request.private {
            border-left: 4px solid var(--warning);
        }

        .request-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .request-meta {
            display: flex;
            gap: 15px;
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .request-meta i {
            margin-right: 5px;
        }

        .prayer-request h3 {
            color: var(--secondary);
            margin-bottom: 10px;
            font-size: 1.3rem;
        }

        .prayer-request p {
            color: #444;
            line-height: 1.7;
            margin-bottom: 15px;
        }

        .prayer-text {
            background: var(--light);
            padding: 20px;
            border-radius: 8px;
            font-style: italic;
            border-left: 3px solid var(--primary);
            margin: 15px 0;
        }

        .request-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: var(--secondary);
            color: white;
        }

        .btn-secondary:hover {
            background: #1a252f;
            transform: translateY(-2px);
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: #8a2720;
            transform: translateY(-2px);
        }

        .privacy-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .public-badge {
            background: #d4edda;
            color: var(--success);
        }

        .private-badge {
            background: #fff3cd;
            color: var(--warning);
        }

        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .no-data i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #dee2e6;
        }

        .no-data h3 {
            color: #6c757d;
            margin-bottom: 10px;
        }

        .navigation {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
            padding: 20px;
            background: var(--light);
            border-top: 1px solid #dee2e6;
        }

        .message {
            padding: 15px 20px;
            margin: 20px;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .container {
                margin: 60px 10px 20px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .request-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .request-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav style="position: fixed; top: 0; left: 0; width: 100%; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 1000; padding: 15px 0;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center;">
            <a href="index.php" style="font-size: 1.5rem; font-weight: bold; color: var(--primary); text-decoration: none;">
                <i class="fas fa-anchor"></i> The Anchor
            </a>
            <div style="display: flex; gap: 20px;">
                <a href="dashboard.php" style="color: var(--dark); text-decoration: none; font-weight: 500;">Dashboard</a>
                <a href="devotions.php" style="color: var(--dark); text-decoration: none; font-weight: 500;">Devotions</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-hands-praying"></i> Prayer Requests</h1>
            <p>View and manage prayer requests from your community</p>
        </div>

        <?php if (isset($message)): ?>
            <div class="message <?php echo strpos($message, 'Error') === false ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total_requests']; ?></div>
                <div class="stat-label">Total Requests</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['public_requests']; ?></div>
                <div class="stat-label">Public Requests</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['private_requests']; ?></div>
                <div class="stat-label">Private Requests</div>
            </div>
        </div>

        <!-- Prayer Requests Content -->
        <div class="content">
            <?php if ($result->num_rows > 0): ?>
                <div class="prayer-requests-grid">
                    <?php while ($row = $result->fetch_assoc()): 
                        $isPublic = isset($row['sharePublicly']) && $row['sharePublicly'];
                        $created_at = isset($row['created_at']) ? date("F j, Y g:i A", strtotime($row['created_at'])) : 'Unknown date';
                    ?>
                        <div class="prayer-request <?php echo $isPublic ? 'public' : 'private'; ?>">
                            <div class="request-header">
                                <h3>
                                    <i class="fas fa-user"></i>
                                    <?php echo htmlspecialchars($row['name']); ?>
                                </h3>
                                <span class="privacy-badge <?php echo $isPublic ? 'public-badge' : 'private-badge'; ?>">
                                    <i class="fas <?php echo $isPublic ? 'fa-eye' : 'fa-eye-slash'; ?>"></i>
                                    <?php echo $isPublic ? 'Public' : 'Private'; ?>
                                </span>
                            </div>

                            <div class="request-meta">
                                <span>
                                    <i class="fas fa-envelope"></i>
                                    <?php echo htmlspecialchars($row['email']); ?>
                                </span>
                                <span>
                                    <i class="fas fa-calendar"></i>
                                    <?php echo $created_at; ?>
                                </span>
                            </div>

                            <div class="prayer-text">
                                <i class="fas fa-quote-left" style="opacity: 0.5; margin-right: 10px;"></i>
                                <?php echo nl2br(htmlspecialchars($row['prayer'])); ?>
                                <i class="fas fa-quote-right" style="opacity: 0.5; margin-left: 10px;"></i>
                            </div>

                            <div class="request-actions">
                                <form method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this prayer request?');">
                                    <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($row['id']); ?>" />
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Delete Request
                                    </button>
                                </form>
                                <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="btn btn-primary">
                                    <i class="fas fa-reply"></i> Respond
                                </a>
                                <button class="btn btn-secondary" onclick="prayForRequest('<?php echo htmlspecialchars($row['name']); ?>')">
                                    <i class="fas fa-pray"></i> Pray Now
                                </button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-praying-hands"></i>
                    <h3>No Prayer Requests Yet</h3>
                    <p>When people submit prayer requests, they will appear here.</p>
                    <a href="dashboard.php" class="btn btn-primary" style="margin-top: 20px;">
                        <i class="fas fa-arrow-left"></i> Return to Dashboard
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Navigation -->
        <?php if ($result->num_rows > 0): ?>
        <div class="navigation">
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <a href="devotions.php" class="btn btn-primary">
                <i class="fas fa-book-open"></i> View Devotions
            </a>
        </div>
        <?php endif; ?>
    </div>

    <script>
        function prayForRequest(name) {
            alert('Praying for ' + name + '... 🙏\nMay God hear our prayers and provide comfort, guidance, and blessings.');
        }

        // Add some interactive features
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation to prayer requests when they come into view
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            });

            document.querySelectorAll('.prayer-request').forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = `all 0.6s ease ${index * 0.1}s`;
                observer.observe(card);
            });
        });
    </script>
</body>
</html>

<?php
// Close connection
$conn->close();
?>