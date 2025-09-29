<?php
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

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle delete action
    if (isset($_POST['delete_id'])) {
        $delete_id = (int) $_POST['delete_id'];
        $sql = "DELETE FROM today_Devotion WHERE id = $delete_id";
        if ($conn->query($sql)) {
            $_SESSION['message'] = "Devotional deleted successfully!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $error = "Error deleting devotional: " . $conn->error;
        }
    }
    // Handle add/edit action
    else {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $title = $conn->real_escape_string($_POST['title'] ?? '');
        $verse = $conn->real_escape_string($_POST['verse'] ?? '');
        $content = $conn->real_escape_string($_POST['content'] ?? '');

        if ($id > 0) {
            // Update existing devotional
            $sql = "UPDATE today_Devotion SET title='$title', verse='$verse', content='$content' WHERE id=$id";
            $success_msg = "Devotional updated successfully!";
        } else {
            // Add new devotional
            $sql = "INSERT INTO today_Devotion (title, verse, content) VALUES ('$title', '$verse', '$content')";
            $success_msg = "Devotional added successfully!";
        }

        if ($conn->query($sql)) {
            $_SESSION['message'] = $success_msg;
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}

// Get devotion for editing if ID is provided
$edit_id = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
$edit_devotion = null;
if ($edit_id > 0) {
    $result = $conn->query("SELECT * FROM today_Devotion WHERE id = $edit_id");
    if ($result->num_rows > 0) {
        $edit_devotion = $result->fetch_assoc();
    }
}

// Get all devotions for listing
$devotions = $conn->query("SELECT * FROM today_Devotion ORDER BY created_at DESC");

// Get the latest devotion for preview
$result = $conn->query("SELECT * FROM today_Devotion ORDER BY created_at DESC LIMIT 1");
if ($result->num_rows > 0) {
    $latest_devotion = $result->fetch_assoc();
} else {
    $latest_devotion = [
        'title' => 'No Devotional Found',
        'verse' => '',
        'content' => 'Please add a devotional.'
    ];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devotional Management</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Open+Sans:wght@400;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #4a6fa5;
            --secondary: #166088;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f5f7fa;
            color: var(--dark);
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: white;
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        h1,
        h2,
        h3 {
            font-family: 'Merriweather', serif;
            color: var(--secondary);
        }

        h2 {
            margin-bottom: 30px;
            text-align: center;
            font-size: 2rem;
        }

        .alert {
            padding: 15px;
            margin-bottom: 25px;
            border-radius: var(--border-radius);
            font-size: 16px;
            text-align: center;
        }

        .alert-success {
            background-color: #e6f7ee;
            color: var(--success);
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #fce8e8;
            color: var(--danger);
            border: 1px solid #f5c6cb;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--secondary);
            font-size: 1.1rem;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-family: 'Open Sans', sans-serif;
            font-size: 16px;
            transition: var(--transition);
        }

        input[type="text"]:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.2);
        }

        textarea {
            min-height: 150px;
            resize: vertical;
            line-height: 1.8;
        }

        .btn {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            display: inline-block;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
        }

        .btn:hover {
            background-color: var(--secondary);
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: var(--danger);
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-warning {
            background-color: var(--warning);
            color: var(--dark);
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .btn-sm {
            padding: 8px 15px;
            font-size: 14px;
        }

        .preview-section {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid #eee;
        }

        .preview-title {
            color: var(--secondary);
            margin-bottom: 15px;
            font-size: 1.5rem;
        }

        .preview-verse {
            font-style: italic;
            color: #555;
            margin-bottom: 20px;
            font-size: 1.1rem;
            padding-left: 15px;
            border-left: 3px solid var(--primary);
        }

        .preview-content {
            line-height: 1.8;
            font-size: 1.05rem;
            white-space: pre-line;
        }

        .devotion-list {
            margin-top: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: var(--secondary);
            color: white;
            font-weight: 600;
        }

        tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 25px;
                margin: 20px auto;
            }

            h2 {
                font-size: 1.8rem;
            }

            .actions {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2><?= $edit_devotion ? 'Edit' : 'Add' ?> Devotional</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?= $_SESSION['message'] ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <?php if ($edit_devotion): ?>
                <input type="hidden" name="id" value="<?= $edit_devotion['id'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title"
                    value="<?= $edit_devotion ? htmlspecialchars($edit_devotion['title']) : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="verse">Bible Verse</label>
                <textarea id="verse" name="verse"
                    required><?= $edit_devotion ? htmlspecialchars($edit_devotion['verse']) : '' ?></textarea>
            </div>

            <div class="form-group">
                <label for="content">Devotional Content</label>
                <textarea id="content" name="content"
                    required><?= $edit_devotion ? htmlspecialchars($edit_devotion['content']) : '' ?></textarea>
            </div>

            <button type="submit" class="btn"><?= $edit_devotion ? 'Update' : 'Save' ?> Devotional</button>
            <?php if ($edit_devotion): ?>
                <a href="?" class="btn btn-warning">Cancel</a>
            <?php endif; ?>
        </form>

        <div class="preview-section">
            <h3 class="preview-title">Latest Devotional Preview</h3>
            <h4><?= htmlspecialchars($latest_devotion['title']) ?></h4>
            <p class="preview-verse"><?= htmlspecialchars($latest_devotion['verse']) ?></p>
            <div class="preview-content"><?= nl2br(htmlspecialchars($latest_devotion['content'])) ?></div>
        </div>

        <div class="devotion-list">
            <h3>All Devotionals</h3>
            <?php if ($devotions->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Verse</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $devotions->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['title']) ?></td>
                                <td><?= htmlspecialchars(substr($row['verse'], 0, 50)) . (strlen($row['verse']) > 50 ? '...' : '') ?>
                                </td>
                                <td><?= date('M j, Y', strtotime($row['created_at'])) ?></td>
                                <td class="actions">
                                    <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form method="POST" action="" style="display:inline;">
                                        <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this devotional?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No devotionals found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Confirm before deleting
        document.querySelectorAll('form[method="POST"]').forEach(form => {
            form.addEventListener('submit', function (e) {
                if (this.querySelector('input[name="delete_id"]')) {
                    if (!confirm('Are you sure you want to delete this devotional?')) {
                        e.preventDefault();
                    }
                }
            });
        });
    </script>
</body>

</html>

<?php
$conn->close();
?>