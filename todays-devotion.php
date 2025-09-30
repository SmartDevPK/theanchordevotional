<?php
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

// Get today’s date
$today = date("Y-m-d");

// Query for today’s devotion
$sql = "SELECT topic, devotion_date, devotion_image, devotion_verse, verse_reference,
               devotion_intro, devotion_content, devotion_prayer, devotion_tags, created_at
        FROM devotions
        WHERE DATE(devotion_date) = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();

$devotion = $result->num_rows > 0 ? $result->fetch_assoc() : null;

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="anchor-logo.png">
    <link rel="shortcut icon" type="image/png" href="anchor-logo.png">
    <link rel="apple-touch-icon" href="anchor-logo.png">
    
    <title>Today's Devotion - The Anchor Devotional</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        /* Global Variables and Reset */
        :root {
            --primary: #ad3128; /* Maroon as primary */
            --secondary: #2c3e50; /* Blue for buttons */
            --accent: #2c3e50; /* Blue as accent */
            --light: #f8f9fa;
            --dark: #212529;
            --text: #333;
            --text-light: #6c757d;
            --success: #28a745;
            --warning: #ffc107;
            --font-main: 'Segoe UI', system-ui, -apple-system, sans-serif;
            --font-heading: 'Georgia', serif;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 20px rgba(0, 0, 0, 0.15);
            --transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
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
            scroll-behavior: smooth;
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
            transition: var(--transition);
        }

        .header-scrolled {
            background-color: rgba(255, 255, 255, 0.98);
            padding: 10px 0;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
        }

        .logo-container {
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: var(--transition);
        }

        .logo-container:hover {
            transform: scale(1.05);
        }

        .logo-img {
            height: 50px;
            width: auto;
            margin-right: 12px;
        }

        .logo-text {
            display: flex;
            flex-direction: column;
        }

        .logo-main {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
            font-family: var(--font-heading);
        }

        .logo-sub {
            font-size: 0.8rem;
            color: var(--secondary);
            font-weight: 400;
            line-height: 1;
        }

        .nav-links {
            display: flex;
            list-style: none;
        }

        .nav-links li {
            margin-left: 30px;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            transition: var(--transition);
            position: relative;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: var(--primary);
            bottom: -5px;
            left: 0;
            transition: var(--transition);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--primary);
            cursor: pointer;
            padding: 5px;
            position: relative;
            z-index: 1001;
            transition: var(--transition);
        }

        .mobile-menu-btn:hover {
            color: #8a2520;
        }

        .mobile-menu-btn:focus {
            outline: none;
        }

        .mobile-menu {
            position: fixed;
            top: 80px;
            left: -100%;
            width: 100%;
            height: calc(100vh - 80px);
            background: white;
            padding: 30px 20px;
            transition: all 0.3s ease;
            z-index: 999;
            overflow-y: auto;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .mobile-menu.active {
            left: 0;
        }

        .mobile-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .mobile-menu li {
            margin-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .mobile-menu li:last-child {
            border-bottom: none;
        }

        .mobile-menu a {
            display: block;
            padding: 15px 0;
            color: var(--text);
            text-decoration: none;
            font-weight: 500;
            font-size: 1.1rem;
            transition: var(--transition);
            position: relative;
        }

        .mobile-menu a:hover {
            color: var(--primary);
            padding-left: 10px;
        }

        .mobile-menu a.active {
            color: var(--primary);
            font-weight: 600;
        }

        /* Mobile Menu Backdrop */
        .mobile-menu-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 998;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .mobile-menu-backdrop.active {
            opacity: 1;
            visibility: visible;
        }

        /* Admin Button Styles */
        .btn-admin {
            background: linear-gradient(135deg, var(--primary) 0%, #8a2520 100%);
            color: white;
            padding: 10px 18px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(173, 49, 40, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .btn-admin::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-admin:hover::before {
            left: 100%;
        }

        .btn-admin:hover {
            background: linear-gradient(135deg, #8a2520 0%, #6d1e17 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(173, 49, 40, 0.4);
            color: white;
        }

        .btn-admin:active {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(173, 49, 40, 0.4);
        }

        .btn-admin i {
            font-size: 0.85rem;
            transition: transform 0.3s ease;
        }

        .btn-admin:hover i {
            transform: rotate(-10deg) scale(1.1);
        }

        /* Mobile Menu Admin Button */
        .mobile-menu .btn-admin {
            background: linear-gradient(135deg, var(--primary) 0%, #8a2520 100%);
            color: white;
            border-radius: 20px;
            padding: 14px 24px;
            margin: 15px 20px;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 1rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(173, 49, 40, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
            width: calc(100% - 40px);
            max-width: 250px;
        }

        .mobile-menu .btn-admin::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .mobile-menu .btn-admin:hover::before {
            left: 100%;
        }

        .mobile-menu .btn-admin:hover {
            background: linear-gradient(135deg, #8a2520 0%, #6d1e17 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(173, 49, 40, 0.4);
            color: white;
            padding-left: 24px;
        }

        .mobile-menu .btn-admin i {
            font-size: 0.9rem;
            transition: transform 0.3s ease;
        }

        .mobile-menu .btn-admin:hover i {
            transform: rotate(-5deg) scale(1.05);
        }

        /* Utility Classes */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .btn {
            display: inline-block;
            padding: 14px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            text-align: center;
            cursor: pointer;
            border: none;
            font-size: 1rem;
            box-shadow: var(--shadow);
        }

        .btn-primary {
            background-color: var(--secondary);
            color: white;
            border: 2px solid var(--secondary);
        }

        .btn-primary:hover {
            background-color: transparent;
            color: var(--secondary);
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-secondary:hover {
            background-color: var(--primary);
            color: white;
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        /* Devotion Header */
        .devotion-header {
            position: relative;
            height: 70vh;
            min-height: 500px;
            margin-top: 80px;
            overflow: hidden;
            border-radius: 0 0 20px 20px;
            box-shadow: var(--shadow-lg);
        }

        .devotion-header-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .devotion-header:hover .devotion-header-image {
            transform: scale(1.03);
        }

        .devotion-header-content {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 40px;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            color: white;
        }

        .devotion-header-title {
            font-size: 3rem;
            margin-bottom: 15px;
            font-family: var(--font-heading);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .devotion-header-date {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .devotion-header-date i {
            color: var(--primary);
        }

        /* Devotion Content */
        .devotion-content-container {
            padding: 80px 0;
        }

        .devotion-content {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 50px;
            border-radius: 20px;
            box-shadow: var(--shadow);
            position: relative;
            top: -50px;
            margin-bottom: -50px;
        }

        .devotion-verse {
            font-style: italic;
            margin-bottom: 40px;
            color: var(--primary);
            font-weight: 500;
            font-size: 1.4rem;
            border-left: 5px solid var(--primary);
            padding-left: 30px;
            line-height: 1.8;
        }

        .devotion-text {
            margin-bottom: 40px;
            line-height: 1.8;
            font-size: 1.1rem;
            color: var(--text);
        }

        .devotion-text p {
            margin-bottom: 25px;
        }

        .devotion-text h3 {
            font-family: var(--font-heading);
            color: var(--primary);
            margin: 40px 0 20px;
            font-size: 1.6rem;
        }

        .devotion-text blockquote {
            border-left: 4px solid var(--secondary);
            padding-left: 20px;
            margin: 30px 0;
            font-style: italic;
            color: var(--text-light);
        }

        /* Devotion Actions */
        .devotion-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 60px;
            padding-top: 30px;
            border-top: 1px solid #eee;
            flex-wrap: wrap;
            gap: 20px;
        }

        .download-btn {
            background-color: var(--success);
            color: white;
            padding: 14px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 1rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: var(--shadow);
        }

        .download-btn:hover {
            background-color: #219653;
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .social-share {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .social-share span {
            font-weight: 500;
        }

        .social-share a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background-color: #f5f5f5;
            border-radius: 50%;
            color: var(--dark);
            transition: var(--transition);
        }

        .social-share a:hover {
            background-color: var(--primary);
            color: white;
            transform: translateY(-3px);
        }

        /* More Devotions */
        .more-devotions {
            text-align: center;
            padding: 60px 0;
        }

        .more-devotions h2 {
            font-family: var(--font-heading);
            color: var(--primary);
            margin-bottom: 30px;
            font-size: 2rem;
        }

        /* Footer */
        footer {
            background-color: var(--dark);
            color: white;
            padding: 60px 0 20px;
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
            padding-left: 5px;
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
            transform: translateY(-3px);
        }

        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #bbb;
            font-size: 0.9rem;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .devotion-header {
                height: 60vh;
            }
            
            .devotion-header-title {
                font-size: 2.5rem;
            }
            
            .devotion-content {
                padding: 40px;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .btn-admin {
                padding: 8px 14px;
                font-size: 0.85rem;
                gap: 6px;
            }
            
            .logo-text {
                display: none;
            }

            .devotion-header {
                height: 50vh;
                min-height: 400px;
            }
            
            .devotion-header-title {
                font-size: 2rem;
            }
            
            .devotion-header-content {
                padding: 30px;
            }
            
            .devotion-content {
                padding: 30px 20px;
                top: -30px;
                margin-bottom: -30px;
            }
            
            .devotion-verse {
                font-size: 1.2rem;
                padding-left: 20px;
            }
            
            .devotion-actions {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 576px) {
            .devotion-header {
                height: 40vh;
                min-height: 300px;
            }
            
            .devotion-header-title {
                font-size: 1.8rem;
            }
            
            .devotion-header-date {
                font-size: 1rem;
            }
            
            .devotion-content {
                padding: 25px 15px;
            }
        }

        /* Bible Study Tools Styles */
        .bible-tools-section {
            background-color: #f8f9fa;
            padding: 60px 0;
        }

        .bible-tools-section h2 {
            text-align: center;
            color: var(--primary);
            font-family: var(--font-heading);
            margin-bottom: 40px;
            font-size: 2.2rem;
        }

        .tools-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .tool-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border: 1px solid #e9ecef;
        }

        .tool-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }

        .tool-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, #8a2520 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            transition: var(--transition);
        }

        .tool-icon i {
            font-size: 1.8rem;
            color: white;
        }

        .tool-card:hover .tool-icon {
            transform: scale(1.1);
        }

        .tool-card h3 {
            color: var(--primary);
            font-family: var(--font-heading);
            margin-bottom: 15px;
            font-size: 1.3rem;
        }

        .tool-card p {
            color: var(--text-light);
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .tool-link {
            display: inline-block;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            padding: 8px 20px;
            border: 2px solid var(--primary);
            border-radius: 25px;
            transition: var(--transition);
        }

        .tool-link:hover {
            background-color: var(--primary);
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header id="header">
        <div class="container">
            <nav>
                <a href="index.php" class="logo-container">
                    <img src="anchor-logo.png" alt="The Anchor Devotional Logo" class="logo-img">
                    <div class="logo-text">
                        <span class="logo-main">The Anchor</span>
                        <span class="logo-sub">Daily Devotional</span>
                    </div>
                </a>
                
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fas fa-bars"></i>
                </button>
                
                <ul class="nav-links" id="navLinks">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="todays-devotion.php">Today's Devotion</a></li>
                    <li><a href="past-devotions.php">Past Devotions</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="family.php">Family</a></li>
                    <li><a href="#subscribe">Subscribe</a></li>
                </ul>
            </nav>
        </div>
        
        <!-- Mobile Menu Backdrop -->
        <div class="mobile-menu-backdrop" id="mobileMenuBackdrop"></div>
        
        <!-- Mobile Menu -->
        <div class="mobile-menu" id="mobileMenu">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="todays-devotion.php">Today's Devotion</a></li>
                <li><a href="past-devotions.php">Past Devotions</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="family.php">Family</a></li>
                <li><a href="#subscribe">Subscribe</a></li>
            </ul>
        </div>
    </header>

    <!-- Devotion Header -->
  <div class="devotion-header" data-aos="fade-in">
    <!-- Devotion Image -->
    <img src="<?= htmlspecialchars($devotion['devotion_image'] ?? '/assets/default.jpg') ?>" 
         alt="Today's Devotion" 
         class="devotion-header-image">

    <!-- Header Content -->
    <div class="devotion-header-content">
        <!-- Topic / Title -->
        <h1 class="devotion-title">
            <?= htmlspecialchars($devotion['topic'] ?? 'No Topic Available') ?>
        </h1>

        <!-- Devotion Date -->
        <div class="devotion-header-date">
            <i class="fas fa-calendar-alt"></i>
            <p class="devotion-date">
                The Anchor - <?= !empty($devotion['devotion_date']) ? date("F j, Y", strtotime($devotion['devotion_date'])) : date("F j, Y") ?>
            </p>
        </div>

        <!-- Download PDF Button -->
        <a href="download/devotionals.pdf" class="download-btn" download>
            <i class="fas fa-download"></i> Download PDF
        </a>
    </div>
</div>


   <!-- Devotion Content -->
<div class="devotion-content-container">
    <div class="container">
        <div class="devotion-content" data-aos="fade-up">
            
            <!-- Verse of the Day -->
            <blockquote class="scripture-verse">
                <p>
                    <?= nl2br(htmlspecialchars($devotion['devotion_verse'] ?? 'No verse available.')) ?>
                </p>
                <footer>
                    <cite>- <?= htmlspecialchars($devotion['verse_reference'] ?? '') ?></cite>
                </footer>
            </blockquote>

            <!-- Intro -->
            <div class="devotion-intro">
                <p><em><?= nl2br(htmlspecialchars($devotion['devotion_intro'] ?? 'No introduction available.')) ?></em></p>
            </div>

            <!-- Main Content -->
            <div class="devotion-text">
                <?= !empty($devotion['devotion_content']) 
                    ? nl2br(htmlspecialchars($devotion['devotion_content'])) 
                    : '<p>No devotion content available today.</p>' ?>
            </div>

            <!-- Prayer -->
            <div class="devotion-prayer">
                <h3>Prayer</h3>
                <p><?= nl2br(htmlspecialchars($devotion['devotion_prayer'] ?? 'No prayer available.')) ?></p>
            </div>

            <!-- Tags -->
            <div class="devotion-tags">
                <strong>Tags:</strong> <?= htmlspecialchars($devotion['devotion_tags'] ?? 'None') ?>
            </div>

        </div>
    </div>
</div>

                
                <div class="devotion-actions">
                    <a href="#" class="download-btn">
                        <i class="fas fa-download"></i> Download Full Devotional
                    </a>
                    <div class="social-share">
                        <span>Share this devotion:</span>
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                        <a href="#"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bible Study Tools -->
    <section class="bible-tools-section">
        <div class="container">
            <h2 data-aos="fade-up">Study Deeper</h2>
            <div class="tools-grid" data-aos="fade-up" data-aos-delay="100">
                <div class="tool-card">
                    <div class="tool-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>Cross References</h3>
                    <p>Explore related Bible verses to gain deeper understanding</p>
                    <a href="#" class="tool-link">View References</a>
                </div>
                
                <div class="tool-card">
                    <div class="tool-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3>Commentary</h3>
                    <p>Read scholarly insights and historical context</p>
                    <a href="#" class="tool-link">Read Commentary</a>
                </div>
                
                <div class="tool-card">
                    <div class="tool-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Discussion</h3>
                    <p>Join our community discussion on today's devotion</p>
                    <a href="#" class="tool-link">Join Discussion</a>
                </div>
                
                <div class="tool-card">
                    <div class="tool-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <h3>Study Guide</h3>
                    <p>Download printable study materials and reflection questions</p>
                    <a href="#" class="tool-link">Download PDF</a>
                </div>
            </div>
        </div>
    </section>

    <!-- More Devotions -->
    <div class="more-devotions">
        <div class="container">
            <h2 data-aos="fade-up">Explore More Devotions</h2>
            <a href="past-devotions.php" class="btn btn-primary" data-aos="fade-up" data-aos-delay="100">
                <i class="fas fa-book-open"></i> View Past Devotions
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column" data-aos="fade-up">
                    <h3>The Anchor</h3>
                    <p>A daily devotional ministry committed to helping believers grow in their relationship with God through Scripture meditation and prayer.</p>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-column" data-aos="fade-up" data-aos-delay="100">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="todays-devotion.php">Today's Devotion</a></li>
                        <li><a href="past-devotions.php">Past Devotions</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="family.php">Family</a></li>
                    </ul>
                </div>
                <div class="footer-column" data-aos="fade-up" data-aos-delay="200">
                    <h3>Resources</h3>
                    <ul class="footer-links">
                        <li><a href="#">Bible Reading Plans</a></li>
                        <li><a href="#">Free Downloads</a></li>
                        <li><a href="#">Recommended Books</a></li>
                        <li><a href="#">Prayer Guides</a></li>
                        <li><a href="#">FAQs</a></li>
                    </ul>
                </div>
                <div class="footer-column" data-aos="fade-up" data-aos-delay="300">
                    <h3>Contact Us</h3>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt"></i> Gospel Believers Mission HQ, Abuja, Nigeria</li>
                        <li><a href="tel:+2348123456789"><i class="fas fa-phone"></i> +234 812 345 6789</a></li>
                        <li><a href="mailto:info@theanchor.com"><i class="fas fa-envelope"></i> info@theanchor.com</a></li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                &copy; 2025 The Anchor Devotional. All Rights Reserved.
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Anchor Authentication System -->
    <script src="anchor-auth.js"></script>
    <script>
        // Initialize AOS animation library
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileMenuBackdrop = document.getElementById('mobileMenuBackdrop');
        
        function toggleMobileMenu() {
            mobileMenu.classList.toggle('active');
            mobileMenuBackdrop.classList.toggle('active');
            document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : 'auto';
            mobileMenuBtn.innerHTML = mobileMenu.classList.contains('active') ? 
                '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
        }
        
        function closeMobileMenu() {
            mobileMenu.classList.remove('active');
            mobileMenuBackdrop.classList.remove('active');
            document.body.style.overflow = 'auto';
            mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
        }
        
        mobileMenuBtn.addEventListener('click', toggleMobileMenu);
        mobileMenuBackdrop.addEventListener('click', closeMobileMenu);
        
        // Close mobile menu when clicking on menu links
        const mobileMenuLinks = mobileMenu.querySelectorAll('a');
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', closeMobileMenu);
        });

        // Header scroll effect
        const header = document.getElementById('header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 100) {
                header.classList.add('header-scrolled');
            } else {
                header.classList.remove('header-scrolled');
            }
        });

        // Initialize with header scrolled if page is not at top
        if (window.scrollY > 100) {
            header.classList.add('header-scrolled');
        }
    </script>
</body>
</html>
