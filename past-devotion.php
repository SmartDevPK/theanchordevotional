<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Database connection
$host = "localhost";
$port = 3307;
$user = "root";
$pass = "";
$db = "prayer_db";

// Connect to database
$conn = new mysqli($host, $user, $pass, $db, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get ID from URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize input

    // Fetch devotion by ID
    $stmt = $conn->prepare("SELECT * FROM devotions WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $devotion = $result->fetch_assoc();
    } else {
        echo "<h2>Devotional not found.</h2>";
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<h2>No devotional ID provided.</h2>";
    exit;
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
                    <li><a href="todays-devotion.php">Devotions</a></li>
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

    <style>
        container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        h1 {
            color: #2c3e50;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #f1f1f1;
        }

        p {
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }

        strong {
            color: #2c3e50;
            font-weight: 600;
        }

        img {
            border-radius: 8px;
            margin: 1.5rem 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        img:hover {
            transform: scale(1.02);
        }

        .btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 1rem;
        }

        .btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            h1 {
                font-size: 2rem;
            }

            p {
                font-size: 1rem;
            }
        }
    </style>

    <div class="container">
        <h1><?= htmlspecialchars($devotion['title']) ?></h1>
        <p><strong>Date:</strong> <?= date('F j, Y', strtotime($devotion['devotion_date'])) ?></p>
        <?php if (!empty($devotion['image'])): ?>
            <img src="<?= htmlspecialchars($devotion['image']) ?>" alt="Devotion Image"
                style="max-width: 100%; height: auto;">
        <?php endif; ?>
        <div>
            <p><?= nl2br(htmlspecialchars($devotion['excerpt'])) ?></p>
        </div>
        <a href="devotions.php" class="btn btn-primary">← Back to Devotions</a>

    </div>
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
                <input type="email" class="subscribe-input" placeholder="Your email address" required>
                <button type="submit" class="subscribe-btn">Subscribe</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column" data-aos="fade-up">
                    <h3>The Anchor</h3>
                    <p>A daily devotional ministry committed to helping believers anchor their faith in God's Word
                        through daily spiritual nourishment.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <div class="footer-column" data-aos="fade-up" data-aos-delay="100">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="todays-devotion.php">Today's Devotion</a></li>
                        <li><a href="past-devotions.php">Past Devotions</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="prayer.php">Prayer Requests</a></li>
                    </ul>
                </div>

                <div class="footer-column" data-aos="fade-up" data-aos-delay="200">
                    <h3>Resources</h3>
                    <ul class="footer-links">
                        <li><a href="bible-reading-plans.php">Bible Reading Plans</a></li>
                        <li><a href="downloads.php">Free Downloads</a></li>
                        <li><a href="books.php">Recommended Books</a></li>
                        <li><a href="blog.php">Blog</a></li>
                        <li><a href="faq.php">FAQs</a></li>
                    </ul>
                </div>

                <div class="footer-column" data-aos="fade-up" data-aos-delay="300">
                    <h3>Contact Us</h3>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt"></i> Gospel Believers Mission HQ, Abuja, Nigeria</li>
                        <li><i class="fas fa-phone"></i> +234 812 345 6789</li>
                        <li><i class="fas fa-envelope"></i> info@theanchor.com</li>
                    </ul>
                </div>
            </div>

            <div class="copyright" data-aos="fade-up">
                <p>&copy; 2023 The Anchor Devotional. All Rights Reserved. | <a href="privacy.php"
                        style="color: #bbb;">Privacy Policy</a> | <a href="terms.php" style="color: #bbb;">Terms of
                        Use</a></p>
            </div>
        </div>
    </footer>



    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS (Animate On Scroll)
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const navLinks = document.getElementById('navLinks');

        mobileMenuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            mobileMenuBtn.innerHTML = navLinks.classList.contains('active') ?
                '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
        });

        // Header Scroll Effect
        window.addEventListener('scroll', () => {
            const header = document.getElementById('header');
            if (window.scrollY > 100) {
                header.classList.add('header-scrolled');
            } else {
                header.classList.remove('header-scrolled');
            }
        });

        // Form Submissions (would be connected to backend in production)
        document.getElementById('prayerRequestForm').addEventListener('submit', function (e) {
            e.preventDefault(); // prevent default form submission

            const form = e.target;
            const formData = new FormData(form);

            // Send form data to submit.php
            fetch('submit.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    // Optional: log the server response
                    console.log('Server response:', data);

                    // Show thank you alert regardless of server response
                    alert('Thank you for your prayer request. Our team will pray for this need.');

                    // Reset the form
                    form.reset();
                })
                .catch(error => {
                    console.error('Submission error:', error);
                    alert('There was a problem submitting your request. Please try again.');
                });
        });

        document.getElementById('subscribeForm').addEventListener('submit', function (e) {
            e.preventDefault();
            alert('Thank you for subscribing to The Anchor Devotional! You will receive your first devotional tomorrow morning.');
            this.reset();
        });

        // Comment Section Functionality
        const commentFormBtn = document.getElementById('commentFormBtn');
        const commentModal = document.getElementById('commentModal');
        const commentModalClose = document.getElementById('commentModalClose');
        const commentForm = document.getElementById('commentForm');
        const commentsList = document.getElementById('commentsList');
        const commentCount = document.getElementById('commentCount');

        // Array to store comments (in a real app, this would be from a database)
        let comments = [];

        // Open comment modal
        commentFormBtn.addEventListener('click', () => {
            commentModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });

        // Close comment modal
        commentModalClose.addEventListener('click', () => {
            commentModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        });

        // Close modal when clicking outside
        commentModal.addEventListener('click', (e) => {
            if (e.target === commentModal) {
                commentModal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });

        // Handle comment submission
        commentForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const name = document.getElementById('commentName').value.trim();
            const text = document.getElementById('commentText').value.trim();

            if (name && text) {
                // Create new comment object
                const newComment = {
                    id: Date.now(),
                    name: name,
                    text: text,
                    date: new Date().toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    })
                };

                // Add to comments array
                comments.unshift(newComment);

                // Update UI
                renderComments();

                // Reset form and close modal
                commentForm.reset();
                commentModal.classList.remove('active');
                document.body.style.overflow = 'auto';

                // Show success message
                alert('Thank you for your comment! It has been posted.');
            }
        });

        // Render comments to the page
        function renderComments() {
            if (comments.length === 0) {
                commentsList.innerHTML = '<div class="no-comments">No comments yet. Be the first to share your thoughts!</div>';
                commentCount.textContent = '0';
                return;
            }

            // Clear existing comments
            commentsList.innerHTML = '';

            // Update comment count
            commentCount.textContent = comments.length;

            // Create HTML for each comment
            comments.forEach(comment => {
                const commentDiv = document.createElement('div');
                commentDiv.className = 'comment';
                commentDiv.innerHTML = `
                    <div class="comment-avatar">${comment.name.charAt(0).toUpperCase()}</div>
                    <div class="comment-content">
                        <div class="comment-meta">
                            <span class="comment-author">${comment.name}</span>
                            <span class="comment-date">${comment.date}</span>
                        </div>
                        <div class="comment-text">${comment.text}</div>
                    </div>
                `;

                commentsList.appendChild(commentDiv);
            });
        }

        // Initial render
        renderComments();
    </script>
</body>

</html>