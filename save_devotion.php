<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devotional Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .form-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        }

        .form-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }

        .form-header h2 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .form-header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        #devotional-form {
            padding: 40px;
        }

        .row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .col-md-6 {
            flex: 1;
        }

        .form-group {
            position: relative;
        }

        .mb-3 {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 15px;
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fb;
            resize: vertical;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }

        .form-control:hover {
            border-color: #c1c8d1;
            background: white;
        }

        textarea.form-control {
            min-height: 80px;
            font-family: inherit;
        }

        input[type="file"].form-control {
            padding: 12px;
            background: white;
            border: 2px dashed #667eea;
            cursor: pointer;
            position: relative;
        }

        input[type="file"].form-control:hover {
            border-color: #5a67d8;
            background: #f7fafc;
        }

        input[type="date"].form-control {
            background: white;
        }

        .d-flex {
            display: flex;
        }

        .justify-content-end {
            justify-content: flex-end;
        }

        .gap-2 {
            gap: 15px;
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            min-width: 140px;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-outline-secondary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: 2px solid transparent;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-outline-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-outline-secondary:active {
            transform: translateY(0);
        }

        /* Loading state */
        .btn.loading {
            color: transparent;
        }

        .btn.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid transparent;
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Form validation styles */
        .form-control:invalid:not(:focus):not(:placeholder-shown) {
            border-color: #e53e3e;
            background: #fef5f5;
        }

        .form-control:valid:not(:focus) {
            border-color: #38a169;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .form-container {
                margin: 10px;
                border-radius: 15px;
            }

            .form-header {
                padding: 20px;
            }

            .form-header h2 {
                font-size: 1.8rem;
            }

            #devotional-form {
                padding: 20px;
            }

            .row {
                flex-direction: column;
                gap: 0;
            }

            .col-md-6 {
                flex: none;
            }

            .d-flex {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .form-header h2 {
                font-size: 1.5rem;
            }

            #devotional-form {
                padding: 15px;
            }
        }

        /* Custom file input styling */
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-input-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }

        .file-input-label {
            display: block;
            padding: 15px;
            border: 2px dashed #667eea;
            border-radius: 12px;
            background: white;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s ease;
            color: #667eea;
            font-weight: 600;
        }

        .file-input-label:hover {
            border-color: #5a67d8;
            background: #f7fafc;
        }

        .file-input-label i {
            display: block;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        /* Success message */
        .success-message {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: center;
            display: none;
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Floating labels effect */
        .form-group.floating {
            position: relative;
        }

        .form-group.floating label {
            position: absolute;
            top: 15px;
            left: 15px;
            background: white;
            padding: 0 5px;
            transition: all 0.3s ease;
            pointer-events: none;
            color: #999;
            text-transform: none;
            font-weight: 400;
        }

        .form-group.floating .form-control:focus+label,
        .form-group.floating .form-control:not(:placeholder-shown)+label {
            top: -8px;
            left: 10px;
            font-size: 0.8rem;
            color: #667eea;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    ?>

    <div class="form-container">
        <div class="form-header mb-4">
            <h2>Create New Devotional</h2>
            <p>Share your spiritual insights and inspire others</p>
        </div>

        <!-- Success message -->
        <div class="alert alert-success d-none" id="successMessage">
        </div>

        <form action="save_devotional.php" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" class="form-control"
                            placeholder="Enter devotional title..." required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="excerpt">Excerpt</label>
                        <textarea name="excerpt" id="excerpt" class="form-control" rows="3"
                            placeholder="Brief description or summary..." required></textarea>
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="devotional_date">Date</label>
                <input type="date" name="devotional_date" id="devotional_date" class="form-control" required>
            </div>

            <div class="form-group mb-4">
                <label for="image">Cover Image</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" name="submit" id="submitBtn" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

    </div>

    <script>
        // Set today's date as default
        document.getElementById('devotional_date').valueAsDate = new Date();

        // Form submission with loading state
        document.getElementById('devotional-form').addEventListener('submit', function (e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Saving...';
        });

        // File input feedback
        document.getElementById('image').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                console.log('Selected file:', file.name);
                // You can add file preview functionality here
            }
        });

        // Basic form validation feedback
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('blur', function () {
                if (this.value.trim() === '' && this.hasAttribute('required')) {
                    this.style.borderColor = '#e53e3e';
                } else if (this.value.trim() !== '') {
                    this.style.borderColor = '#38a169';
                }
            });

            input.addEventListener('focus', function () {
                this.style.borderColor = '#667eea';
            });
        });
    </script>
</body>

</html>