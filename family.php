<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Family Resources - The Anchor Devotional</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
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

        /* Header Styles */
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

        /* Hero Section */
        .resources-hero {
            position: relative;
            height: 400px;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
                url('https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-top: 80px;
        }

        .resources-hero-content h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            font-family: var(--font-heading);
        }

        .resources-hero-content p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 30px;
        }

        /* Button Styles */
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

        /* Resources Section */
        .resources-container {
            padding: 80px 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
            font-size: 2.5rem;
            color: var(--primary);
            font-family: var(--font-heading);
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: var(--primary);
        }

        /* Category Navigation */
        .category-nav {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .category-tab {
            padding: 12px 25px;
            margin: 0 5px 10px;
            background-color: var(--light);
            border-radius: 5px;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 1px;
        }

        .category-tab.active {
            background-color: var(--primary);
            color: white;
        }

        .category-tab:hover:not(.active) {
            background-color: #e9ecef;
        }

        /* Category Sections */
        .category-section {
            margin-bottom: 60px;
        }

        .category-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--light);
        }

        .category-icon {
            width: 40px;
            height: 40px;
            background-color: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.2rem;
        }

        .category-title {
            font-size: 1.8rem;
            color: var(--secondary);
            font-family: var(--font-heading);
        }

        /* Resources Grid */
        .resources-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }

        .resource-card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .resource-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .resource-image {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }

        .resource-content {
            padding: 20px;
        }

        .resource-category {
            display: inline-block;
            padding: 5px 10px;
            background-color: var(--light);
            color: var(--primary);
            border-radius: 50px;
            font-size: 0.8rem;
            margin-bottom: 10px;
        }

        .resource-title {
            font-size: 1.3rem;
            margin-bottom: 10px;
            font-family: var(--font-heading);
            color: var(--secondary);
        }

        .resource-description {
            color: var(--text-light);
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .resource-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
            color: var(--text-light);
        }

        .download-btn {
            background-color: var(--primary);
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
            font-size: 0.9rem;
        }

        .download-btn:hover {
            background-color: #8a2520;
        }

        /* Search */
        .search-container {
            max-width: 600px;
            margin: 0 auto 40px;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 15px 20px;
            border-radius: 50px;
            border: 1px solid #ddd;
            font-size: 1rem;
            padding-right: 50px;
        }

        .search-btn {
            position: absolute;
            right: 5px;
            top: 5px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
        }

        /* Subscribe Section */
        .subscribe {
            background-color: var(--primary);
            color: white;
            text-align: center;
            padding: 80px 0;
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

        /* Responsive Design */
        @media (max-width: 992px) {
            .resources-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }

            .resources-hero-content h1 {
                font-size: 2.5rem;
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

            .resources-hero-content h1 {
                font-size: 2rem;
            }

            .resources-hero-content p {
                font-size: 1rem;
            }

            .category-nav {
                flex-direction: column;
                align-items: center;
            }

            .category-tab {
                width: 100%;
                text-align: center;
                margin: 5px 0;
            }

            .resources-grid {
                grid-template-columns: 1fr;
            }

            .btn-admin {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 576px) {
            .resources-hero-content h1 {
                font-size: 1.8rem;
            }

            .section-title {
                font-size: 2rem;
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
                    <li><a href="family.php" class="active">Family</a></li>
                    <li><a href="#subscribe">Subscribe</a></li>
                    <li><a href="dashboad.php" class="btn-admin"><i class="fas fa-lock"></i> Admin</a></li>
                </ul>
            </nav>
        </div>

        <!-- Mobile Menu -->
        <div class="mobile-menu" id="mobileMenu">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="todays-devotion.php">Devotions</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="family.php" class="active">Family</a></li>
                <li><a href="#subscribe">Subscribe</a></li>
                <li><a href="dashboad.php" class="btn-admin"><i class="fas fa-lock"></i> Admin Login</a></li>
            </ul>
        </div>
    </header>

    <!-- Resources Hero Section -->
    <section class="resources-hero">
        <div class="resources-hero-content">
            <h1>Family & Life Stage Resources</h1>
            <p>Discover helpful resources tailored for every stage of life - from children to teens, singles to married
                couples.</p>
            <a href="#resources" class="btn btn-white">Explore Resources</a>
        </div>
    </section>

    <!-- Resources Section -->
    <section class="resources-container" id="resources">
        <div class="container">
            <h2 class="section-title">Spiritual Resources for All Ages</h2>

            <div class="search-container">
                <input type="text" class="search-input" placeholder="Search resources...">
                <button class="search-btn"><i class="fas fa-search"></i></button>
            </div>

            <div class="category-nav">
                <div class="category-tab active" data-category="all">All Resources</div>
                <div class="category-tab" data-category="children">Children</div>
                <div class="category-tab" data-category="teens">Teens</div>
                <div class="category-tab" data-category="singles">Singles</div>
                <div class="category-tab" data-category="married">Married</div>
                <div class="category-tab" data-category="parents">Parents</div>
            </div>

            <!-- Children's Resources -->
            <div class="category-section" id="children">
                <div class="category-header">
                    <div class="category-icon">
                        <i class="fas fa-child"></i>
                    </div>
                    <h3 class="category-title">Children's Resources (Ages 5-12)</h3>
                </div>

                <div class="resources-grid">
                    <div class="resource-card" data-category="children">
                        <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                            alt="Bible Coloring Pages" class="resource-image">
                        <div class="resource-content">
                            <span class="resource-category">Children</span>
                            <h3 class="resource-title">Bible Story Coloring Book</h3>
                            <p class="resource-description">25 coloring pages featuring key Bible stories with simple
                                explanations for young children.</p>
                            <div class="resource-meta">
                                <span>PDF • 5.1 MB</span>
                                <a href="#" class="download-btn"><i class="fas fa-download"></i> Download</a>
                            </div>
                        </div>
                    </div>

                    <div class="resource-card" data-category="children">
                        <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                            alt="Sunday School" class="resource-image">
                        <div class="resource-content">
                            <span class="resource-category">Children</span>
                            <h3 class="resource-title">Fruit of the Spirit Activities</h3>
                            <p class="resource-description">Games, crafts and lessons to help children understand and
                                practice the Fruit of the Spirit.</p>
                            <div class="resource-meta">
                                <span>PDF • 2.9 MB</span>
                                <a href="#" class="download-btn"><i class="fas fa-download"></i> Download</a>
                            </div>
                        </div>
                    </div>

                    <div class="resource-card" data-category="children">
                        <img src="https://images.unsplash.com/photo-1585771724684-38269d6639fd?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                            alt="Memory Verse Cards" class="resource-image">
                        <div class="resource-content">
                            <span class="resource-category">Children</span>
                            <h3 class="resource-title">Memory Verse Cards</h3>
                            <p class="resource-description">52 printable memory verse cards - one for each week of the
                                year. Perfect for family memorization.</p>
                            <div class="resource-meta">
                                <span>PDF • 1.5 MB</span>
                                <a href="#" class="download-btn"><i class="fas fa-download"></i> Download</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teen Resources -->
            <div class="category-section" id="teens">
                <div class="category-header">
                    <div class="category-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3 class="category-title">Teen Resources (Ages 13-19)</h3>
                </div>

                <div class="resources-grid">
                    <div class="resource-card" data-category="teens">
                        <img src="https://images.unsplash.com/photo-1518655048521-f130df041f66?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                            alt="Prayer Journal" class="resource-image">
                        <div class="resource-content">
                            <span class="resource-category">Teens</span>
                            <h3 class="resource-title">Teen Prayer Journal</h3>
                            <p class="resource-description">Guided prayer journal template designed specifically for
                                teenagers to grow their prayer life.</p>
                            <div class="resource-meta">
                                <span>PDF • 1.1 MB</span>
                                <a href="#" class="download-btn"><i class="fas fa-download"></i> Download</a>
                            </div>
                        </div>
                    </div>

                    <div class="resource-card" data-category="teens">
                        <img src="https://images.unsplash.com/photo-1549060279-7e168fcee0c2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                            alt="Bible Study" class="resource-image">
                        <div class="resource-content">
                            <span class="resource-category">Teens</span>
                            <h3 class="resource-title">30-Day Teen Devotional</h3>
                            <p class="resource-description">Daily readings addressing issues teens face today with
                                biblical wisdom and practical application.</p>
                            <div class="resource-meta">
                                <span>PDF • 2.4 MB</span>
                                <a href="#" class="download-btn"><i class="fas fa-download"></i> Download</a>
                            </div>
                        </div>
                    </div>

                    <div class="resource-card" data-category="teens">
                        <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                            alt="Bible Study" class="resource-image">
                        <div class="resource-content">
                            <span class="resource-category">Teens</span>
                            <h3 class="resource-title">Social Media & Faith Guide</h3>
                            <p class="resource-description">Biblical perspective on using social media wisely as a
                                Christian teen.</p>
                            <div class="resource-meta">
                                <span>PDF • 1.7 MB</span>
                                <a href="#" class="download-btn"><i class="fas fa-download"></i> Download</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Singles Resources -->
            <div class="category-section" id="singles">
                <div class="category-header">
                    <div class="category-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3 class="category-title">Resources for Singles</h3>
                </div>

                <div class="resources-grid">
                    <div class="resource-card" data-category="singles">
                        <img src="https://images.unsplash.com/photo-1544717305-2782549b5136?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                            alt="Single Life" class="resource-image">
                        <div class="resource-content">
                            <span class="resource-category">Singles</span>
                            <h3 class="resource-title">Thriving in Singleness</h3>
                            <p class="resource-description">Biblical perspective on making the most of your single years
                                for God's glory.</p>
                            <div class="resource-meta">
                                <span>PDF • 3.2 MB</span>
                                <a href="#" class="download-btn"><i class="fas fa-download"></i> Download</a>
                            </div>
                        </div>
                    </div>

                    <div class="resource-card" data-category="singles">
                        <img src="https://images.unsplash.com/photo-1516589178581-6cd7833ae3b2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                            alt="Prayer" class="resource-image">
                        <div class="resource-content">
                            <span class="resource-category">Singles</span>
                            <h3 class="resource-title">Prayer Guide for Singles</h3>
                            <p class="resource-description">30-day prayer guide covering specific needs and challenges
                                faced by singles.</p>
                            <div class="resource-meta">
                                <span>PDF • 1.8 MB</span>
                                <a href="#" class="download-btn"><i class="fas fa-download"></i> Download</a>
                            </div>
                        </div>
                    </div>

                    <div class="resource-card" data-category="singles">
                        <img src="https://images.unsplash.com/photo-1549060279-7e168fcee0c2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                            alt="Bible Study" class="resource-image">
                        <div class="resource-content">
                            <span class="resource-category">Singles</span>
                            <h3 class="resource-title">Dating with Purpose</h3>
                            <p class="resource-description">Biblical principles for Christian dating and relationships.
                            </p>
                            <div class="resource-meta">
                                <span>PDF • 2.1 MB</span>
                                <a href="#" class="download-btn"><i class="fas fa-download"></i> Download</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Married Resources -->
            <div class="category-section" id="married">
                <div class="category-header">
                    <div class="category-icon">
                        <i class="fas fa-ring"></i>
                    </div>
                    <h3 class="category-title">Resources for Married Couples</h3>
                </div>

                <div class="resources-grid">
                    <div class="resource-card" data-category="married">
                        <img src="https://images.unsplash.com/photo-1516589178581-6cd7833ae3b2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                            alt="Couple Praying" class="resource-image">
                        <div class="resource-content">
                            <span class="resource-category">Married</span>
                            <h3 class="resource-title">Praying Together Guide</h3>
                            <p class="resource-description">Practical guide to help couples establish and maintain a
                                prayer life together.</p>
                            <div class="resource-meta">
                                <span>PDF • 1.8 MB</span>
                                <a href="#" class="download-btn"><i class="fas fa-download"></i> Download</a>
                            </div>
                        </div>
                    </div>

                    <div class="resource-card" data-category="married">
                        <img src="https://images.unsplash.com/photo-1529257414772-1960b7bea4eb?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                            alt="Marriage" class="resource-image">
                        <div class="resource-content">
                            <span class="resource-category">Married</span>
                            <h3 class="resource-title">Biblical Marriage Principles</h3>
                            <p class="resource-description">Foundational biblical teachings on marriage roles,
                                communication, and conflict resolution.</p>
                            <div class="resource-meta">
                                <span>PDF • 2.5 MB</span>
                                <a href="#" class="download-btn"><i class="fas fa-download"></i> Download</a>
                            </div>
                        </div>
                    </div>

                    <div class="resource-card" data-category="married">
                        <img src="https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                            alt="Date Night" class="resource-image">
                        <div class="resource-content">
                            <span class="resource-category">Married</span>
                            <h3 class="resource-title">52 Date Night Ideas</h3>
                            <p class="resource-description">Creative, budget-friendly date ideas to keep your marriage
                                strong throughout the year.</p>
                            <div class="resource-meta">
                                <span>PDF • 1.3 MB</span>
                                <a href="#" class="download-btn"><i class="fas fa-download"></i> Download</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Parents Resources -->
            <div class="category-section" id="parents">
                <div class="category-header">
                    <div class="category-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="category-title">Parenting Resources</h3>
                </div>

                <div class="resources-grid">
                    <div class="resource-card" data-category="parents">
                        <img src="https://images.unsplash.com/photo-1544717305-2782549b5136?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                            alt="Parent and Child" class="resource-image">
                        <div class="resource-content">
                            <span class="resource-category">Parents</span>
                            <h3 class="resource-title">Raising Godly Children</h3>
                            <p class="resource-description">Biblical principles for parenting at different stages of
                                childhood and adolescence.</p>
                            <div class="resource-meta">
                                <span>PDF • 3.2 MB</span>
                                <a href="#" class="download-btn"><i class="fas fa-download"></i> Download</a>
                            </div>
                        </div>
                    </div>

                    <div class="resource-card" data-category="parents">
                        <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                            alt="Family Devotional" class="resource-image">
                        <div class="resource-content">
                            <span class="resource-category">Parents</span>
                            <h3 class="resource-title">Family Devotional Guide</h3>
                            <p class="resource-description">Practical guide to establishing regular family devotions
                                with children of all ages.</p>
                            <div class="resource-meta">
                                <span>PDF • 2.7 MB</span>
                                <a href="#" class="download-btn"><i class="fas fa-download"></i> Download</a>
                            </div>
                        </div>
                    </div>

                    <div class="resource-card" data-category="parents">
                        <img src="https://images.unsplash.com/photo-1549060279-7e168fcee0c2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                            alt="Family Bible Study" class="resource-image">
                        <div class="resource-content">
                            <span class="resource-category">Parents</span>
                            <h3 class="resource-title">30-Day Family Devotional</h3>
                            <p class="resource-description">A month of daily devotionals designed for families with
                                children of all ages.</p>
                            <div class="resource-meta">
                                <span>PDF • 2.4 MB</span>
                                <a href="#" class="download-btn"><i class="fas fa-download"></i> Download</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Subscribe Section -->
    <section class="subscribe" id="subscribe">
        <div class="container">
            <h2 class="section-title" style="color: white;">Stay Connected</h2>
            <p
                style="text-align: center; margin-bottom: 30px; max-width: 700px; margin-left: auto; margin-right: auto;">
                Receive updates when new family resources are added. Join our community growing together in faith.
            </p>
            <form class="subscribe-form" id="subscribeForm">
                <input type="email" class="subscribe-input" placeholder="Your email address" required>
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
                        <li><a href="family.php">Family Resources</a></li>
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

        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');
            mobileMenuBtn.innerHTML = mobileMenu.classList.contains('active') ?
                '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
        });

        // Header scroll effect
        window.addEventListener('scroll', () => {
            const header = document.getElementById('header');
            if (window.scrollY > 100) {
                header.classList.add('header-scrolled');
            } else {
                header.classList.remove('header-scrolled');
            }
        });

        // Form submission
        document.getElementById('subscribeForm').addEventListener('submit', function (e) {
            e.preventDefault();
            alert('Thank you for subscribing to family resources updates!');
            this.reset();
        });

        // Category filtering
        const categoryTabs = document.querySelectorAll('.category-tab');
        const categorySections = document.querySelectorAll('.category-section');

        categoryTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Update active tab
                categoryTabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                const category = tab.dataset.category;

                // Show/hide sections
                if (category === 'all') {
                    categorySections.forEach(section => {
                        section.style.display = 'block';
                    });
                } else {
                    categorySections.forEach(section => {
                        section.style.display = 'none';
                    });
                    document.getElementById(category).style.display = 'block';
                }

                // Scroll to section
                if (category !== 'all') {
                    document.getElementById(category).scrollIntoView({
                        behavior: 'smooth'
                    });
                } else {
                    window.scrollTo({
                        top: document.getElementById('resources').offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Simple search functionality
        const searchInput = document.querySelector('.search-input');
        const resourceCards = document.querySelectorAll('.resource-card');

        searchInput.addEventListener('input', () => {
            const searchTerm = searchInput.value.toLowerCase();

            resourceCards.forEach(card => {
                const title = card.querySelector('.resource-title').textContent.toLowerCase();
                const description = card.querySelector('.resource-description').textContent.toLowerCase();

                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>