<?php
include 'db.php';
error_reporting(E_ALL);
ini_set("display_errors", 1);

if (isset($_GET['reject'])) {
    switch ($_GET['reject']) {
        case 'success':
            echo '<div class="alert alert-success">Testimony rejected successfully.</div>';
            break;
        case 'invalid_csrf':
            echo '<div class="alert alert-danger">Invalid CSRF token. Please try again.</div>';
            break;
        case 'invalid_input':
            echo '<div class="alert alert-danger">Invalid input provided.</div>';
            break;
        case 'database_error':
            echo '<div class="alert alert-danger">Database error occurred. Please try later.</div>';
            break;
        case 'invalid_method':
            echo '<div class="alert alert-danger">Invalid request method.</div>';
            break;
        case 'server_error':
            echo '<div class="alert alert-danger">Server error. Contact admin.</div>';
            break;
    }
}

// Assuming db.php contains the database connection code  
$conn = new mysqli("localhost", "root", "", "prayer_db", 3307);
$result = $conn->query("SELECT * FROM testimonies_pending WHERE status = 'pending' ORDER BY date DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Testimonies - Admin Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h2 {
            font-size: 2.5rem;
            font-weight: 300;
            margin-bottom: 10px;
        }

        .header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .table-container {
            padding: 30px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        th {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #333;
            font-weight: 600;
            padding: 20px 15px;
            text-align: left;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #dee2e6;
        }

        td {
            padding: 20px 15px;
            border-bottom: 1px solid #f1f3f4;
            vertical-align: top;
        }

        tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        .name-cell {
            font-weight: 600;
            color: #2c3e50;
        }

        .initials-cell {
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
            color: white;
            text-align: center;
            font-weight: bold;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }

        .message-cell {
            max-width: 400px;
            line-height: 1.6;
            color: #555;
        }

        .actions-cell {
            white-space: nowrap;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            margin-right: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-approve {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-approve:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        }

        .btn-reject {
            background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }

        .btn-reject:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
        }

        .empty-state {
            text-align: center;
            padding: 60px 30px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .stats {
            background: #f8f9fa;
            padding: 20px 30px;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stats-item {
            text-align: center;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: bold;
            color: #4a90e2;
        }

        .stats-label {
            color: #6c757d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        @media (max-width: 768px) {
            .header h2 {
                font-size: 2rem;
            }

            .table-container {
                padding: 20px;
            }

            th,
            td {
                padding: 15px 10px;
                font-size: 0.9rem;
            }

            .btn {
                padding: 8px 15px;
                font-size: 0.8rem;
                margin-bottom: 5px;
                display: block;
                text-align: center;
            }

            .stats {
                flex-direction: column;
                gap: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Pending Testimonies</h2>
            <p>Review and manage testimony submissions</p>
        </div>

        <div class="table-container">
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Initials</th>
                            <th>Message</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="name-cell"><?= htmlspecialchars($row['name']) ?></td>
                                <td>
                                    <div class="initials-cell"><?= $row['initials'] ?></div>
                                </td>
                                <td class="message-cell"><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                                <td class="actions-cell">
                                    <a href="approve_testimony.php?admin_approve=1&token=<?= $row['approval_token'] ?>"
                                        class="btn btn-approve">Approve</a>
                                    <a href="reject_form.php?token=<?= $row['approval_token'] ?>"
                                        class="btn btn-reject">Reject</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <div style="font-size: 4rem; margin-bottom: 20px; opacity: 0.5;">📝</div>
                    <h3>No Pending Testimonies</h3>
                    <p>All testimonies have been reviewed.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="stats">
            <div class="stats-item">
                <div class="stats-number"><?= $result->num_rows ?></div>
                <div class="stats-label">Pending Reviews</div>
                <div class="stats-label"><a href="dashboard.php">Return To DashBoard</a></div>
            </div>
            <div class="stats-item">
                <div class="stats-number"><?= date('Y-m-d') ?></div>
                <div class="stats-label">Today's Date</div>
            </div>
        </div>
    </div>
</body>

</html>