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

$conn = new mysqli($host, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Default section headings
$default_headings = [
    'understanding_heat' => "Understanding the Nature of Heat",
    'purpose_of_heat' => "The Purpose of Heat in Our Lives",
    'biblical_examples' => "Biblical Examples of Surviving Heat",
    'practical_steps' => "Practical Steps for Surviving Heat",
    'promise_of_fruitfulness' => "The Promise of Fruitfulness"
];

// Handle form submission for update
$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $verse_text = $conn->real_escape_string($_POST['verse_text'] ?? '');
    $verse_reference = $conn->real_escape_string($_POST['verse_reference'] ?? '');
    $introduction_text = $conn->real_escape_string($_POST['introduction_text'] ?? '');

    // Prepare sections as JSON
    $sections = [];
    foreach ($default_headings as $key => $default_heading) {
        $heading = $conn->real_escape_string($_POST[$key . '_heading'] ?? $default_heading);
        $content = $conn->real_escape_string($_POST[$key] ?? '');
        $sections[$key] = ['heading' => $heading, 'content' => $content];
    }
    $sections_json = json_encode($sections);

    // Update database
    $stmt = $conn->prepare("UPDATE todayDevotions SET verse_text=?, verse_reference=?, introduction_text=?, sections=?, updated_at=NOW() WHERE id=?");
    $stmt->bind_param("ssssi", $verse_text, $verse_reference, $introduction_text, $sections_json, $id);

    if ($stmt->execute()) {
        $success = "Devotion updated successfully!";
    } else {
        $error = "Error updating devotion: " . $stmt->error;
    }
    $stmt->close();
}

// Get devotion ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch devotion data
$stmt = $conn->prepare("SELECT * FROM todayDevotions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$devotion = $result->fetch_assoc();
$stmt->close();

// Decode JSON sections
$sections = $devotion ? json_decode($devotion['sections'], true) : [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Devotion - The Anchor Devotional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Same styles as your admin.php */
        :root {
            --primary: #ad3128;
            --secondary: #2c3e50;
            --light: #f8f9fa;
            --dark: #212529;
            --text: #333;
            --text-light: #6c757d;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #f5f5f5;
        }

        .form-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 5px;
            background: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .section-header input {
            flex-grow: 1;
            margin-left: 10px;
            font-weight: bold;
            font-size: 1.2rem;
            border: none;
            border-bottom: 2px solid #eee;
            padding: 5px 0;
        }

        .section-header input:focus {
            outline: none;
            border-color: var(--primary);
        }

        .section-icon {
            font-size: 1.5rem;
            color: var(--primary);
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Edit Devotion</h1>
            <a href="manage.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($devotion): ?>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $devotion['id'] ?>">

                <!-- Verse Section -->
                <div class="form-section">
                    <h4><i class="fas fa-bible me-2 section-icon"></i>Verse Section</h4>
                    <div class="mb-3">
                        <label for="verse_text" class="form-label">Verse Text</label>
                        <textarea class="form-control" id="verse_text" name="verse_text" rows="5" required><?=
                            htmlspecialchars($devotion['verse_text'])
                            ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="verse_reference" class="form-label">Verse Reference</label>
                        <input type="text" class="form-control" id="verse_reference" name="verse_reference"
                            value="<?= htmlspecialchars($devotion['verse_reference']) ?>" required>
                    </div>
                </div>

                <!-- Introduction -->
                <div class="form-section">
                    <h4><i class="fas fa-paragraph me-2 section-icon"></i>Introduction</h4>
                    <div class="mb-3">
                        <textarea class="form-control" id="introduction_text" name="introduction_text" rows="8" required><?=
                            htmlspecialchars($devotion['introduction_text'])
                            ?></textarea>
                    </div>
                </div>

                <!-- Dynamic Sections -->
                <?php foreach ($default_headings as $key => $default_heading): ?>
                    <?php $section = $sections[$key] ?? ['heading' => $default_heading, 'content' => '']; ?>
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas 
                                <?= $key === 'understanding_heat' ? 'fa-fire' : '' ?>
                                <?= $key === 'purpose_of_heat' ? 'fa-hammer' : '' ?>
                                <?= $key === 'biblical_examples' ? 'fa-book-bible' : '' ?>
                                <?= $key === 'practical_steps' ? 'fa-footsteps' : '' ?>
                                <?= $key === 'promise_of_fruitfulness' ? 'fa-apple-alt' : '' ?>
                                me-2 section-icon"></i>
                            <input type="text" class="form-control" id="<?= $key ?>_heading" name="<?= $key ?>_heading"
                                value="<?= htmlspecialchars($section['heading']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" id="<?= $key ?>" name="<?= $key ?>" rows="8" required><?=
                                    htmlspecialchars($section['content'])
                                    ?></textarea>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Submit Buttons -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <button type="reset" class="btn btn-secondary me-md-2">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Devotion
                    </button>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-danger">
                Devotion not found. <a href="manage.php">Return to list</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tab key support for textareas
        document.querySelectorAll('textarea').forEach(textarea => {
            textarea.addEventListener('keydown', function (e) {
                if (e.key === 'Tab') {
                    e.preventDefault();
                    const start = this.selectionStart;
                    const end = this.selectionEnd;
                    this.value = this.value.substring(0, start) + "\t" + this.value.substring(end);
                    this.selectionStart = this.selectionEnd = start + 1;
                }
            });
        });
    </script>
</body>

</html>

<?php $conn->close(); ?>