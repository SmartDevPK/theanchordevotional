<?php
// Display errors for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// DB connection params
$host = "localhost";
$port = 3307;
$username = "root";
$password = "";
$database = "prayer_db";

// Connect to DB
$conn = new mysqli($host, $username, $password, $database, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch latest devotion
$result = $conn->query("SELECT * FROM devotion ORDER BY id DESC LIMIT 1");
$devotion = $result ? $result->fetch_assoc() : null;

// Fetch today's devotion
$results = $conn->query("SELECT * FROM today_Devotion ORDER BY id DESC LIMIT 1");
$devotions = $results ? $results->fetch_assoc() : null;

// Now it's safe to close
$conn->close();

// Handle success/error messages
$message = "";
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'submitted':
            $message = "Thank you! Your testimony is pending approval.";
            break;
        case 'approved':
            $message = "Testimony approved successfully!";
            break;
    }
}

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'empty_fields':
            $message = "Please fill all required fields.";
            break;
        // Add more error handling here if needed
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>The Anchor Devotional - Daily Spiritual Nourishment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        /* Global Variables and Reset */
        :root {
            --primary: #ad3128;
            --secondary: #2c3e50;
            --light: #f8f9fa;
            --dark: #212529;
            --text: #333;
            --text-light: #6c757d;
            --font-main: 'Segoe UI', system-ui, -apple-system, sans-serif;
            --font-heading: 'Georgia', serif;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 20px rgba(0, 0, 0, 0.15);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-main);
            line-height: 1.6;
            color: var(--text);
            background-color: #fff;
            overflow-x: hidden;
        }

        /* Header & Navigation */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: var(--shadow);
            z-index: 1000;
            padding: 15px 0;
            transition: var(--transition);
        }

        .header-scrolled {
            padding: 10px 0;
            background-color: rgba(255, 255, 255, 0.98);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-container {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 15px;
        }

        .logo-img {
            height: 50px;
            width: auto;
        }

        .logo-text {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .logo-main {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            font-family: var(--font-heading);
        }

        .logo-sub {
            font-size: 0.9rem;
            color: var(--secondary);
            font-weight: 500;
        }

        .nav-links {
            display: flex;
            list-style: none;
            align-items: center;
        }

        .nav-links li {
            margin-left: 25px;
            position: relative;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            transition: var(--transition);
            font-size: 1rem;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .btn-admin {
            background-color: var(--primary);
            color: white;
            padding: 8px 15px;
            border-radius: 50px;
            font-size: 0.9rem;
            text-decoration: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-admin:hover {
            background-color: #8a2520;
            transform: translateY(-2px);
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--primary);
            cursor: pointer;
        }

        /* Mobile Menu */
        .mobile-menu {
            position: fixed;
            top: 80px;
            left: -100%;
            width: 100%;
            height: calc(100vh - 80px);
            background-color: white;
            padding: 30px 20px;
            transition: var(--transition);
            z-index: 999;
            overflow-y: auto;
            box-shadow: var(--shadow-lg);
        }

        .mobile-menu.active {
            left: 0;
        }

        .mobile-menu ul {
            list-style: none;
        }

        .mobile-menu li {
            margin-bottom: 20px;
        }

        .mobile-menu a {
            text-decoration: none;
            color: var(--dark);
            font-size: 1.1rem;
            display: block;
            padding: 10px 0;
        }

        .mobile-menu .btn-admin {
            margin-top: 20px;
            display: inline-block;
        }

        /* Video Hero Section */
        .video-hero {
            position: relative;
            height: 100vh;
            min-height: 600px;
            overflow: hidden;
            margin-top: 80px;
        }

        .video-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            padding: 0 20px;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            font-family: var(--font-heading);
            line-height: 1.2;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            animation: fadeIn 1s ease forwards;
        }

        .hero-content p {
            font-size: 1.3rem;
            margin-bottom: 40px;
            line-height: 1.8;
            max-width: 800px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
            animation: fadeIn 1s ease forwards 0.3s;
        }

        .cta-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            animation: fadeIn 1s ease forwards 0.6s;
        }

        .btn {
            display: inline-block;
            padding: 14px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            text-align: center;
            font-size: 1rem;
        }

        .btn-primary {
            background-color: var(--secondary);
            color: white;
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-secondary:hover {
            background-color: var(--primary);
            color: white;
        }

        .btn-white {
            background-color: white;
            color: var(--primary);
        }

        /* Devotion Content */
        .section {
            padding: 80px 0;
        }

        .devotion-page {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 40px;
            margin-top: 40px;
        }

        .devotion-cover {
            position: relative;
            height: 400px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            margin-bottom: 30px;
        }

        .cover-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .cover-content {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 30px;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            color: white;
        }

        .cover-topic {
            font-size: 2rem;
            margin-bottom: 10px;
            font-family: var(--font-heading);
        }

        .download-btn {
            background-color: #28a745;
            color: white;
            padding: 12px 25px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
        }

        .download-btn:hover {
            background-color: #218838;
        }

        .devotion-content {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: var(--shadow);
        }

        .devotion-title {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: var(--primary);
            font-family: var(--font-heading);
        }

        .devotion-verse {
            font-style: italic;
            margin-bottom: 30px;
            color: var(--primary);
            font-weight: 500;
            font-size: 1.2rem;
            border-left: 4px solid var(--primary);
            padding-left: 20px;
        }

        .devotion-text p {
            margin-bottom: 20px;
            line-height: 1.8;
        }

        .devotion-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .social-share {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .social-share a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: #f5f5f5;
            border-radius: 50%;
            color: var(--dark);
            transition: var(--transition);
        }

        .social-share a:hover {
            background-color: var(--primary);
            color: white;
        }

        /* Author Column */
        .author-column {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: var(--shadow);
            position: sticky;
            top: 100px;
            height: fit-content;
        }

        .author-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            margin: 0 auto 20px;
            border: 5px solid var(--light);
        }

        .author-name {
            font-size: 1.4rem;
            text-align: center;
            color: var(--primary);
            margin-bottom: 5px;
            font-family: var(--font-heading);
        }

        .author-title {
            text-align: center;
            color: var(--text-light);
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .author-bio {
            margin-bottom: 25px;
            line-height: 1.7;
        }

        .author-contact {
            margin-bottom: 25px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .contact-item i {
            margin-right: 10px;
            color: var(--primary);
            width: 20px;
        }

        .author-social {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .author-social a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: #f5f5f5;
            border-radius: 50%;
            color: var(--dark);
            transition: var(--transition);
        }

        .author-social a:hover {
            background-color: var(--primary);
            color: white;
        }

        /* Comments Section */
        .comments-section {
            margin-top: 60px;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: var(--shadow);
        }

        .comments-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .comments-title {
            font-size: 1.5rem;
            color: var(--primary);
            font-family: var(--font-heading);
        }

        .comment-count {
            background: var(--primary);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .comment-form-btn {
            text-decoration: none;
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
        }

        .comment-form-btn:hover {
            background-color: #8a2520;
        }

        .comments-list {
            margin-top: 30px;
        }

        .comment {
            display: flex;
            gap: 20px;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }

        .comment-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-weight: bold;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .comment-content {
            flex-grow: 1;
        }

        .comment-author {
            font-weight: 600;
            color: var(--primary);
        }

        .comment-date {
            font-size: 0.8rem;
            color: var(--text-light);
        }

        .comment-text {
            line-height: 1.6;
        }

        /* Prayer Request Section */
        .prayer {
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)),
                url('https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .prayer-form {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: var(--shadow);
            max-width: 800px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--primary);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: var(--font-main);
        }

        textarea.form-control {
            min-height: 150px;
        }

        /* Testimonies Section */
        .testimony-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }

        .testimony-card {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: var(--shadow);
        }

        .testimony-meta {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .testimony-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--primary);
            font-weight: bold;
            font-size: 1.2rem;
        }

        .testimony-name {
            font-weight: 600;
            color: var(--primary);
        }

        .testimony-date {
            font-size: 0.8rem;
            color: var(--text-light);
        }

        /* Subscribe Section */
        .subscribe {
            background-color: var(--primary);
            color: white;
            text-align: center;
        }

        .subscribe-form {
            max-width: 600px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .subscribe-input {
            flex: 1;
            min-width: 300px;
            padding: 15px;
            border: none;
            border-radius: 50px 0 0 50px;
        }

        .subscribe-btn {
            padding: 15px 30px;
            border-radius: 0 50px 50px 0;
            background-color: var(--secondary);
            color: white;
            border: none;
            font-weight: 600;
            cursor: pointer;
        }

        /* Footer */
        footer {
            background-color: var(--dark);
            color: white;
            padding: 80px 0 20px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-column h3 {
            font-size: 1.2rem;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-column h3::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 40px;
            height: 2px;
            background-color: var(--primary);
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: #bbb;
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: white;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            transition: var(--transition);
        }

        .social-links a:hover {
            background-color: var(--primary);
        }

        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #bbb;
            font-size: 0.9rem;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .devotion-page {
                grid-template-columns: 1fr;
            }

            .author-column {
                position: static;
                margin-top: 40px;
            }

            .hero-content h1 {
                font-size: 2.8rem;
            }
        }

        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }

            .nav-links {
                display: none;
            }

            .logo-text {
                display: none;
            }

            .hero-content h1 {
                font-size: 2.2rem;
            }

            .hero-content p {
                font-size: 1.1rem;
            }

            .cta-buttons {
                flex-direction: column;
                gap: 15px;
            }

            .btn {
                width: 100%;
            }

            .devotion-cover {
                height: 300px;
            }

            .devotion-content {
                padding: 30px 20px;
            }

            .devotion-actions {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .comments-section {
                padding: 20px;
            }

            .comment {
                flex-direction: column;
                gap: 15px;
            }

            .btn-admin {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 576px) {
            .hero-content h1 {
                font-size: 2rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .cover-topic {
                font-size: 1.5rem;
            }

            .devotion-verse {
                font-size: 1rem;
            }

            .subscribe-form {
                flex-direction: column;
            }

            .subscribe-input {
                border-radius: 50px;
                margin-bottom: 10px;
            }

            .subscribe-btn {
                border-radius: 50px;
            }
        }
    </style>
</head>

<body>
    <!-- Header with Admin Button -->
    <header id="header">
        <div class="container">
            <nav>
                <a href="index.php" class="logo-container">
                    <img src="the anch logo.png" alt="The Anchor Logo" class="logo-img">
                    <div class="logo-text">
                        <span class="logo-main">The Anchor</span>
                        <span class="logo-sub">Daily Devotional</span>
                    </div>
                </a>

                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fas fa-bars"></i>
                </button>

                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="past-devotions.php">Devotions</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="family.php">Family</a></li>
                    <li><a href="#subscribe">Subscribe</a></li>
                    <li><a href="login.php" class="btn-admin"><i class="fas fa-lock"></i> Admin</a></li>
                </ul>
            </nav>
        </div>

        <!-- Mobile Menu -->
        <div class="mobile-menu" id="mobileMenu">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="todays-devotion.php">Devotions</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="family.php">Family</a></li>
                <li><a href="#subscribe">Subscribe</a></li>
                <li><a href="login.php" class="btn-admin"><i class="fas fa-lock"></i> Admin Login</a></li>
            </ul>
        </div>
    </header>

    <!-- Video Hero Section -->
    <section class="video-hero" id="home">
        <video class="video-background" autoplay muted loop>
            <source src="hero video.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="video-overlay"></div>
        <div class="hero-content">
            <h1>Daily Spiritual Nourishment</h1>
            <p>Anchor your soul in God's Word with our daily devotionals. Each day brings fresh insight, biblical
                wisdom, and practical application for your spiritual journey.</p>
            <div class="cta-buttons">
                <a href="todays-devotion.php" class="btn btn-white">Today's Devotion</a>
                <a href="#subscribe" class="btn btn-secondary">Subscribe Daily</a>
            </div>
        </div>
    </section>

    <!-- Devotion Content -->
    <section class="section" id="devotion">
        <div class="container">
            <?php if ($message): ?>
                <div class="alert-message"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <div class="devotion-page">

                <!-- Main Devotion Content -->
                <div class="devotion-main">

                    <!-- Devotion Cover -->
                    <div class="devotion-cover">
                        <img src="<?= htmlspecialchars($devotion['image_path']) ?>" alt="Today's Devotion"
                            class="cover-image" />
                        <div class="cover-content">
                            <h2 class="cover-topic"><?= htmlspecialchars($devotion['topic']) ?></h2>
                            <p class="cover-date">
                                <span>The Anchor - <?= date("F j, Y", strtotime($devotion['date'])) ?></span>
                            </p>
                            <a href="download/devotionals.pdf" class="download-btn" download>
                                <i class="fas fa-download"></i> Download PDF
                            </a>
                        </div>
                    </div>

                    <!-- Devotion Text Content -->
                    <div class="devotion-content">
                        <?php if ($devotions): ?>
                            <h3 class="devotion-title"><?= htmlspecialchars($devotions['title']) ?></h3>
                            <p class="devotion-verse"><?= nl2br(htmlspecialchars($devotions['verse'])) ?></p>

                            <div class="devotion-text">
                                <?= $devotions['content'] ?>
                            </div>
                        <?php else: ?>
                            <p>No devotion found for today.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Comments Section -->
                    <div class="comments-section">
                        <div class="comments-header">
                            <h3 class="comments-title">Comments <span class="comment-count">0</span></h3>
                            <a href="comments.php" class="comment-form-btn">
                                <i class="fas fa-comment"></i> Leave a Comment
                            </a>
                        </div>

                        <div class="comments-list" id="commentsList">
                            <div class="no-comments">No comments yet. Be the first to share your thoughts!</div>
                        </div>
                    </div>

                    <!-- See More Devotions Button -->
                    <div class="see-more-devotions">
                        <a href="devotions.php" class="btn btn-primary">
                            <i class="fas fa-book-open"></i> See More Devotions
                        </a>
                    </div>
                </div>

                <!-- Author Info Sidebar -->
                <aside class="author-column">
                    <div class="author-header">
                        <img src="PROFILE DADDY 1.png" alt="Maj Gen (Dr) Ezra Jahadi Jakko (Rtd)"
                            class="author-avatar" />
                        <h3 class="author-name">Maj Gen (Dr) Ezra Jahadi Jakko (Rtd)</h3>
                        <p class="author-title">
                            Pastor/General Overseer<br />
                            Gospel Believers Mission
                        </p>
                    </div>

                    <div class="author-bio">
                        <p>Pastor has been writing daily devotionals for years. His insights into Scripture have helped
                            thousands grow in their faith journey.</p>
                    </div>

                    <div class="author-contact">
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>pastor@theanchor.com</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>+234 812 345 6789</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Abuja, Nigeria</span>
                        </div>
                    </div>

                    <div class="author-social">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </aside>

            </div> <!-- end devotion-page -->
        </div> <!-- end container -->
    </section>

    <!-- Prayer Request Section -->
    <section class="section prayer" id="prayer">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Prayer Requests</h2>
            <div class="prayer-form" data-aos="fade-up" data-aos-delay="100">
                <form id="prayerRequestForm" action="submit_prayer.php" method="POST">
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email (optional)</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="request">Prayer Request</label>
                        <textarea id="request" name="prayer" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="sharePublicly" name="sharePublicly" value="1"> Share this request
                            publicly (anonymous)
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Testimonies Section -->
    <section class="section testimonies" id="testimonies">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Recent Testimonies</h2>

            <!-- This will be filled by JavaScript -->
            <div class="testimony-grid" id="testimony-grid" data-aos="fade-up"></div>

            <div style="text-align: center; margin-top: 40px;" data-aos="fade-up">
                <a href="testimonies.php" class="btn btn-secondary">Add More Testimonies</a>
            </div>
        </div>
    </section>

    <!-- Subscribe Section -->
    <section class="section subscribe" id="subscribe">
        <div class="container">
            <h2 class="section-title" style="color: white;" data-aos="fade-up">Stay Connected</h2>
            <p style="text-align: center; margin-bottom: 30px; max-width: 700px; margin-left: auto; margin-right: auto;"
                data-aos="fade-up">
                Receive daily devotionals directly in your inbox. Join our community of believers growing together in
                faith.
            </p>
            <form class="subscribe-form" id="subscribeForm" data-aos="fade-up" data-aos-delay="100">
                <input type="email" name="email" class="subscribe-input" placeholder="Your email address" required>
                <button type="submit" class="subscribe-btn">Subscribe</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>The Anchor</h3>
                    <p>A daily devotional ministry committed to helping believers anchor their faith in God's Word
                        through daily spiritual nourishment.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="todays-devotion.php">Today's Devotion</a></li>
                        <li><a href="about.php">About</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Contact Us</h3>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt"></i> Gospel Believers Mission HQ, Abuja, Nigeria</li>
                        <li><i class="fas fa-phone"></i> +234 812 345 6789</li>
                        <li><i class="fas fa-envelope"></i> info@theanchor.com</li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2023 The Anchor Devotional. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS animations
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // ------------------ Navigation & Scroll Effects ------------------

        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        mobileMenuBtn?.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');
            mobileMenuBtn.innerHTML = mobileMenu.classList.contains('active') ?
                '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
        });

        window.addEventListener('scroll', () => {
            const header = document.getElementById('header');
            if (window.scrollY > 100) {
                header.classList.add('header-scrolled');
            } else {
                header.classList.remove('header-scrolled');
            }
        });

        // ------------------ Devotional Fetch & Display ------------------

        async function fetchDevotionals(limit = null, filter = {}) {
            let url = 'api/index.php/devotionals';
            try {
                const response = await fetch(url);
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return await response.json();
            } catch (error) {
                console.error('Error fetching devotionals:', error);
                return [];
            }
        }

        function displayDevotionals(devotionals) {
            const devotionGrid = document.querySelector('.devotions-grid');
            if (!devotionGrid) return;

            devotionGrid.innerHTML = devotionals.length === 0
                ? '<p>No devotionals found. Please check back later or adjust your filters.</p>'
                : '';

            devotionals.forEach(devotional => {
                const card = document.createElement('div');
                card.className = 'devotion-card';
                card.innerHTML = `
                <img src="${devotional.cover_image_url || 'placeholder.jpg'}" alt="Devotion Cover" class="devotion-image">
                <div class="devotion-content">
                    <div class="devotion-date">${new Date(devotional.date).toLocaleDateString()}</div>
                    <h3 class="devotion-title">${devotional.title}</h3>
                    <p class="devotion-excerpt">${devotional.content.substring(0, 100)}...</p>
                    <a href="single-devotion.html?id=${devotional.id}" class="read-more">
                        Read More <i class="fas fa-arrow-right"></i>
                    </a>
                </div>`;
                devotionGrid.appendChild(card);
            });
        }

        // ------------------ Comments Section ------------------

        function loadComments() {
            fetch('fetch_comments.php')
                .then(res => res.json())
                .then(data => {
                    const commentList = document.getElementById("commentsList");
                    const commentCount = document.getElementById("commentCount");
                    commentList.innerHTML = "";

                    if (data.length === 0) {
                        commentList.innerHTML = '<div class="no-comments">No comments yet. Be the first to share your thoughts!</div>';
                        commentCount.textContent = '0';
                        return;
                    }

                    data.forEach(comment => {
                        commentList.insertAdjacentHTML("beforeend", `
                        <div class="comment">
                            <h4>${comment.name}</h4>
                            <small>${comment.created_at}</small>
                            <p>${comment.comment}</p>
                            <hr>
                        </div>`);
                    });

                    commentCount.textContent = data.length;
                })
                .catch(error => console.error("Error fetching comments:", error));
        }

        // ------------------ Testimonies Section ------------------

        function loadTestimonies() {
            fetch('Submit_Testimony.php')
                .then(res => res.json())
                .then(data => {
                    const grid = document.getElementById('testimony-grid');
                    grid.innerHTML = '';

                    data.forEach(testimony => {
                        const card = document.createElement('div');
                        card.className = 'testimony-card';
                        card.setAttribute('data-aos', 'fade-up');
                        card.innerHTML = `
                        <div class="testimony-meta">
                            <div class="testimony-avatar">${testimony.initials}</div>
                            <div>
                                <div class="testimony-name">${testimony.name}</div>
                                <div class="testimony-date">${testimony.date}</div>
                            </div>
                        </div>
                        <div class="testimony-content">
                            <p>${testimony.message}</p>
                        </div>`;
                        grid.appendChild(card);
                    });
                })
                .catch(error => {
                    console.error('Error fetching testimonies:', error);
                    document.getElementById('testimony-grid').innerHTML = '<p style="color:red;">Failed to load testimonies.</p>';
                });
        }

        // ------------------ Form Submissions ------------------

        // Prayer Request Form
        document.getElementById('prayerRequestForm')?.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('submit.php', {
                method: 'POST',
                body: formData
            })
                .then(res => res.text())
                .then(data => {
                    console.log('Server response:', data);
                    alert('Thank you for your prayer request. Our team will pray for this need.');
                    this.reset();
                })
                .catch(error => {
                    console.error('Submission error:', error);
                    alert('There was a problem submitting your request. Please try again.');
                });
        });

        // Newsletter Subscription Form
        document.getElementById('subscribeForm')?.addEventListener('submit', function (e) {
            e.preventDefault();
            const emailInput = document.querySelector('.subscribe-input');
            const email = emailInput.value.trim();

            if (!email) {
                alert('Please enter your email.');
                return;
            }

            fetch('subscribe.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'email=' + encodeURIComponent(email)
            })
                .then(res => res.text())
                .then(data => {
                    alert(data);
                    emailInput.value = '';
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('There was an error. Please try again.');
                });
        });

        // ------------------ On DOM Load ------------------

        document.addEventListener('DOMContentLoaded', async () => {
            const devotionals = await fetchDevotionals();
            displayDevotionals(devotionals);
            loadComments();
            loadTestimonies();

            // Filter form listener (demo only)
            document.querySelector('.filter-form')?.addEventListener('submit', async (e) => {
                e.preventDefault();
                alert('Filtering not fully implemented in this example.');
            });
        });
    </script>

</body>

</html>