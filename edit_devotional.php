<?php
session_start();

error_reporting(E_ALL);
ini_set("display_errors", 1);

// DB connection parameters
$host = "localhost";
$port = 3307;
$username = "root";
$password = "";
$database = "prayer_db";

// Create connection
$conn = new mysqli($host, $username, $password, $database, $port);
if ($conn->connect_error) {
    die("Connection failed: {$conn->connect_error}");
}

// Check if ID is provided
if (!isset($_GET['id'])) {
    die("No devotional ID provided.");
}

$id = intval($_GET['id']);

// Fetch the devotional to edit
$stmt = $conn->prepare("SELECT id, topic, date, image_path, pdf_path FROM devotion WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$devotional = $result->fetch_assoc();
$stmt->close();

if (!$devotional) {
    die("Devotional not found.");
}

$error = '';

// Process update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $topic = trim($_POST['topic'] ?? '');
    $date = trim($_POST['date'] ?? '');

    if (empty($topic) || empty($date)) {
        $error = "Topic and Date are required.";
    } else {
        $imagePath = $devotional['image_path']; // default: keep old image

        // Check if image uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $tmpName = $_FILES['image']['tmp_name'];
            $originalName = basename($_FILES['image']['name']);
            // Create a unique file name to avoid conflicts
            $ext = pathinfo($originalName, PATHINFO_EXTENSION);
            $newFileName = uniqid('img_', true) . '.' . $ext;
            $targetPath = $uploadDir . $newFileName;

            if (move_uploaded_file($tmpName, $targetPath)) {
                $imagePath = $targetPath;
                // Optionally: delete old image file if you want
                if (!empty($devotional['image_path']) && file_exists($devotional['image_path'])) {
                    unlink($devotional['image_path']);
                }
            } else {
                $error = "Failed to upload image.";
            }
        }

        if (!$error) {
            $stmt_update = $conn->prepare("UPDATE devotion SET topic = ?, date = ?, image_path = ? WHERE id = ?");
            $stmt_update->bind_param("sssi", $topic, $date, $imagePath, $id);

            if ($stmt_update->execute()) {
                $stmt_update->close();
                $conn->close();
                // Redirect after successful update
                header("Location: dashboard.php?message=updated");
                exit();
            } else {
                $error = "Error updating devotional: {$stmt_update->error}";
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Devotional</title>
    <style>
        /* Reset some default styles */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f4f8;
            margin: 0;
            padding: 2rem;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #222;
            margin-bottom: 1.5rem;
            font-weight: 700;
        }

        form {
            background: #fff;
            max-width: 520px;
            margin: 0 auto;
            padding: 2rem 2.5rem;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        form:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        label {
            display: block;
            margin-bottom: 0.6rem;
            font-weight: 600;
            color: #444;
        }

        input[type="text"],
        input[type="date"],
        input[type="file"] {
            width: 100%;
            padding: 0.65rem 1rem;
            border: 1.8px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.25s ease;
            font-family: inherit;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="file"]:focus {
            border-color: #007BFF;
            outline: none;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.25);
        }

        .error {
            background: #ffebe8;
            color: #d8000c;
            border-left: 5px solid #d8000c;
            padding: 0.8rem 1rem;
            margin-bottom: 1.25rem;
            border-radius: 5px;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .current-files {
            margin-top: 1.3rem;
            font-style: italic;
            color: #666;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .current-files div {
            margin-bottom: 0.4rem;
        }

        .current-files a {
            color: #007BFF;
            text-decoration: none;
            font-weight: 600;
        }

        .current-files a:hover {
            text-decoration: underline;
        }

        button {
            display: inline-block;
            width: 100%;
            padding: 12px 0;
            margin-top: 2rem;
            background-color: #007BFF;
            color: white;
            border: none;
            font-size: 1.1rem;
            font-weight: 700;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.25s ease;
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.4);
        }

        button:hover {
            background-color: #0056b3;
            box-shadow: 0 6px 15px rgba(0, 86, 179, 0.6);
        }

        a.back-link {
            display: block;
            max-width: 520px;
            margin: 1.8rem auto 0 auto;
            text-align: center;
            color: #007BFF;
            font-weight: 600;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        a.back-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 600px) {
            body {
                padding: 1rem;
            }

            form {
                padding: 1.5rem 1.5rem;
            }

            button {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>

    <h1>Edit Devotional</h1>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data" novalidate>
        <label for="topic">Topic:</label>
        <input type="text" id="topic" name="topic" value="<?= htmlspecialchars($devotional['topic']) ?>" required />

        <label for="date">Date:</label>
        <input type="date" id="date" name="date" value="<?= htmlspecialchars($devotional['date']) ?>" required />

        <label for="image">Change Image (optional):</label>
        <input type="file" id="image" name="image" accept="image/*" />

        <div class="current-files">
            <?php if (!empty($devotional['image_path'])): ?>
                <div>Current Image: <a href="<?= htmlspecialchars($devotional['image_path']) ?>" target="_blank">View
                        Image</a></div>
            <?php else: ?>
                <div>No image uploaded.</div>
            <?php endif; ?>

            <?php if (!empty($devotional['pdf_path'])): ?>
                <div>Current PDF: <a href="<?= htmlspecialchars($devotional['pdf_path']) ?>" target="_blank">View PDF</a>
                </div>
            <?php else: ?>
                <div>No PDF uploaded.</div>
            <?php endif; ?>
        </div>

        <button type="submit">Update Devotional</button>
    </form>

    <a href="dashboard.php" class="back-link">← Back to Dashboard</a>

</body>

</html>