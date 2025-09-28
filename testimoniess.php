<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share Your Testimony</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            padding: 40px;
            width: 100%;
            max-width: 500px;
            position: relative;
            overflow: hidden;
            animation: slideIn 0.8s ease-out;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shimmer {

            0%,
            100% {
                background-position: 200% 0;
            }

            50% {
                background-position: -200% 0;
            }
        }

        h1 {
            text-align: center;
            margin-bottom: 8px;
            color: #2d3748;
            font-size: 28px;
            font-weight: 700;
        }

        .subtitle {
            text-align: center;
            color: #718096;
            margin-bottom: 32px;
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            font-family: inherit;
            background: white;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .form-group input:hover,
        .form-group textarea:hover {
            border-color: #cbd5e0;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
            font-family: inherit;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #a0aec0;
        }

        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        #testimonyMessage {
            margin-top: 20px;
            padding: 16px;
            border-radius: 12px;
            font-weight: 500;
            text-align: center;
            opacity: 0;
            transition: all 0.4s ease;
        }

        #testimonyMessage.show {
            opacity: 1;
            animation: messageSlide 0.5s ease-out;
        }

        @keyframes messageSlide {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        #testimonyMessage.success {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
        }

        #testimonyMessage.error {
            background: linear-gradient(135deg, #f56565, #e53e3e);
            color: white;
        }

        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
            z-index: -1;
        }

        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .shape1 {
            width: 60px;
            height: 60px;
            background: #667eea;
            border-radius: 50%;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape2 {
            width: 40px;
            height: 40px;
            background: #764ba2;
            border-radius: 50%;
            top: 60%;
            right: 20%;
            animation-delay: 2s;
        }

        .shape3 {
            width: 30px;
            height: 30px;
            background: #f093fb;
            border-radius: 50%;
            bottom: 30%;
            left: 80%;
            animation-delay: 4s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        @media (max-width: 600px) {
            .container {
                padding: 24px;
                margin: 10px;
            }

            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>



<body>
    <div class="container">
        <div class="floating-shapes">
            <div class="shape shape1"></div>
            <div class="shape shape2"></div>
            <div class="shape shape3"></div>
        </div>

        <h1>Share Your Testimony</h1>
        <p class="subtitle">Your story matters and can inspire others</p>

        <form id="testimonyForm" action="submit_testimony.php" method="POST">
            <div class="form-group">
                <input type="text" name="name" id="name" placeholder="Your Name" required>
            </div>

            <div class="form-group">
                <textarea name="message" id="message" placeholder="Share your testimony or experience..."
                    required></textarea>
            </div>

            <button type="submit" class="submit-btn">Submit Testimony</button>
        </form>

        <div id="testimonyMessage"></div>
    </div>

    <script>
        // Enhanced form interaction
        const form = document.getElementById('testimonyForm');
        const inputs = form.querySelectorAll('input, textarea');
        const messageDiv = document.getElementById('testimonyMessage');

        // Add focus animations
        inputs.forEach(input => {
            input.addEventListener('focus', function () {
                this.parentElement.style.transform = 'scale(1.02)';
            });

            input.addEventListener('blur', function () {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Simulate form submission for demo
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = form.querySelector('.submit-btn');
            submitBtn.textContent = 'Submitting...';
            submitBtn.style.background = '#a0aec0';

            setTimeout(() => {
                messageDiv.textContent = 'Thank you! Your testimony has been submitted successfully.';
                messageDiv.className = 'show success';
                submitBtn.textContent = 'Submit Testimony';
                submitBtn.style.background = '';

                // Reset form
                form.reset();
            }, 1500);
        });
    </script>
</body>

</html>