<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);


$host = "localhost";
$port = 3307;
$username = "root";
$password = "";
$database = "prayer_db";

// connect
$conn = new mysqli($host, $username, $password, $database, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// get latest record by updated_at
$sql = "SELECT hero_title, hero_subtitle, hero_description, cover_image,
               featured_topic, featured_date, featured_intro,
               verse_of_day, verse_reference, updated_at
        FROM landing_page_content
        ORDER BY updated_at DESC
        LIMIT 1";

$result = $conn->query($sql);

// ensure $landing always exists (avoid undefined variable)
$landing = null;
if ($result) {
    $landing = $result->num_rows ? $result->fetch_assoc() : null;
} else {
    error_log("Landing query failed: " . $conn->error); // log error for debugging
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="the anch logo.png">
    <link rel="shortcut icon" type="image/png" href="the anch logo.png">
    <link rel="apple-touch-icon" href="the anch logo.png">
    
    <!-- SEO Meta Tags -->
    <title>The Anchor Devotional - Daily Spiritual Nourishment & Bible Study</title>
    <meta name="description" content="Join thousands in daily spiritual growth with The Anchor Devotional. Get biblical wisdom, prayer requests, and spiritual nourishment delivered daily. Free Christian devotionals from Pastor Ezra Jahadi Jakko.">
    <meta name="keywords" content="daily devotional, christian devotional, bible study, spiritual growth, prayer requests, christian ministry, anchor devotional, Pastor Ezra Jakko, gospel believers mission, daily bible reading, christian faith, spiritual nourishment">
    <meta name="author" content="Maj Gen (Dr) Ezra Jahadi Jakko (Rtd)">
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">
    <meta name="revisit-after" content="1 days">
    
    <!-- Open Graph Meta Tags for Social Media -->
    <meta property="og:title" content="The Anchor Devotional - Daily Spiritual Nourishment">
    <meta property="og:description" content="Join thousands in daily spiritual growth with biblical wisdom and prayer. Free Christian devotionals delivered daily.">
    <meta property="og:image" content="the anch logo.png">
    <meta property="og:url" content="https://theanchordevotional.com">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="The Anchor Devotional">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="The Anchor Devotional - Daily Spiritual Nourishment">
    <meta name="twitter:description" content="Join thousands in daily spiritual growth with biblical wisdom and prayer. Free Christian devotionals delivered daily.">
    <meta name="twitter:image" content="the anch logo.png">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="https://theanchordevotional.com">
    
    <!-- Additional SEO Meta Tags -->
    <meta name="theme-color" content="#ad3128">
    <meta name="msapplication-TileColor" content="#ad3128">
    <meta name="msapplication-TileImage" content="the anch logo.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=yes">
    <meta name="google-site-verification" content="your-google-verification-code-here">
    
    <!-- Preconnect for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://unpkg.com">
    
    <!-- External Resources -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Structured Data for Google -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "The Anchor Devotional",
      "description": "Daily Christian devotional ministry providing spiritual nourishment through Bible study and prayer",
      "url": "https://theanchordevotional.com",
      "logo": "https://theanchordevotional.com/the anch logo.png",
      "founder": {
        "@type": "Person",
        "name": "Maj Gen (Dr) Ezra Jahadi Jakko (Rtd)",
        "jobTitle": "Pastor/General Overseer",
        "worksFor": "Gospel Believers Mission"
      },
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+234-812-345-6789",
        "contactType": "customer service",
        "email": "info@theanchor.com"
      },
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Abuja",
        "addressCountry": "Nigeria"
      },
      "sameAs": [
        "https://facebook.com/theanchordevotional",
        "https://twitter.com/theanchordevotional",
        "https://instagram.com/theanchordevotional",
        "https://youtube.com/theanchordevotional"
      ]
    }
    </script>
    
    <!-- Article Structured Data for Featured Devotion -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Article",
      "headline": "Surviving the HEAT - Daily Devotional",
      "description": "A powerful devotional about trusting in God during life's difficulties and challenges, based on Jeremiah 17:7-8.",
      "author": {
        "@type": "Person",
        "name": "Maj Gen (Dr) Ezra Jahadi Jakko (Rtd)",
        "jobTitle": "Pastor/General Overseer"
      },
      "publisher": {
        "@type": "Organization",
        "name": "The Anchor Devotional",
        "logo": {
          "@type": "ImageObject",
          "url": "https://theanchordevotional.com/the anch logo.png"
        }
      },
      "datePublished": "2025-06-05",
      "dateModified": "2025-06-05",
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "https://theanchordevotional.com"
      },
      "image": "https://theanchordevotional.com/Untitled design.png"
    }
    </script>
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
            background: linear-gradient(135deg, var(--primary) 0%, #8a2520 100%);
            color: white;
            border-radius: 20px;
            padding: 14px 24px;
            text-align: center;
            font-size: 1rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(173, 49, 40, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
            width: 100%;
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
        }

        .mobile-menu .btn-admin i {
            font-size: 0.9rem;
            transition: transform 0.3s ease;
        }

        .mobile-menu .btn-admin:hover i {
            transform: rotate(-5deg) scale(1.05);
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

        .section-title {
            font-size: 2.5rem;
            margin-bottom: 20px;
            text-align: center;
            color: var(--primary);
            font-family: var(--font-heading);
            font-weight: 700;
        }

        .devotion-section-header,
        .prayer-section-header {
            position: relative;
            z-index: 1;
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

        /* Daily Verse Section Styles */
        .daily-verse {
            background: linear-gradient(135deg, var(--primary) 0%, #8a2520 100%);
            color: white;
            padding: 60px 0;
        }

        .daily-verse-content {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .verse-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 800px;
            width: 100%;
        }

        .verse-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .verse-header h3 {
            font-family: var(--font-heading);
            font-size: 1.5rem;
            margin: 0;
        }

        .verse-date {
            font-size: 0.9rem;
            opacity: 0.8;
            font-weight: 500;
        }

        .verse-text {
            margin-bottom: 25px;
        }

        .verse-text p {
            font-size: 1.3rem;
            line-height: 1.6;
            font-style: italic;
            margin-bottom: 15px;
            font-family: var(--font-heading);
        }

        .verse-text cite {
            font-size: 1rem;
            font-weight: 600;
            opacity: 0.9;
        }

        .verse-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .btn-share-verse, .btn-copy-verse {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-share-verse:hover, .btn-copy-verse:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
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

        .contact-item a {
            color: var(--text);
            text-decoration: none;
            transition: var(--transition);
        }

        .contact-item a:hover {
            color: var(--primary);
            text-decoration: underline;
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
            from { opacity: 0; }
            to { opacity: 1; }
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
                padding: 8px 14px;
                font-size: 0.85rem;
                gap: 6px;
            }
            
            .verse-card {
                padding: 20px;
            }
            
            .verse-header {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            
            .verse-actions {
                justify-content: center;
                flex-wrap: wrap;
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
                    <img src="the anch logo.png" alt="The Anchor Devotional Logo" class="logo-img">
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
                    <li><a href="login.php" class="btn-admin"><i class="fas fa-lock"></i> Admin</a></li>
                </ul>
            </nav>
        </div>
        
        <!-- Mobile Menu -->
        <div class="mobile-menu" id="mobileMenu">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="todays-devotion.php">Today's Devotion</a></li>
                <li><a href="past-devotions.php">Past Devotions</a></li>
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
              <source src="hero-video.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="video-overlay"></div>
        <div class="hero-content">
            <h1>Daily Spiritual Nourishment</h1>
            <p>Anchor your soul in God's Word with our daily devotionals. Each day brings fresh insight, biblical wisdom, and practical application for your spiritual journey.</p>
            <div class="cta-buttons">
                <a href="todays-devotion.php" class="btn btn-white">Today's Devotion</a>
                <a href="#subscribe" class="btn btn-secondary">Subscribe Daily</a>
            </div>
        </div>
    </section>

   <!-- Devotion Content -->
   <section class="section" id="devotion">
        <div class="container">
            <div class="devotion-section-header">
                <h2 class="section-title" style="text-align: center; margin-bottom: 50px; color: var(--primary); font-family: var(--font-heading);">Today's Featured Devotional</h2>
            </div>
            <div class="devotion-page">
                <div class="devotion-main">
                    <div class="devotion-cover">
                    <img src="<?= htmlspecialchars($landing['cover_image'] ?? 'assets/default-cover.jpg') ?>" 
                         alt="Devotional Cover" class="cover-image">

                    <div class="cover-content">
                        <h2 class="cover-topic">
                            <?= htmlspecialchars($landing['featured_topic'] ?? 'No Topic') ?>
                        </h2>
                        <h4 class="cover-subtitle">
                            <?= htmlspecialchars($landing['hero_subtitle'] ?? '') ?>
                        </h4>
                        <p class="cover-date">
                            The Anchor - <?= !empty($landing['featured_date']) ? date("F j, Y", strtotime($landing['featured_date'])) : date("F j, Y") ?>
                        </p>
                    </div>
                </div>

                    <div class="devotion-content">
                        <h3 class="devotion-title">
                        <?= htmlspecialchars($landing['featured_topic'] ?? 'No Title Available') ?>
                        </h3>
                          <div class="devotion-text">
                        <p><?= nl2br(htmlspecialchars($landing['hero_description'] ?? 'No description available.')) ?></p>
                        <p><em><?= nl2br(htmlspecialchars($landing['featured_intro'] ?? '')) ?></em></p>
                    </div>
                        
                        <div class="devotion-actions">
                            <a href="past-devotions.php" class="btn btn-primary">
                                <i class="fas fa-book-open"></i> See More Devotions
                            </a>
                            <div class="social-share">
                                <span>Share this devotion:</span>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=https://theanchordevotional.com" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://twitter.com/intent/tweet?text=Check%20out%20this%20inspiring%20devotion&url=https://theanchordevotional.com" target="_blank" rel="noopener"><i class="fab fa-twitter"></i></a>
                                <a href="https://wa.me/?text=Check%20out%20this%20inspiring%20devotion%20from%20The%20Anchor%20Devotional%20https://theanchordevotional.com" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i></a>
                                <a href="mailto:?subject=Inspiring%20Daily%20Devotion&body=I%20thought%20you%20might%20enjoy%20this%20devotion%20from%20The%20Anchor%20Devotional:%20https://theanchordevotional.com"><i class="fas fa-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <div class="author-column">
                    <div class="author-header">
                        <img src="profile-daddy.png" alt="Author" class="author-avatar">
                        <h3 class="author-name">Maj Gen (Dr) Ezra Jahadi Jakko (Rtd)</h3>
                        <p class="author-title">Pastor/General Overseer<br>Gospel Believers mission</p>
                    </div>
                    
                    <div class="author-bio">
                        <p>Pastor has been writing daily devotionals for years. His insights into Scripture have helped thousands grow in their faith journey.</p>
                    </div>
                    
                    <div class="author-contact">
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:pastor@theanchor.com">pastor@theanchor.com</a>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <a href="tel:+2348123456789">+234 812 345 6789</a>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Abuja, Nigeria</span>
                        </div>
                    </div>
                    
                    <div class="author-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Daily Verse Section -->
 <section class="section daily-verse" id="daily-verse">
    <div class="container">
        <div class="daily-verse-content">
            <div class="verse-card">
                <div class="verse-header">
                    <h3>Verse of the Day</h3>
                    <blockquote>
                    <?php echo htmlspecialchars($landing['featured_date']); ?>

                    </blockquote>
                </div>
                <div class="verse-text">
                    <?php echo htmlspecialchars($landing['verse_of_day']); ?><br>

                    <cite><?php echo htmlspecialchars($landing['verse_reference']); ?></cite>
                </div>
                <div class="verse-actions">
                    <button class="btn-share-verse" onclick="shareVerse()">
                        <i class="fas fa-share-alt"></i> Share Verse
                    </button>
                    <button class="btn-copy-verse" onclick="copyVerse()">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>


    <!-- Prayer Request Section -->
    <section class="section prayer" id="prayer">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Prayer Requests</h2>
            <div class="prayer-form" data-aos="fade-up" data-aos-delay="100">
                <form id="prayerRequestForm" action="submit.php" method="POST">
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
                <div class="footer-column" data-aos="fade-up">
                    <h3>The Anchor</h3>
                    <p>A daily devotional ministry committed to helping believers anchor their faith in God's Word through daily spiritual nourishment.</p>
                    <div class="social-links">
                        <a href="https://facebook.com/theanchordevotional" aria-label="Facebook" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://twitter.com/anchordevotional" aria-label="Twitter" target="_blank" rel="noopener"><i class="fab fa-twitter"></i></a>
                        <a href="https://instagram.com/theanchordevotional" aria-label="Instagram" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a>
                        <a href="https://youtube.com/theanchordevotional" aria-label="YouTube" target="_blank" rel="noopener"><i class="fab fa-youtube"></i></a>
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
            
            <div class="copyright" data-aos="fade-up">
                <p>&copy; 2025 The Anchor Devotional. All Rights Reserved. | <a href="#" style="color: #bbb;">Privacy Policy</a> | <a href="#" style="color: #bbb;">Terms of Use</a></p>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
   <script>
/* ------------------------------
   AOS Animation Init
--------------------------------*/
AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true
});

/* ------------------------------
   Mobile Menu Toggle
--------------------------------*/
const mobileMenuBtn = document.getElementById('mobileMenuBtn');
const mobileMenu = document.getElementById('mobileMenu');

if (mobileMenuBtn && mobileMenu) {
    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('active');
        mobileMenuBtn.innerHTML = mobileMenu.classList.contains('active')
            ? '<i class="fas fa-times"></i>'
            : '<i class="fas fa-bars"></i>';
    });
}

/* ------------------------------
   Header Scroll Effect
--------------------------------*/
window.addEventListener('scroll', () => {
    const header = document.getElementById('header');
    if (!header) return;
    if (window.scrollY > 100) {
        header.classList.add('header-scrolled');
    } else {
        header.classList.remove('header-scrolled');
    }
});

/* ------------------------------
   Prayer Request Form Submission
--------------------------------*/
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
            alert(data); // show server message directly
            this.reset();
        })
        .catch(error => {
            console.error('Submission error:', error);
            alert('There was a problem submitting your request. Please try again.');
        });
});

/* ------------------------------
   Subscribe Form Submission
--------------------------------*/
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


/* ------------------------------
   Fetch & Display Devotionals
--------------------------------*/
async function fetchDevotionals(limit = null, filter = {}) {
    let url = 'api/index.php/devotionals';
    // add query params if needed here

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

    devotionGrid.innerHTML = '';

    if (devotionals.length === 0) {
        devotionGrid.innerHTML = '<p>No devotionals found. Please check back later or adjust your filters.</p>';
        return;
    }

    devotionals.forEach(devotional => {
        const card = document.createElement('div');
        card.className = 'devotion-card';
        card.innerHTML = `
            <img src="${devotional.cover_image_url || 'placeholder.jpg'}" alt="Devotion Cover" class="devotion-image">
            <div class="devotion-content">
                <div class="devotion-date">${new Date(devotional.date).toLocaleDateString()}</div>
                <h3 class="devotion-title">${devotional.title}</h3>
                <p class="devotion-excerpt">${devotional.content.substring(0, 100)}...</p>
                <a href="single-devotion.php?id=${devotional.id}" class="read-more">
                    Read More <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        `;
        devotionGrid.appendChild(card);
    });
}

document.addEventListener('DOMContentLoaded', async () => {
    const devotionals = await fetchDevotionals();
    displayDevotionals(devotionals);

    document.querySelector('.filter-form')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const month = document.getElementById('month').value;
        const year = document.getElementById('year').value;
        // const filtered = await fetchDevotionals(null, { month, year });
        // displayDevotionals(filtered);
        alert('Filtering not fully implemented in this example.');
    });
});

/* ------------------------------
   Daily Verse Share / Copy
--------------------------------*/
function shareVerse() {
    const verseText = document.getElementById('daily-verse-text')?.textContent || '';
    const verseRef = document.getElementById('verse-reference')?.textContent || '';
    const shareText = `${verseText} - ${verseRef}`;

    if (navigator.share) {
        navigator.share({
            title: 'Daily Verse - The Anchor Devotional',
            text: shareText,
            url: window.location.href
        });
    } else {
        const url = `https://twitter.com/intent/tweet?text=${encodeURIComponent(shareText)}&url=${encodeURIComponent(window.location.href)}`;
        window.open(url, '_blank');
    }
}

function copyVerse() {
    const verseText = document.getElementById('daily-verse-text')?.textContent || '';
    const verseRef = document.getElementById('verse-reference')?.textContent || '';
    const fullVerse = `${verseText} - ${verseRef}`;

    navigator.clipboard.writeText(fullVerse).then(() => {
        const copyBtn = document.querySelector('.btn-copy-verse');
        if (!copyBtn) return;
        const originalText = copyBtn.innerHTML;
        copyBtn.innerHTML = '<i class="fas fa-check"></i> Copied!';
        setTimeout(() => {
            copyBtn.innerHTML = originalText;
        }, 2000);
    });
}

/* ------------------------------
   Update Verse Date
--------------------------------*/
function updateVerseDate() {
    const today = new Date();
    const options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    };
    const verseDate = document.getElementById('verse-date');
    if (verseDate) verseDate.textContent = today.toLocaleDateString('en-US', options);
}
updateVerseDate();
</script>

</body>
</html>