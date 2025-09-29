<?php
include("db.php");

// --- Handle delete FIRST ---
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $mysqli->prepare("DELETE FROM landing_page_content WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        // redirect after delete
        header("Location:dashboard.php");
        exit;
    } else {
        die("Error deleting: " . $mysqli->error);
    }
}

// --- Handle update ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_homepage'])) {
    $id = (int)$_POST['id'];

    // Handle file upload
    $cover_path = null;
    if (!empty($_FILES['cover_image']['name'])) {
        $upload_dir = "uploads/";
        $cover_path = $upload_dir . basename($_FILES['cover_image']['name']);
        move_uploaded_file($_FILES['cover_image']['tmp_name'], $cover_path);
    }

    // Keep old image if new one not uploaded
    if (!$cover_path) {
        $result = $mysqli->query("SELECT cover_image FROM landing_page_content WHERE id=$id");
        $row = $result->fetch_assoc();
        $cover_path = $row['cover_image'];
    }

    $stmt = $mysqli->prepare("
        UPDATE landing_page_content 
        SET hero_title=?, hero_subtitle=?, hero_description=?, cover_image=?, 
            featured_topic=?, featured_date=?, featured_intro=?, 
            verse_of_day=?, verse_reference=?
        WHERE id=?
    ");
    $stmt->bind_param(
        "sssssssssi",
        $_POST['hero_title'],
        $_POST['hero_subtitle'],
        $_POST['hero_description'],
        $cover_path,
        $_POST['featured_topic'],
        $_POST['featured_date'],
        $_POST['featured_intro'],
        $_POST['verse_of_day'],
        $_POST['verse_reference'],
        $id
    );

    if ($stmt->execute()) {
        echo "<script>alert('Homepage updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating: " . addslashes($mysqli->error) . "');</script>";
    }
}

// --- Fetch existing content ---
$sql = "SELECT * FROM landing_page_content LIMIT 1";
$result = $mysqli->query($sql);
$content = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Homepage Content - Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --danger: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
            --gradient: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 1200px;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .page-header {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            border-left: 5px solid var(--primary);
        }

        .page-header h2 {
            color: var(--dark);
            font-weight: 700;
            margin-bottom: 10px;
        }

        .page-header .subtitle {
            color: #6c757d;
            font-size: 1.1rem;
        }

        .content-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            overflow: hidden;
            border: none;
        }

        .card-header {
            background: var(--gradient);
            color: white;
            padding: 20px 30px;
            border-bottom: none;
        }

        .card-header h3 {
            margin: 0;
            font-weight: 600;
            font-size: 1.4rem;
        }

        .card-body {
            padding: 40px;
        }

        .form-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            border-left: 4px solid var(--primary);
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title::before {
            font-size: 1.4rem;
        }

        .hero-section .section-title::before { content: "🏠"; }
        .image-section .section-title::before { content: "🖼️"; }
        .featured-section .section-title::before { content: "⭐"; }
        .verse-section .section-title::before { content: "✝️"; }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .image-preview {
            margin-top: 15px;
            text-align: center;
        }

        .image-preview img {
            max-width: 300px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: 3px solid #e9ecef;
            transition: transform 0.3s ease;
        }

        .image-preview img:hover {
            transform: scale(1.05);
        }

        .btn {
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }

        .btn-primary {
            background: var(--gradient);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #f72585 0%, #b5179e 100%);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(247, 37, 133, 0.4);
        }

        .btn-outline-light {
            border: 2px solid #dee2e6;
            color: #6c757d;
        }

        .btn-outline-light:hover {
            background: #f8f9fa;
            color: var(--dark);
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .action-buttons .btn {
            flex: 1;
            min-width: 200px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #6c757d;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: var(--primary);
            text-decoration: none;
        }

        .no-content {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .no-content p {
            color: #6c757d;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                margin-top: 20px;
                margin-bottom: 20px;
            }

            .card-body {
                padding: 25px;
            }

            .page-header {
                padding: 20px;
            }

            .form-section {
                padding: 20px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons .btn {
                min-width: 100%;
            }
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 20px;
            }

            .page-header h2 {
                font-size: 1.8rem;
            }

            .btn {
                padding: 10px 20px;
                font-size: 1rem;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .content-card {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h2>Manage Homepage Content</h2>
            <div class="subtitle">Customize your website's landing page appearance and content</div>
        </div>

        <?php if ($content): ?>
            <div class="content-card">
                <div class="card-header">
                    <h3>🛠️ Edit Homepage Content</h3>
                </div>
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $content['id']; ?>">
                        <input type="hidden" name="update_homepage" value="1">

                        <!-- Hero Section -->
                        <div class="form-section hero-section">
                            <div class="section-title">Hero Section</div>
                            <div class="form-group">
                                <label>Main Hero Title</label>
                                <input type="text" class="form-control" name="hero_title"
                                       value="<?php echo htmlspecialchars($content['hero_title']); ?>" 
                                       placeholder="Enter compelling main title" required>
                            </div>

                            <div class="form-group">
                                <label>Hero Subtitle</label>
                                <input type="text" class="form-control" name="hero_subtitle"
                                       value="<?php echo htmlspecialchars($content['hero_subtitle']); ?>" 
                                       placeholder="Enter supporting subtitle" required>
                            </div>

                            <div class="form-group">
                                <label>Hero Description</label>
                                <textarea class="form-control" name="hero_description" 
                                          placeholder="Describe your mission or welcome message..." 
                                          rows="4" required><?php echo htmlspecialchars($content['hero_description']); ?></textarea>
                            </div>
                        </div>

                        <!-- Image Section -->
                        <div class="form-section image-section">
                            <div class="section-title">Cover Image</div>
                            <div class="form-group">
                                <label>Upload New Cover Image</label>
                                <input type="file" class="form-control" name="cover_image" accept="image/*">
                                <small class="form-text text-muted">Recommended size: 1200x600 pixels. Leave empty to keep current image.</small>
                                
                                <?php if ($content['cover_image']): ?>
                                    <div class="image-preview">
                                        <p><strong>Current Image:</strong></p>
                                        <img src="<?php echo $content['cover_image']; ?>" alt="Current cover image">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Featured Content Section -->
                        <div class="form-section featured-section">
                            <div class="section-title">Featured Devotion</div>
                            <div class="form-group">
                                <label>Featured Topic</label>
                                <input type="text" class="form-control" name="featured_topic"
                                       value="<?php echo htmlspecialchars($content['featured_topic']); ?>" 
                                       placeholder="Enter featured devotion topic" required>
                            </div>

                            <div class="form-group">
                                <label>Publication Date</label>
                                <input type="date" class="form-control" name="featured_date"
                                       value="<?php echo $content['featured_date']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Featured Devotion Introduction</label>
                                <textarea class="form-control" name="featured_intro" 
                                          placeholder="Write a brief introduction to the featured devotion..." 
                                          rows="4" required><?php echo htmlspecialchars($content['featured_intro']); ?></textarea>
                            </div>
                        </div>

                        <!-- Verse Section -->
                        <div class="form-section verse-section">
                            <div class="section-title">Verse of the Day</div>
                            <div class="form-group">
                                <label>Bible Verse</label>
                                <textarea class="form-control" name="verse_of_day" 
                                          placeholder="Enter the inspirational verse..." 
                                          rows="3" required><?php echo htmlspecialchars($content['verse_of_day']); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Verse Reference</label>
                                <input type="text" class="form-control" name="verse_reference"
                                       value="<?php echo htmlspecialchars($content['verse_reference']); ?>" 
                                       placeholder="e.g., John 3:16" required>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <button type="submit" class="btn btn-primary">
                                💾 Update Homepage
                            </button>
                            
                            <a href="edit_homepage.php?delete=<?php echo $content['id']; ?>"
                               class="btn btn-danger"
                               onclick="return confirm('⚠️ Are you absolutely sure? This will delete all homepage content and cannot be undone!');">
                                🗑️ Delete Content
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="no-content">
                <p>📭 No homepage content found</p>
                <p><small>You may need to create initial homepage content first.</small></p>
            </div>
        <?php endif; ?>

        <!-- Back to Dashboard -->
        <div class="text-center mt-4">
            <a href="dashboard.php" class="back-link">
                ← Return to Dashboard
            </a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add some interactive features
        $(document).ready(function() {
            // Add loading state to form submission
            $('form').on('submit', function() {
                $('button[type="submit"]').html('🔄 Updating...').prop('disabled', true);
            });

            // Preview image before upload
            $('input[type="file"]').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Remove existing preview if any
                        $('.image-preview').remove();
                        
                        // Add new preview
                        $(this).closest('.form-group').append(`
                            <div class="image-preview">
                                <p><strong>New Image Preview:</strong></p>
                                <img src="${e.target.result}" alt="New cover preview">
                            </div>
                        `);
                    }.bind(this);
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
</body>
</html>