<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About The Anchor Devotional - Daily Spiritual Nourishment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        /* Global Variables and Reset */
        :root {
            --primary: #ad3128;
            /* Maroon as primary */
            --secondary: #2c3e50;
            /* Blue for buttons */
            --accent: #2c3e50;
            /* Blue as accent */
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

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate {
            animation: fadeIn 1s ease forwards;
        }

        .animate-up {
            animation: slideUp 0.8s ease forwards;
        }

        /* Utility Classes */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section {
            padding: 100px 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
            font-family: var(--font-heading);
            color: var(--primary);
            position: relative;
            font-size: 2.5rem;
        }

        .section-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: var(--primary);
            margin: 20px auto;
            border-radius: 2px;
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

        .btn-white {
            background-color: white;
            color: var(--primary);
            border: 2px solid white;
        }

        .btn-white:hover {
            background-color: transparent;
            color: white;
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
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
            padding: 15px 0;
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
            transition: var(--transition);
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
            font-size: 1rem;
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
        }

        /* About Page Specific Styles */
        .about-hero {
            position: relative;
            height: 60vh;
            min-height: 400px;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
                url('https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }

        .about-hero-content {
            max-width: 800px;
            padding: 0 20px;
        }

        .about-hero h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            font-family: var(--font-heading);
            line-height: 1.2;
        }

        .about-hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .mission-section {
            background-color: #f8f9fa;
        }

        .mission-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }

        .mission-content p {
            margin-bottom: 30px;
            font-size: 1.1rem;
            line-height: 1.8;
        }

        .team-section {
            background-color: white;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .team-card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .team-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .team-info {
            padding: 25px;
        }

        .team-name {
            font-size: 1.4rem;
            color: var(--primary);
            margin-bottom: 5px;
            font-family: var(--font-heading);
        }

        .team-position {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 15px;
            display: block;
        }

        .team-bio {
            color: var(--text);
            margin-bottom: 20px;
            line-height: 1.7;
        }

        .team-social {
            display: flex;
            gap: 15px;
        }

        .team-social a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            background-color: #f5f5f5;
            border-radius: 50%;
            color: var(--dark);
            transition: var(--transition);
        }

        .team-social a:hover {
            background-color: var(--primary);
            color: white;
        }

        .devotional-section {
            background-color: #f8f9fa;
        }

        .devotional-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .devotional-content p {
            margin-bottom: 25px;
            font-size: 1.1rem;
            line-height: 1.8;
        }

        .family-section {
            background-color: white;
        }

        .family-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
        }

        .family-image {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .family-image img {
            width: 100%;
            height: auto;
            display: block;
            transition: var(--transition);
        }

        .family-image:hover img {
            transform: scale(1.05);
        }

        .family-text h3 {
            font-size: 1.8rem;
            color: var(--primary);
            margin-bottom: 20px;
            font-family: var(--font-heading);
        }

        .family-text p {
            margin-bottom: 20px;
            line-height: 1.8;
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
            .section {
                padding: 80px 0;
            }

            .about-hero h1 {
                font-size: 2.5rem;
            }

            .family-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .family-image {
                order: -1;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                position: fixed;
                top: 80px;
                left: -100%;
                width: 100%;
                height: calc(100vh - 80px);
                background-color: white;
                flex-direction: column;
                align-items: center;
                padding: 40px 0;
                transition: var(--transition);
                box-shadow: var(--shadow);
            }

            .nav-links.active {
                left: 0;
            }

            .nav-links li {
                margin: 15px 0;
            }

            .mobile-menu-btn {
                display: block;
            }

            .logo-text {
                display: none;
            }

            .about-hero {
                height: 50vh;
                min-height: 350px;
            }

            .about-hero h1 {
                font-size: 2rem;
            }

            .about-hero p {
                font-size: 1rem;
            }

            .section-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 576px) {
            .about-hero h1 {
                font-size: 1.8rem;
            }

            .team-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Header with Logo Image -->
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
                    <li><a href="todays-devotion.php">Devotions</a></li>
                    <li><a href="about.php">About</a></li>
                    <!-- <li><a href="prayer.php">Prayer</a></li> -->
                    <li><a href="#subscribe">Subscribe</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- About Hero Section -->
    <section class="about-hero">
        <div class="about-hero-content" data-aos="fade-up">
            <h1>About The Anchor Devotional</h1>
            <p>Discover the story behind our ministry and the team dedicated to bringing you daily spiritual
                nourishment.</p>
            <a href="#mission" class="btn btn-white">Our Mission</a>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="section mission-section" id="mission">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Our Mission & Vision</h2>
            <div class="mission-content" data-aos="fade-up" data-aos-delay="100">
                <p> The Anchor Devotional is a daily devotional inspired by Gods Word, Finding the need for daily
                    connection, and intimacy with God on daily.
                    Here we read, pray and meditate on the
                    Holy Spirit - inspired word of God. We will be focusing on personal growth by
                    continuous devotion each day with God.</p>
                <p>Our vision is to see Christians around the world develop a consistent, meaningful devotional life
                    that transforms their relationship with God and impacts their families, workplaces, and communities.
                    We strive to make biblical truth accessible, practical, and applicable to everyday life.</p>

                <p>Every devotional is carefully crafted to combine sound biblical teaching with real-world application.
                    We don't just want to inform minds—we want to transform hearts and lives through the power of God's
                    Word.</p>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="section team-section" id="team">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Meet Our Team</h2>
            <p style="text-align: center; max-width: 800px; margin: 0 auto 50px;" data-aos="fade-up">Our dedicated team
                of pastors, writers, and editors work tirelessly to bring you fresh, biblical insights every day.</p>

            <div class="team-grid">
                <!-- Team Member 1 -->
                <div class="team-card" data-aos="fade-up" data-aos-delay="100">
                    <img src="avarta.jpg" alt="Maj Gen (Dr) Ezra Jahadi Jakko" class="team-image">
                    <div class="team-info">
                        <h3 class="team-name">Maj Gen (Dr) Ezra Jahadi Jakko</h3>
                        <span class="team-position">Founder & Lead Writer</span>
                        <p class="team-bio">With over 40 years of pastoral experience, Pastor Ezra brings deep biblical
                            insight and practical wisdom to each devotional. He holds a Doctorate in Theology and is
                            passionate about making Scripture accessible to all believers.</p>
                        <div class="team-social">
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Team Member 2 -->
                <div class="team-card" data-aos="fade-up" data-aos-delay="200">
                    <img src="avarta.jpg" alt="Sarah Johnson" class="team-image">
                    <div class="team-info">
                        <h3 class="team-name">Sarah Johnson</h3>
                        <span class="team-position">Editorial Director</span>
                        <p class="team-bio">Sarah oversees the editorial process, ensuring each devotional maintains
                            theological accuracy while being engaging and practical. She holds a Master's in Biblical
                            Studies and has 15 years of publishing experience.</p>
                        <div class="team-social">
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Team Member 3 -->
                <div class="team-card" data-aos="fade-up" data-aos-delay="300">
                    <img src="avarta.jpg" alt="Michael Adebayo" class="team-image">
                    <div class="team-info">
                        <h3 class="team-name">Michael Adebayo</h3>
                        <span class="team-position">Digital Director</span>
                        <p class="team-bio">Michael manages our digital platforms, ensuring the devotional reaches
                            readers through our website, app, and email. With a background in both theology and
                            technology, he bridges the gap between faith and digital media.</p>
                        <div class="team-social">
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Devotional Content Section -->
    <section class="section devotional-section">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Our Devotional Approach</h2>
            <div class="devotional-content" data-aos="fade-up" data-aos-delay="100">
                <p>Each devotional begins with prayerful study of Scripture. We believe the Bible is God's living Word,
                    relevant to every generation and culture. Our writers spend time meditating on passages, seeking to
                    understand both the original context and contemporary application.</p>

                <p>We emphasize the importance of connecting biblical truth to daily life. Our devotionals aren't just
                    theological treatises—they're practical guides for living out your faith in the workplace, at home,
                    and in your community. Each entry includes reflection questions to help you apply what you've read.
                </p>

                <p>The Anchor Devotional covers the full breadth of Scripture, from Genesis to Revelation. We follow the
                    church calendar, addressing seasonal themes while maintaining a balanced diet of biblical teaching.
                    Whether you're a new believer or a mature Christian, our devotionals meet you where you are and
                    challenge you to grow deeper in your faith.</p>
            </div>
        </div>
    </section>

    <!-- Family Space Section -->
    <section class="section family-section">
        <div class="container">
            <div class="family-content">
                <div class="family-text" data-aos="fade-right">
                    <h3>A Ministry for the Whole Family</h3>
                    <p>The Anchor Devotional isn't just for individuals—it's designed to strengthen families in their
                        faith journey. Many of our readers use the devotionals as discussion starters during family
                        meals or bedtime routines.</p>
                    <p>We offer special family editions during holidays and school breaks, with age-appropriate
                        questions and activities that help children engage with Scripture. Our goal is to equip parents
                        to be the primary spiritual influencers in their children's lives.</p>
                    <p>Looking ahead, we're developing resources specifically for couples, parents, and
                        multi-generational households. Because faith isn't meant to be lived in isolation—it flourishes
                        in community, especially within the family.</p>
                    <a href="#" class="btn btn-primary">Family Resources</a>
                </div>
                <div class="family-image" data-aos="fade-left" data-aos-delay="100">
                    <img src="https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                        alt="Family reading Bible together">
                </div>
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
        document.getElementById('subscribeForm').addEventListener('submit', function (e) {
            e.preventDefault();
            alert('Thank you for subscribing to The Anchor Devotional! You will receive your first devotional tomorrow morning.');
            this.reset();
        });


    </script>
</body>

</html>