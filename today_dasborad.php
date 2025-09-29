<?php
// Start session and authentication check

error_reporting(E_ALL);
ini_set("display_errors", 1);
// Database configuration
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

// Default section headings
$default_headings = [
    'understanding_heat' => "Understanding the Nature of Heat",
    'purpose_of_heat' => "The Purpose of Heat in Our Lives",
    'biblical_examples' => "Biblical Examples of Surviving Heat",
    'practical_steps' => "Practical Steps for Surviving Heat",
    'promise_of_fruitfulness' => "The Promise of Fruitfulness"
];

// Handle form submission
$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Use null coalescing to allow empty strings if not set
    $verse_text = $conn->real_escape_string($_POST['verse_text'] ?? '');
    $verse_reference = $conn->real_escape_string($_POST['verse_reference'] ?? '');
    $introduction_text = $conn->real_escape_string($_POST['introduction_text'] ?? '');

    // Prepare sections as JSON including both headings and content
    $sections = [];
    foreach ($default_headings as $key => $default_heading) {
        $heading = $conn->real_escape_string($_POST[$key . '_heading'] ?? $default_heading);
        $content = $conn->real_escape_string($_POST[$key] ?? '');

        $sections[$key] = [
            'heading' => $heading,
            'content' => $content
        ];
    }

    $sections_json = json_encode($sections);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO devotional (verse_text, verse_reference, introduction_text, sections) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $verse_text, $verse_reference, $introduction_text, $sections_json);

    if ($stmt->execute()) {
        $success = "Devotion content added successfully!";
        // Clear form on successful submission
        $_POST = [];
    } else {
        $error = "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devotion Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
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

        .sidebar {
            min-height: 100vh;
            background-color: var(--dark);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.7);
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .content-area {
            padding: 20px;
        }

        .form-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 5px;
            background: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .editor-container {
            min-height: 200px;
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

        .btn-primary:hover {
            background-color: #8a2720;
            border-color: #8a2720;
        }

        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .section-header input {
                margin-left: 0;
                margin-top: 10px;
                width: 100%;
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
                            <a href="admin.php" class="nav-link active">
                                <i class="fas fa-plus-circle me-2"></i>
                                Add New Devotion
                            </a>
                        </li>
                        <li>
                            <a href="manage.php" class="nav-link">
                                <i class="fas fa-list me-2"></i>
                                Manage Devotions
                            </a>
                        </li>
                        <li>
                            <a href="dashboard.php" class="nav-link">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                    </ul>
                    <hr>
                    <div class="dropdown">

                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 ms-sm-auto px-md-4 py-4 content-area">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Add New Devotion</h1>
                </div>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <!-- Verse Section -->
                    <div class="form-section">
                        <h4><i class="fas fa-bible me-2 section-icon"></i>Verse Section</h4>
                        <div class="mb-3">
                            <label for="verse_text" class="form-label">Verse Text</label>
                            <textarea class="form-control editor-container" id="verse_text" name="verse_text"
                                rows="5"><?= htmlspecialchars($_POST['verse_text'] ?? '') ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="verse_reference" class="form-label">Verse Reference</label>
                            <input type="text" class="form-control" id="verse_reference" name="verse_reference"
                                value="<?= htmlspecialchars($_POST['verse_reference'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- Introduction -->
                    <div class="form-section">
                        <h4><i class="fas fa-paragraph me-2 section-icon"></i>Introduction</h4>
                        <div class="mb-3">
                            <textarea class="form-control editor-container" id="introduction_text"
                                name="introduction_text"
                                rows="8"><?= htmlspecialchars($_POST['introduction_text'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- Dynamic Sections -->
                    <?php foreach ($default_headings as $key => $heading): ?>
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
                                    value="<?= htmlspecialchars($_POST[$key . '_heading'] ?? $heading) ?>">
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control editor-container" id="<?= $key ?>" name="<?= $key ?>"
                                    rows="8"><?= htmlspecialchars($_POST[$key] ?? '') ?></textarea>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <!-- Submit Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="reset" class="btn btn-secondary me-md-2">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Devotion
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Enhance textareas with basic functionality
        document.querySelectorAll('.editor-container').forEach(textarea => {
            textarea.addEventListener('keydown', function (e) {
                if (e.key === 'Tab') {
                    e.preventDefault();
                    const start = this.selectionStart;
                    const end = this.selectionEnd;

                    // Set textarea value to: text before caret + tab + text after caret
                    this.value = this.value.substring(0, start) + "\t" + this.value.substring(end);

                    // Put caret at right position again
                    this.selectionStart = this.selectionEnd = start + 1;
                }
            });
        });
    </script>
</body>

</html>
<?php
$conn->close();
?>