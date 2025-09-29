<?php
include("db.php");

// --- Handle Deletion ---
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $mysqli->prepare("DELETE FROM devotions WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "<script>
            alert('Devotion deleted successfully');
            window.location.href='manage_devotions.php';
          </script>";
    exit;
}

// If ?edit=ID is set, fetch that devotion; otherwise fetch the latest
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $res = $mysqli->query("SELECT * FROM devotions WHERE id=$id");
} else {
    $res = $mysqli->query("SELECT * FROM devotions ORDER BY devotion_date DESC LIMIT 1");
}
$editDevotion = $res->fetch_assoc();

// --- Handle Update ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id      = (int)$_POST['id'];
    $topic   = $_POST['topic'];
    $date    = $_POST['devotion_date'];
    $verse   = $_POST['devotion_verse'];
    $ref     = $_POST['verse_reference'];
    $intro   = $_POST['devotion_intro'];
    $content = $_POST['devotion_content'];
    $prayer  = $_POST['devotion_prayer'];
    $tags    = $_POST['devotion_tags'];

    // Handle image upload
    $image_path = null;
    if (!empty($_FILES['devotion_image']['name'])) {
        $upload_dir = "uploads/";
        $image_path = $upload_dir . basename($_FILES['devotion_image']['name']);
        move_uploaded_file($_FILES['devotion_image']['tmp_name'], $image_path);
    } else {
        $res = $mysqli->query("SELECT devotion_image FROM devotions WHERE id=$id");
        $row = $res->fetch_assoc();
        $image_path = $row['devotion_image'];
    }

    // Update the devotion
    $stmt = $mysqli->prepare("
        UPDATE devotions 
        SET topic=?, devotion_date=?, devotion_image=?, devotion_verse=?, verse_reference=?, devotion_intro=?, devotion_content=?, devotion_prayer=?, devotion_tags=? 
        WHERE id=?
    ");
    $stmt->bind_param("sssssssssi", $topic, $date, $image_path, $verse, $ref, $intro, $content, $prayer, $tags, $id);
    $stmt->execute();

    echo "<script>
            alert('Devotion updated successfully');
            window.location.href='manage_devotions.php';
          </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Devotion - Devotion Manager</title>
    <style>
        /* Global Styles */
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

        /* Container */
        .container {
            max-width: 1000px;
            margin: 0 auto;
            animation: fadeIn 0.6s ease-in;
        }

        /* Header */
        .page-header {
            text-align: center;
            margin-bottom: 40px;
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .page-header h2 {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .page-header .subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Card */
        .card {
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin-bottom: 30px;
            overflow: hidden;
            border: none;
            background: white;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }

        .card-header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            font-size: 1.4rem;
            font-weight: 600;
            padding: 20px 30px;
            border-bottom: none;
        }

        .card-body {
            padding: 40px;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            display: block;
            font-size: 1rem;
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e8f0fe;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f8fafc;
        }

        .form-control:focus {
            border-color: #4facfe;
            box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.1);
            background-color: white;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }

        .form-row .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        /* Content Editor */
        .editor-container {
            position: relative;
        }

        #devotion-content {
            border: 2px solid #e8f0fe;
            border-radius: 10px;
            padding: 16px;
            min-height: 200px;
            background-color: #f8fafc;
            line-height: 1.6;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        #devotion-content:focus {
            border-color: #4facfe;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.1);
        }

        #devotion-content p {
            margin-bottom: 12px;
        }

        #devotion-content ul, #devotion-content ol {
            margin-left: 24px;
            margin-bottom: 12px;
        }

        /* Image Upload */
        .image-upload-container {
            position: relative;
        }

        .current-image {
            margin-top: 15px;
            text-align: center;
        }

        .current-image img {
            max-width: 250px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: 3px solid #e8f0fe;
            transition: transform 0.3s ease;
        }

        .current-image img:hover {
            transform: scale(1.05);
        }

        /* Buttons */
        .btn {
            font-weight: 600;
            padding: 14px 30px;
            border-radius: 10px;
            border: none;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-lg {
            padding: 16px 40px;
            font-size: 1.2rem;
        }

        .btn-success {
            background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 176, 155, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4);
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .action-buttons form {
            flex: 1;
            min-width: 200px;
        }

        .action-buttons .btn {
            width: 100%;
        }

        /* No Content Message */
        .no-content {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
            font-size: 1.2rem;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .page-header h2 {
                font-size: 2rem;
            }

            .card-body {
                padding: 25px;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-lg {
                padding: 14px 25px;
                font-size: 1.1rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .card-body {
                padding: 20px;
            }

            .page-header h2 {
                font-size: 1.8rem;
            }

            .card-header {
                padding: 15px 20px;
                font-size: 1.2rem;
            }
        }

        /* Form Section Styling */
        .form-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            border-left: 4px solid #4facfe;
        }

        .form-section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-section-title::before {
            content: "📖";
            font-size: 1.4rem;
        }

        .image-section .form-section-title::before {
            content: "🖼️";
        }

        .scripture-section .form-section-title::before {
            content: "✝️";
        }

        .content-section .form-section-title::before {
            content: "📝";
        }

        .prayer-section .form-section-title::before {
            content: "🙏";
        }

        .tags-section .form-section-title::before {
            content: "🏷️";
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="page-header">
            <h2>Edit Devotion Entry</h2>
            <div class="subtitle">Manage and update your devotion content</div>
        </div>

        <?php if ($editDevotion): ?>
        <div class="card">
            <div class="card-header">
                Editing: <?php echo htmlspecialchars($editDevotion['topic']); ?>
            </div>
            <div class="card-body">
                <!-- Update Form -->
                <form method="POST" enctype="multipart/form-data" id="devotion-form">
                    <input type="hidden" name="id" value="<?php echo $editDevotion['id']; ?>">

                    <!-- Basic Information Section -->
                    <div class="form-section">
                        <div class="form-section-title">Basic Information</div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Topic / Title</label>
                                <input type="text" name="topic" class="form-control" 
                                       value="<?php echo htmlspecialchars($editDevotion['topic']); ?>" 
                                       placeholder="Enter devotion title" required>
                            </div>
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" name="devotion_date" class="form-control" 
                                       value="<?php echo $editDevotion['devotion_date']; ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- Image Section -->
                    <div class="form-section image-section">
                        <div class="form-section-title">Devotion Image</div>
                        <div class="form-group image-upload-container">
                            <label>Upload New Image (Optional)</label>
                            <input type="file" name="devotion_image" class="form-control" 
                                   accept="image/*">
                            <?php if(!empty($editDevotion['devotion_image'])): ?>
                                <div class="current-image">
                                    <p><strong>Current Image:</strong></p>
                                    <img src="<?php echo $editDevotion['devotion_image']; ?>" 
                                         alt="Current devotion image">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Scripture Section -->
                    <div class="form-section scripture-section">
                        <div class="form-section-title">Scripture Reference</div>
                        <div class="form-group">
                            <label>Scripture Verse</label>
                            <textarea name="devotion_verse" class="form-control" rows="3" 
                                      placeholder="Enter the scripture verse..." required><?php echo htmlspecialchars($editDevotion['devotion_verse']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Verse Reference</label>
                            <input type="text" name="verse_reference" class="form-control" 
                                   value="<?php echo htmlspecialchars($editDevotion['verse_reference']); ?>" 
                                   placeholder="e.g., John 3:16" required>
                        </div>
                    </div>

                    <!-- Introduction Section -->
                    <div class="form-section">
                        <div class="form-section-title">Introduction</div>
                        <div class="form-group">
                            <textarea name="devotion_intro" class="form-control" rows="4" 
                                      placeholder="Write a compelling introduction..." required><?php echo htmlspecialchars($editDevotion['devotion_intro']); ?></textarea>
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="form-section content-section">
                        <div class="form-section-title">Devotion Content</div>
                        <div class="form-group editor-container">
                            <label>Full Content (Rich Text Editor)</label>
                            <div id="devotion-content" contenteditable="true"><?php echo $editDevotion['devotion_content']; ?></div>
                            <textarea name="devotion_content" id="devotion-content-hidden" style="display:none;" required><?php echo htmlspecialchars($editDevotion['devotion_content']); ?></textarea>
                            <small class="form-text text-muted">You can format text using bold, italics, lists, etc.</small>
                        </div>
                    </div>

                    <!-- Prayer Section -->
                    <div class="form-section prayer-section">
                        <div class="form-section-title">Closing Prayer</div>
                        <div class="form-group">
                            <label>Closing Prayer (Optional)</label>
                            <textarea name="devotion_prayer" class="form-control" rows="3" 
                                      placeholder="Write a closing prayer..."><?php echo htmlspecialchars($editDevotion['devotion_prayer']); ?></textarea>
                        </div>
                    </div>

                    <!-- Tags Section -->
                    <div class="form-section tags-section">
                        <div class="form-section-title">Tags & Categories</div>
                        <div class="form-group">
                            <label>Tags (comma-separated)</label>
                            <input type="text" name="devotion_tags" class="form-control" 
                                   value="<?php echo htmlspecialchars($editDevotion['devotion_tags']); ?>" 
                                   placeholder="e.g., faith, hope, love, salvation">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button type="submit" class="btn btn-success btn-lg">
                            💾 Update Devotion
                        </button>
                        
                        <form method="GET" onsubmit="return confirm('Are you absolutely sure you want to delete this devotion? This action cannot be undone.');">
                            <input type="hidden" name="delete" value="<?php echo $editDevotion['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-lg">
                                🗑️ Delete Devotion
                            </button>
                        </form>
                    </div>
                </form>
            </div>
        </div>
        <?php else: ?>
            <div class="no-content">
                <p>No devotion found to edit.</p>
                <p><small>Please check if the devotion exists or try creating a new one.</small></p>
            </div>
        <?php endif; ?>
    </div>

    <script>
        const editor = document.getElementById('devotion-content');
        const hidden = document.getElementById('devotion-content-hidden');

        // Sync content editor with hidden textarea
        document.getElementById('devotion-form')?.addEventListener('submit', () => {
            hidden.value = editor.innerHTML;
        });

        // Set default paragraph separator
        editor?.addEventListener('focus', () => {
            document.execCommand('defaultParagraphSeparator', false, 'p');
        });

        // Add some basic rich text functionality
        editor?.addEventListener('input', () => {
            // Auto-save functionality could be added here
        });

        // Add keyboard shortcuts for basic formatting
        editor?.addEventListener('keydown', (e) => {
            // Ctrl+B for bold
            if (e.ctrlKey && e.key === 'b') {
                e.preventDefault();
                document.execCommand('bold');
            }
            // Ctrl+I for italic
            if (e.ctrlKey && e.key === 'i') {
                e.preventDefault();
                document.execCommand('italic');
            }
        });
    </script>
</body>
</html>