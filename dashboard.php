<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="anchor-logo.png">
    <link rel="shortcut icon" type="image/png" href="anchor-logo.png">
    <link rel="apple-touch-icon" href="anchor-logo.png">
    
    <title>The Anchor Devotional - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Anchor Authentication System -->
    <script src="anchor-auth.js"></script>
    <!-- Real-time Website Updater -->
    <script src="website-updater.js"></script>
    <style>
        :root {
            --primary: #ad3128;
            --secondary: #2c3e50;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --sidebar-width: 280px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #f5f7fa;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--secondary) 0%, #34495e 100%);
            color: white;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 25px 20px;
            background: rgba(0, 0, 0, 0.2);
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .sidebar-header h3 {
            margin-bottom: 5px;
            font-weight: 600;
            font-size: 1.3rem;
        }

        .sidebar-header p {
            font-size: 0.85rem;
            opacity: 0.8;
            margin: 0;
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .sidebar-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-nav li {
            margin-bottom: 5px;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 12px 25px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: var(--primary);
            transform: translateX(5px);
        }

        .sidebar-nav i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .top-nav {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--primary);
            cursor: pointer;
            padding: 8px;
            border-radius: 4px;
            transition: all 0.3s ease;
            margin-right: 10px;
        }

        .mobile-menu-btn:hover {
            background: #f8f9fa;
            color: var(--dark);
        }

        .breadcrumb {
            margin: 0;
            background: transparent;
            font-size: 0.9rem;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-actions .btn {
            border-radius: 20px;
            padding: 5px 12px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .user-actions .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .content-area {
            padding: 30px;
        }

        .page-section {
            display: none;
        }

        .page-section.active {
            display: block;
        }

        .section-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
        }

        .section-title {
            color: var(--primary);
            margin-bottom: 20px;
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(173, 49, 40, 0.25);
        }

        .btn {
            border-radius: 8px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, #8a2520 100%);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #8a2520 0%, var(--primary) 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(173, 49, 40, 0.3);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-warning {
            background: var(--warning);
            color: var(--dark);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .image-preview {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            margin-top: 10px;
            border: 2px solid #e9ecef;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 1.5rem;
            color: white;
        }

        .stat-icon.primary { background: var(--primary); }
        .stat-icon.success { background: var(--success); }
        .stat-icon.warning { background: var(--warning); }
        .stat-icon.info { background: #17a2b8; }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .devotions-list {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        .devotion-item {
            padding: 15px 20px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.3s ease;
        }

        .devotion-item:hover {
            background-color: #f8f9fa;
        }

        .devotion-item:last-child {
            border-bottom: none;
        }

        .devotion-info h6 {
            margin: 0 0 5px 0;
            color: var(--primary);
        }

        .devotion-info small {
            color: #6c757d;
        }

        .devotion-actions {
            display: flex;
            gap: 10px;
        }

        .btn-sm {
            padding: 5px 12px;
            font-size: 0.8rem;
        }

        .alert {
            border-radius: 8px;
            border: none;
            padding: 15px 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .file-upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            background: #f8f9fa;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .file-upload-area:hover {
            border-color: var(--primary);
            background: rgba(173, 49, 40, 0.05);
        }

        .file-upload-area.dragover {
            border-color: var(--primary);
            background: rgba(173, 49, 40, 0.1);
        }

        .resources-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .resource-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
            transition: transform 0.3s ease;
        }

        .resource-card:hover {
            transform: translateY(-5px);
        }

        .resource-thumbnail {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .resource-info {
            padding: 15px;
        }

        .resource-name {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .resource-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        /* Rich Text Editor Styles */
        .rich-editor-toolbar {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-bottom: none;
            border-radius: 8px 8px 0 0;
            padding: 10px;
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            align-items: center;
        }

        .toolbar-group {
            display: flex;
            align-items: center;
            gap: 5px;
            padding-right: 15px;
            border-right: 1px solid #dee2e6;
            margin-right: 10px;
        }

        .toolbar-group:last-child {
            border-right: none;
            margin-right: 0;
            padding-right: 0;
        }

        .toolbar-btn {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 6px 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 14px;
            color: #495057;
        }

        .toolbar-btn:hover {
            background: #e9ecef;
            border-color: #adb5bd;
        }

        .toolbar-btn.active {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .toolbar-select {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 6px 8px;
            cursor: pointer;
            font-size: 14px;
            min-width: 80px;
        }

        .toolbar-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(173, 49, 40, 0.25);
        }

        .color-picker {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            width: 32px;
            height: 32px;
            padding: 0;
            cursor: pointer;
            background: none;
        }

        .rich-editor {
            border: 1px solid #dee2e6;
            border-radius: 0 0 8px 8px;
            min-height: 300px;
            padding: 15px;
            font-family: 'Georgia', serif;
            font-size: 14px;
            line-height: 1.6;
            background: white;
            overflow-y: auto;
            max-height: 500px;
        }

        .rich-editor:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(173, 49, 40, 0.25);
        }

        .rich-editor[placeholder]:empty:before {
            content: attr(placeholder);
            color: #6c757d;
            font-style: italic;
        }

        .rich-editor p {
            margin: 0 0 1em 0;
        }

        .rich-editor h1, .rich-editor h2, .rich-editor h3 {
            color: var(--primary);
            margin: 1em 0 0.5em 0;
        }

        .rich-editor blockquote {
            border-left: 4px solid var(--primary);
            margin: 1em 0;
            padding-left: 1em;
            font-style: italic;
            background: rgba(173, 49, 40, 0.05);
        }

        .rich-editor ul, .rich-editor ol {
            margin: 0.5em 0;
            padding-left: 2em;
        }

        .rich-editor li {
            margin: 0.25em 0;
        }

        /* Loading and Animation States */
        .page-section {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
        }

        .page-section.active {
            opacity: 1;
            transform: translateY(0);
        }

        .sidebar {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .overlay {
            transition: opacity 0.3s ease;
        }

        /* Focus improvements for accessibility */
        .btn:focus, .form-control:focus, .toolbar-btn:focus {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        /* Touch-friendly improvements */
        .btn, .form-control, .toolbar-btn {
            -webkit-tap-highlight-color: transparent;
            touch-action: manipulation;
        }

        /* Smooth scrolling for iOS */
        .content-area {
            -webkit-overflow-scrolling: touch;
        }

        /* Better touch targets */
        @media (max-width: 768px) {
            .nav-link, .btn, .toolbar-btn {
                min-height: 44px;
                min-width: 44px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .sidebar .nav-link {
                padding: 15px 20px;
                min-height: 48px;
            }
        }

        /* Responsive Design - Consistent with Landing Page */
        @media (max-width: 992px) {
            .container {
                max-width: 100%;
                padding: 0 20px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
            
            .card {
                margin-bottom: 20px;
            }
            
            .rich-text-editor {
                min-height: 250px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 280px;
                z-index: 1000;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                background: white;
                box-shadow: 0 0 20px rgba(0,0,0,0.1);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .header {
                position: relative;
                z-index: 999;
            }

            .mobile-menu-btn {
                display: block;
                background: none;
                border: none;
                font-size: 1.5rem;
                color: var(--primary);
                cursor: pointer;
                margin-right: 15px;
            }

            .content-area {
                padding: 15px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .devotion-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
                padding: 15px;
            }

            .devotion-actions {
                align-self: flex-end;
                flex-direction: column;
                gap: 8px;
                width: 100%;
            }

            .devotion-actions .btn {
                width: 100%;
                text-align: center;
            }

            .form-row {
                flex-direction: column;
                gap: 15px;
            }

            .toolbar-group {
                flex-wrap: wrap;
                gap: 5px;
            }

            .toolbar-btn {
                min-width: 35px;
                height: 35px;
                font-size: 0.9rem;
            }

            .rich-text-editor {
                min-height: 200px;
                font-size: 16px; /* Prevent zoom on iOS */
            }

            .modal-dialog {
                margin: 10px;
            }

            .btn {
                padding: 12px 20px;
                font-size: 1rem;
            }

            .card-title {
                font-size: 1.25rem;
            }

            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 999;
                display: none;
            }

            .overlay.active {
                display: block;
            }
        }

        @media (max-width: 576px) {
            .header h1 {
                font-size: 1.5rem;
            }

            .stats-card h3 {
                font-size: 1.8rem;
            }

            .stats-card p {
                font-size: 0.9rem;
            }

            .card {
                border-radius: 8px;
                margin-bottom: 15px;
            }

            .card-body {
                padding: 15px;
            }

            .devotion-item {
                padding: 12px;
            }

            .toolbar-group {
                gap: 3px;
            }

            .toolbar-btn {
                min-width: 32px;
                height: 32px;
                font-size: 0.8rem;
                padding: 4px;
            }

            .toolbar-select {
                padding: 4px 6px;
                font-size: 0.85rem;
            }

            .color-picker {
                width: 32px;
                height: 32px;
            }

            .rich-text-editor {
                min-height: 180px;
                font-size: 16px;
                padding: 12px;
            }

            .btn {
                padding: 10px 16px;
                font-size: 0.9rem;
            }

            .btn-sm {
                padding: 6px 12px;
                font-size: 0.8rem;
            }

            .form-control {
                font-size: 16px; /* Prevent zoom on iOS */
            }

            .modal-content {
                margin: 5px;
            }

            .sidebar {
                width: 260px;
            }

            .sidebar-nav a {
                padding: 12px 20px;
                font-size: 0.95rem;
            }

            .file-upload-area {
                padding: 20px;
                font-size: 0.9rem;
            }

            .alert {
                padding: 12px 15px;
                font-size: 0.9rem;
            }
        }

        /* Extra small screens */
        @media (max-width: 320px) {
            .sidebar {
                width: 240px;
            }

            .toolbar-btn {
                min-width: 28px;
                height: 28px;
                font-size: 0.75rem;
            }

            .rich-text-editor {
                min-height: 160px;
                padding: 10px;
            }

            .content-area {
                padding: 10px;
            }
        }

        /* Real-time Website Update Styles */
        .form-actions {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .form-actions button {
            position: relative;
            overflow: hidden;
        }

        .form-actions button:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .form-actions button:hover:before {
            left: 100%;
        }

        .live-update-indicator {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #28a745;
            font-size: 0.9rem;
        }

        .live-update-indicator::before {
            content: '';
            width: 8px;
            height: 8px;
            background: #28a745;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.2); }
            100% { opacity: 1; transform: scale(1); }
        }

        .update-status {
            position: fixed;
            bottom: 20px;
            right: 20px;
            max-width: 300px;
            z-index: 1000;
        }

        .preview-button {
            border: 2px dashed #6c757d;
            background: transparent;
            color: #6c757d;
        }

        .preview-button:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: rgba(173, 49, 40, 0.1);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="theachor.png" alt="Logo" class="sidebar-logo">
            <h3>The Anchor</h3>
            <p>Admin Dashboard</p>
        </div>
        
        <nav class="sidebar-nav">
            <ul>
                <li>
                    <a href="#" class="nav-link active" data-page="dashboard">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link" data-page="landing-page">
                        <i class="fas fa-home"></i> Landing Page
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link" data-page="todays-devotion">
                        <i class="fas fa-book-open"></i> Today's Devotion
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link" data-page="family-resources">
                        <i class="fas fa-users"></i> Family Resources
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link" data-page="past-devotions">
                        <i class="fas fa-history"></i> Past Devotions
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link" data-page="prayer-requests">
                        <i class="fas fa-pray"></i> Prayer Requests
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link" data-page="settings">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </li>
                <li>
                    <a href="index.html">
                        <i class="fas fa-sign-out-alt"></i> Back to Website
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Mobile Overlay -->
    <div class="overlay" id="mobileOverlay"></div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-nav">
            <div class="d-flex align-items-center">
                <button class="mobile-menu-btn d-md-none" id="mobileMenuBtn">
                    <i class="fas fa-bars"></i>
                </button>
                <nav aria-label="breadcrumb" class="flex-grow-1">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item active" id="breadcrumb-text">Dashboard</li>
                    </ol>
                </nav>
            </div>
            <div class="user-menu">
                <span id="user-welcome">Welcome, Pastor Ezra</span>
                <div class="user-actions">
                    <button class="btn btn-sm btn-outline-danger" onclick="anchorAuth.logout()" title="Logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                    <button class="btn btn-sm btn-outline-primary d-md-none" id="sidebarToggle" style="display: none;">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="content-area">
            <!-- Dashboard Overview -->
            <div class="page-section active" id="dashboard">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon primary">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="stat-number" id="total-devotions">0</div>
                        <div class="stat-label">Total Devotions</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon success">
                            <i class="fas fa-pray"></i>
                        </div>
                        <div class="stat-number" id="total-prayers">0</div>
                        <div class="stat-label">Prayer Requests</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon warning">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-number" id="total-resources">0</div>
                        <div class="stat-label">Family Resources</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon info">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="stat-number" id="days-active">365</div>
                        <div class="stat-label">Days Active</div>
                    </div>
                </div>

                <div class="section-card">
                    <h4 class="section-title">
                        <i class="fas fa-chart-line"></i>
                        Recent Activity
                    </h4>
                    <div class="devotions-list" id="recent-activity">
                        <!-- Recent activity will be populated here -->
                    </div>
                </div>
            </div>

            <!-- Landing Page Management -->
            <div class="page-section" id="landing-page">
                <div class="section-card">
                    <h4 class="section-title">
                        <i class="fas fa-home"></i>
                        Landing Page Content
                    </h4>
                    <form id="homepage-form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hero-title">Main Hero Title</label>
                                    <input type="text" class="form-control" id="hero-title" name="hero-title" placeholder="Daily Spiritual Nourishment" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hero-subtitle">Hero Subtitle</label>
                                    <input type="text" class="form-control" id="hero-subtitle" name="hero-subtitle" placeholder="Anchor your soul in God's Word" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="hero-description">Hero Description</label>
                            <textarea class="form-control" id="hero-description" name="hero-description" rows="3" placeholder="Describe your daily devotional ministry..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="cover-image">Cover Image</label>
                            <input type="file" class="form-control" id="cover-image" accept="image/*">
                            <img id="cover-preview" class="image-preview" style="display:none;">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="featured-topic">Featured Topic</label>
                                    <input type="text" class="form-control" id="featured-topic" placeholder="Surviving the HEAT" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="featured-date">Publication Date</label>
                                    <input type="date" class="form-control" id="featured-date" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="featured-intro">Featured Devotion Intro</label>
                            <textarea class="form-control" id="featured-intro" rows="4" placeholder="Brief introduction to today's devotion..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="verse-of-day">Verse of the Day</label>
                            <textarea class="form-control" id="verse-of-day" rows="3" placeholder="Enter today's featured Bible verse..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="verse-reference">Verse Reference</label>
                            <input type="text" class="form-control" id="verse-reference" placeholder="John 3:16 (NIV)" required>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-home"></i> Update Live Homepage
                            </button>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> 
                                This will immediately update the homepage hero section
                            </small>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Today's Devotion Management -->
            <div class="page-section" id="todays-devotion">
                <div class="section-card">
                    <h4 class="section-title">
                        <i class="fas fa-book-open"></i>
                        Today's Devotion Entry
                    </h4>
                    <form id="devotion-form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="devotion-topic">Topic/Title</label>
                                    <input type="text" class="form-control" id="devotion-topic" placeholder="Enter devotion topic" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="devotion-date">Date</label>
                                    <input type="date" class="form-control" id="devotion-date" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="devotion-image">Devotion Image</label>
                            <input type="file" class="form-control" id="devotion-image" accept="image/*">
                            <img id="devotion-preview" class="image-preview" style="display:none;">
                        </div>

                        <div class="form-group">
                            <label for="devotion-verse">Scripture Verse</label>
                            <textarea class="form-control" id="devotion-verse" rows="3" placeholder="Enter the main Bible verse for this devotion..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="verse-reference">Scripture Reference</label>
                            <input type="text" class="form-control" id="verse-reference" placeholder="e.g., John 3:16, Psalm 23:1" required>
                        </div>

                        <div class="form-group">
                            <label for="devotion-intro">Devotion Introduction</label>
                            <textarea class="form-control" id="devotion-intro" rows="4" placeholder="Write a compelling introduction..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="devotion-content">Full Devotion Content</label>
                            <div class="rich-editor-toolbar" id="editor-toolbar">
                                <div class="toolbar-group">
                                    <button type="button" class="toolbar-btn" data-command="bold" title="Bold">
                                        <i class="fas fa-bold"></i>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-command="italic" title="Italic">
                                        <i class="fas fa-italic"></i>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-command="underline" title="Underline">
                                        <i class="fas fa-underline"></i>
                                    </button>
                                </div>
                                
                                <div class="toolbar-group">
                                    <select class="toolbar-select" data-command="fontSize" title="Font Size">
                                        <option value="1">8pt</option>
                                        <option value="2">10pt</option>
                                        <option value="3" selected>12pt</option>
                                        <option value="4">14pt</option>
                                        <option value="5">18pt</option>
                                        <option value="6">24pt</option>
                                        <option value="7">36pt</option>
                                    </select>
                                    
                                    <select class="toolbar-select" data-command="fontName" title="Font Family">
                                        <option value="Arial">Arial</option>
                                        <option value="Georgia" selected>Georgia</option>
                                        <option value="Times New Roman">Times New Roman</option>
                                        <option value="Helvetica">Helvetica</option>
                                        <option value="Verdana">Verdana</option>
                                        <option value="Trebuchet MS">Trebuchet MS</option>
                                    </select>
                                </div>
                                
                                <div class="toolbar-group">
                                    <button type="button" class="toolbar-btn" data-command="justifyLeft" title="Align Left">
                                        <i class="fas fa-align-left"></i>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-command="justifyCenter" title="Align Center">
                                        <i class="fas fa-align-center"></i>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-command="justifyRight" title="Align Right">
                                        <i class="fas fa-align-right"></i>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-command="justifyFull" title="Justify">
                                        <i class="fas fa-align-justify"></i>
                                    </button>
                                </div>
                                
                                <div class="toolbar-group">
                                    <button type="button" class="toolbar-btn" data-command="insertUnorderedList" title="Bullet List">
                                        <i class="fas fa-list-ul"></i>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-command="insertOrderedList" title="Numbered List">
                                        <i class="fas fa-list-ol"></i>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-command="indent" title="Indent">
                                        <i class="fas fa-indent"></i>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-command="outdent" title="Outdent">
                                        <i class="fas fa-outdent"></i>
                                    </button>
                                </div>
                                
                                <div class="toolbar-group">
                                    <input type="color" class="color-picker" data-command="foreColor" title="Text Color" value="#000000">
                                    <input type="color" class="color-picker" data-command="hiliteColor" title="Highlight Color" value="#ffff00">
                                </div>
                                
                                <div class="toolbar-group">
                                    <button type="button" class="toolbar-btn" data-command="removeFormat" title="Clear Formatting">
                                        <i class="fas fa-remove-format"></i>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-command="undo" title="Undo">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-command="redo" title="Redo">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="rich-editor" id="devotion-content" contenteditable="true" placeholder="Write the complete devotional content with rich formatting..."></div>
                            <textarea id="devotion-content-hidden" style="display: none;" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="devotion-prayer">Closing Prayer (Optional)</label>
                            <textarea class="form-control" id="devotion-prayer" rows="3" placeholder="Add a closing prayer..."></textarea>
                        </div>

                        <div class="form-group">
                            <label for="devotion-tags">Tags (comma-separated)</label>
                            <input type="text" class="form-control" id="devotion-tags" placeholder="faith, prayer, hope, love">
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-globe"></i> Publish to Live Website
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="previewDevotion()">
                                <i class="fas fa-eye"></i> Preview
                            </button>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> 
                                This will immediately update the live website and save to database
                            </small>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Family Resources Management -->
            <div class="page-section" id="family-resources">
                <div class="section-card">
                    <h4 class="section-title">
                        <i class="fas fa-upload"></i>
                        Upload New Resource
                    </h4>
                    <form id="resource-form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="resource-name">Resource Name</label>
                                    <input type="text" class="form-control" id="resource-name" placeholder="Enter resource name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="resource-category">Category</label>
                                    <select class="form-control" id="resource-category" required>
                                        <option value="">Select Category</option>
                                        <option value="children">Children</option>
                                        <option value="teens">Teens</option>
                                        <option value="young-adults">Young Adults</option>
                                        <option value="couples">Couples</option>
                                        <option value="parents">Parents</option>
                                        <option value="seniors">Seniors</option>
                                        <option value="general">General</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="resource-description">Description</label>
                            <textarea class="form-control" id="resource-description" rows="3" placeholder="Describe this resource..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="resource-thumbnail">Thumbnail Image</label>
                            <input type="file" class="form-control" id="resource-thumbnail" accept="image/*" required>
                            <img id="thumbnail-preview" class="image-preview" style="display:none;">
                        </div>

                        <div class="form-group">
                            <label for="resource-file">Resource File (PDF, DOC, etc.)</label>
                            <div class="file-upload-area" id="file-drop-area">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                <p>Drag and drop your file here or click to select</p>
                                <input type="file" class="form-control" id="resource-file" accept=".pdf,.doc,.docx,.txt" required style="display:none;">
                            </div>
                            <div id="file-info" style="display:none;" class="mt-2">
                                <small class="text-muted">Selected: <span id="file-name"></span></small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload Resource
                        </button>
                    </form>
                </div>

                <div class="section-card">
                    <h4 class="section-title">
                        <i class="fas fa-list"></i>
                        Uploaded Resources
                    </h4>
                    <div class="resources-grid" id="resources-grid">
                        <!-- Resources will be populated here -->
                    </div>
                </div>
            </div>

            <!-- Past Devotions Archive -->
            <div class="page-section" id="past-devotions">
                <div class="section-card">
                    <h4 class="section-title">
                        <i class="fas fa-history"></i>
                        Past Devotions Archive
                    </h4>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <select class="form-control" id="filter-month">
                                <option value="">All Months</option>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" id="filter-year">
                                <option value="">All Years</option>
                                <option value="2025">2025</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="search-topic" placeholder="Search by topic...">
                        </div>
                    </div>

                    <div class="devotions-list" id="past-devotions-list">
                        <!-- Past devotions will be populated here -->
                    </div>
                </div>
            </div>

            <!-- Prayer Requests -->
            <div class="page-section" id="prayer-requests">
                <div class="section-card">
                    <h4 class="section-title">
                        <i class="fas fa-pray"></i>
                        Prayer Requests Management
                    </h4>
                    <div class="devotions-list" id="prayer-requests-list">
                        <!-- Prayer requests will be populated here -->
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="page-section" id="settings">
                <div class="section-card">
                    <h4 class="section-title">
                        <i class="fas fa-cog"></i>
                        Website Settings
                    </h4>
                    <form id="settings-form">
                        <div class="form-group">
                            <label for="site-title">Website Title</label>
                            <input type="text" class="form-control" id="site-title" value="The Anchor Devotional">
                        </div>

                        <div class="form-group">
                            <label for="site-description">Website Description</label>
                            <textarea class="form-control" id="site-description" rows="3">Daily spiritual nourishment and biblical wisdom for believers.</textarea>
                        </div>

                        <div class="form-group">
                            <label for="admin-email">Admin Email</label>
                            <input type="email" class="form-control" id="admin-email" value="pastor@theanchor.com">
                        </div>

                        <div class="form-group">
                            <label for="contact-phone">Contact Phone</label>
                            <input type="tel" class="form-control" id="contact-phone" value="+234 812 345 6789">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Dashboard
        document.addEventListener('DOMContentLoaded', function() {
            initializeDashboard();
            loadStoredData();
            syncPrayerRequests();
            initializeRichTextEditor();
            initializeMobileMenu();
        });

        // Mobile Menu Functions
        function initializeMobileMenu() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('mobileOverlay');
            const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');

            // Mobile menu toggle
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                });
            }

            // Close mobile menu when overlay is clicked
            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            }

            // Close mobile menu when a link is clicked
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        sidebar.classList.remove('active');
                        overlay.classList.remove('active');
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                }
            });

            // Handle escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                }
            });
        }

        // Rich Text Editor Functions
        function initializeRichTextEditor() {
            const editor = document.getElementById('devotion-content');
            const toolbar = document.getElementById('editor-toolbar');
            const hiddenTextarea = document.getElementById('devotion-content-hidden');

            // Enable design mode for contenteditable
            editor.addEventListener('focus', function() {
                document.execCommand('defaultParagraphSeparator', false, 'p');
            });

            // Toolbar button event listeners
            toolbar.addEventListener('click', function(e) {
                if (e.target.classList.contains('toolbar-btn') || e.target.parentElement.classList.contains('toolbar-btn')) {
                    e.preventDefault();
                    const button = e.target.classList.contains('toolbar-btn') ? e.target : e.target.parentElement;
                    const command = button.getAttribute('data-command');
                    
                    executeCommand(command);
                    updateToolbarState();
                    editor.focus();
                }
            });

            // Handle select dropdowns
            toolbar.addEventListener('change', function(e) {
                if (e.target.classList.contains('toolbar-select')) {
                    const command = e.target.getAttribute('data-command');
                    const value = e.target.value;
                    executeCommand(command, value);
                    editor.focus();
                }
            });

            // Handle color pickers
            toolbar.addEventListener('change', function(e) {
                if (e.target.classList.contains('color-picker')) {
                    const command = e.target.getAttribute('data-command');
                    const color = e.target.value;
                    executeCommand(command, color);
                    editor.focus();
                }
            });

            // Update toolbar state on selection change
            editor.addEventListener('keyup', updateToolbarState);
            editor.addEventListener('mouseup', updateToolbarState);

            // Sync content with hidden textarea for form submission
            editor.addEventListener('input', function() {
                hiddenTextarea.value = editor.innerHTML;
            });

            // Handle keyboard shortcuts
            editor.addEventListener('keydown', function(e) {
                if (e.ctrlKey || e.metaKey) {
                    switch(e.key.toLowerCase()) {
                        case 'b':
                            e.preventDefault();
                            executeCommand('bold');
                            break;
                        case 'i':
                            e.preventDefault();
                            executeCommand('italic');
                            break;
                        case 'u':
                            e.preventDefault();
                            executeCommand('underline');
                            break;
                        case 'z':
                            e.preventDefault();
                            executeCommand('undo');
                            break;
                        case 'y':
                            e.preventDefault();
                            executeCommand('redo');
                            break;
                    }
                }
            });
        }

        function executeCommand(command, value = null) {
            document.execCommand(command, false, value);
        }

        function updateToolbarState() {
            const buttons = document.querySelectorAll('.toolbar-btn');
            const selects = document.querySelectorAll('.toolbar-select');

            buttons.forEach(button => {
                const command = button.getAttribute('data-command');
                if (document.queryCommandState(command)) {
                    button.classList.add('active');
                } else {
                    button.classList.remove('active');
                }
            });

            selects.forEach(select => {
                const command = select.getAttribute('data-command');
                const value = document.queryCommandValue(command);
                
                if (command === 'fontSize') {
                    select.value = value || '3';
                } else if (command === 'fontName') {
                    select.value = value.replace(/['"]/g, '') || 'Georgia';
                }
            });
        }

        // Sync prayer requests from website forms
        function syncPrayerRequests() {
            const websitePrayers = JSON.parse(localStorage.getItem('prayerRequests') || '[]');
            const dashboardPrayers = getFromStorage('prayers') || [];
            
            // Add new prayers from website to dashboard
            websitePrayers.forEach(prayer => {
                if (!dashboardPrayers.find(p => p.id === prayer.id)) {
                    dashboardPrayers.push(prayer);
                }
            });
            
            if (dashboardPrayers.length > 0) {
                saveToStorage('prayers', dashboardPrayers);
            }
        }

        // Navigation
        function initializeDashboard() {
            const navLinks = document.querySelectorAll('.nav-link');
            const sections = document.querySelectorAll('.page-section');
            const breadcrumb = document.getElementById('breadcrumb-text');

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const targetPage = this.getAttribute('data-page');
                    if (!targetPage) return;

                    // Update active nav link
                    navLinks.forEach(nav => nav.classList.remove('active'));
                    this.classList.add('active');

                    // Show target section
                    sections.forEach(section => section.classList.remove('active'));
                    const targetSection = document.getElementById(targetPage);
                    if (targetSection) {
                        targetSection.classList.add('active');
                        breadcrumb.textContent = this.textContent.trim();
                    }
                });
            });

            // Mobile sidebar toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('active');
                });
            }
        }

        // Data Storage Functions
        function saveToStorage(key, data) {
            localStorage.setItem('anchor_' + key, JSON.stringify(data));
        }

        function getFromStorage(key) {
            const data = localStorage.getItem('anchor_' + key);
            return data ? JSON.parse(data) : null;
        }

        function loadStoredData() {
            // Load existing devotions
            const devotions = getFromStorage('devotions') || [];
            const resources = getFromStorage('resources') || [];
            const prayers = getFromStorage('prayers') || [];

            // Update stats
            document.getElementById('total-devotions').textContent = devotions.length;
            document.getElementById('total-resources').textContent = resources.length;
            document.getElementById('total-prayers').textContent = prayers.length;

            // Populate lists
            populatePastDevotions(devotions);
            populateResources(resources);
            populatePrayerRequests(prayers);
        }

        // Landing Page Form
        document.getElementById('landing-page-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                introTitle: document.getElementById('intro-title').value,
                introSubtitle: document.getElementById('intro-subtitle').value,
                introDescription: document.getElementById('intro-description').value,
                featuredTopic: document.getElementById('featured-topic').value,
                featuredDate: document.getElementById('featured-date').value,
                featuredIntro: document.getElementById('featured-intro').value,
                verseOfDay: document.getElementById('verse-of-day').value,
                verseReference: document.getElementById('verse-reference').value,
                timestamp: new Date().toISOString()
            };

            saveToStorage('landing_page', formData);
            showAlert('success', 'Landing page content updated successfully!');
        });

        // Today's Devotion Form
        document.getElementById('devotion-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const devotions = getFromStorage('devotions') || [];
            const newDevotion = {
                id: Date.now(),
                topic: document.getElementById('devotion-topic').value,
                date: document.getElementById('devotion-date').value,
                verse: document.getElementById('devotion-verse').value,
                intro: document.getElementById('devotion-intro').value,
                content: document.getElementById('devotion-content').value,
                prayer: document.getElementById('devotion-prayer').value,
                tags: document.getElementById('devotion-tags').value.split(',').map(tag => tag.trim()),
                timestamp: new Date().toISOString()
            };

            devotions.unshift(newDevotion);
            saveToStorage('devotions', devotions);
            
            this.reset();
            showAlert('success', 'Devotion published successfully!');
            loadStoredData();
        });

        // Resource Upload Form
        document.getElementById('resource-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const resources = getFromStorage('resources') || [];
            const newResource = {
                id: Date.now(),
                name: document.getElementById('resource-name').value,
                category: document.getElementById('resource-category').value,
                description: document.getElementById('resource-description').value,
                filename: document.getElementById('file-name').textContent,
                timestamp: new Date().toISOString()
            };

            resources.unshift(newResource);
            saveToStorage('resources', newResource);
            
            this.reset();
            document.getElementById('file-info').style.display = 'none';
            showAlert('success', 'Resource uploaded successfully!');
            loadStoredData();
        });

        // File Upload Handling
        const fileDropArea = document.getElementById('file-drop-area');
        const fileInput = document.getElementById('resource-file');
        const fileInfo = document.getElementById('file-info');
        const fileName = document.getElementById('file-name');

        fileDropArea.addEventListener('click', () => fileInput.click());
        
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                fileName.textContent = this.files[0].name;
                fileInfo.style.display = 'block';
            }
        });

        // Image Preview Functions
        function setupImagePreview(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            
            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        setupImagePreview('cover-image', 'cover-preview');
        setupImagePreview('devotion-image', 'devotion-preview');
        setupImagePreview('resource-thumbnail', 'thumbnail-preview');

        // Populate Functions
        function populatePastDevotions(devotions) {
            const container = document.getElementById('past-devotions-list');
            if (!devotions.length) {
                container.innerHTML = '<p class="text-muted p-4">No devotions published yet.</p>';
                return;
            }

            container.innerHTML = devotions.map(devotion => `
                <div class="devotion-item">
                    <div class="devotion-info">
                        <h6>${devotion.topic}</h6>
                        <small>${formatDate(devotion.date)} • ${devotion.tags.join(', ')}</small>
                    </div>
                    <div class="devotion-actions">
                        <button class="btn btn-sm btn-outline-primary" onclick="editDevotion(${devotion.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteDevotion(${devotion.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `).join('');
        }

        function populateResources(resources) {
            const container = document.getElementById('resources-grid');
            if (!resources.length) {
                container.innerHTML = '<p class="text-muted">No resources uploaded yet.</p>';
                return;
            }

            container.innerHTML = resources.map(resource => `
                <div class="resource-card">
                    <div class="resource-info">
                        <div class="resource-name">${resource.name}</div>
                        <small class="text-muted">${resource.category} • ${formatDate(resource.timestamp)}</small>
                        <p class="mt-2">${resource.description}</p>
                        <div class="resource-actions">
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download"></i> Download
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteResource(${resource.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function populatePrayerRequests(prayers) {
            const container = document.getElementById('prayer-requests-list');
            if (!prayers.length) {
                container.innerHTML = '<p class="text-muted p-4">No prayer requests yet.</p>';
                return;
            }

            container.innerHTML = prayers.map(prayer => `
                <div class="devotion-item">
                    <div class="devotion-info">
                        <h6>${prayer.name}</h6>
                        <p class="mb-1">${prayer.request}</p>
                        <small>${formatDate(prayer.timestamp)}</small>
                    </div>
                    <div class="devotion-actions">
                        <button class="btn btn-sm btn-success">
                            <i class="fas fa-check"></i> Prayed
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deletePrayer('${prayer.id}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `).join('');
        }

        // Utility Functions
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

        function showAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.querySelector('.content-area').insertBefore(alertDiv, document.querySelector('.content-area').firstChild);
            
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        function deleteDevotion(id) {
            if (confirm('Are you sure you want to delete this devotion?')) {
                let devotions = getFromStorage('devotions') || [];
                devotions = devotions.filter(d => d.id !== id);
                saveToStorage('devotions', devotions);
                loadStoredData();
                showAlert('success', 'Devotion deleted successfully!');
            }
        }

        function deleteResource(id) {
            if (confirm('Are you sure you want to delete this resource?')) {
                let resources = getFromStorage('resources') || [];
                resources = resources.filter(r => r.id !== id);
                saveToStorage('resources', resources);
                loadStoredData();
                showAlert('success', 'Resource deleted successfully!');
            }
        }

        function deletePrayer(id) {
            if (confirm('Are you sure you want to delete this prayer request?')) {
                let prayers = getFromStorage('prayers') || [];
                prayers = prayers.filter(p => p.id !== id);
                saveToStorage('prayers', prayers);
                loadStoredData();
                showAlert('success', 'Prayer request deleted successfully!');
            }
        }

        // Set today's date as default
        document.getElementById('devotion-date').value = new Date().toISOString().split('T')[0];
        document.getElementById('featured-date').value = new Date().toISOString().split('T')[0];

        // Dashboard Authentication & Initialization
        document.addEventListener('DOMContentLoaded', function() {
            // Check authentication
            if (!anchorAuth.isAuthenticated()) {
                // Redirect to home page if not authenticated
                window.location.href = 'index.html';
                return;
            }

            // Update welcome message with actual user info
            const user = anchorAuth.getCurrentUser();
            if (user) {
                const welcomeElement = document.getElementById('user-welcome');
                if (welcomeElement) {
                    welcomeElement.textContent = `Welcome, ${user.full_name || user.username}`;
                }
            }

            // Initialize dashboard data
            loadStoredData();
            
            // Set up periodic refresh for real-time updates
            setInterval(loadStoredData, 30000); // Refresh every 30 seconds
        });

        // Add logout confirmation
        window.addEventListener('beforeunload', function() {
            // Optional: Save any unsaved changes
            // This runs when user closes the tab/window
        });

        // Real-time Website Update Functions
        function previewDevotion() {
            const title = document.getElementById('devotion-topic')?.value || 'Preview Title';
            const verse = document.getElementById('devotion-verse')?.value || 'Sample verse content';
            const verseRef = document.getElementById('verse-reference')?.value || 'Reference';
            const content = document.getElementById('devotion-content')?.innerHTML || 'Sample content';
            
            const previewWindow = window.open('', 'devotion-preview', 'width=800,height=600,scrollbars=yes');
            previewWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Devotion Preview</title>
                    <style>
                        body { font-family: Georgia, serif; max-width: 600px; margin: 50px auto; padding: 20px; line-height: 1.6; }
                        .devotion-title { color: #ad3128; border-bottom: 2px solid #ad3128; padding-bottom: 10px; }
                        .scripture-verse { background: #f8f9fa; padding: 20px; border-left: 4px solid #ad3128; margin: 20px 0; }
                        .devotion-content { margin: 20px 0; }
                        .preview-badge { position: fixed; top: 10px; right: 10px; background: #ffc107; color: #000; padding: 5px 10px; border-radius: 4px; font-size: 12px; }
                    </style>
                </head>
                <body>
                    <div class="preview-badge">PREVIEW MODE</div>
                    <h1 class="devotion-title">${title}</h1>
                    <p class="devotion-date">${new Date().toLocaleDateString('en-US', { 
                        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' 
                    })}</p>
                    <blockquote class="scripture-verse">
                        <p><strong>"${verse}"</strong></p>
                        <footer><cite>- ${verseRef}</cite></footer>
                    </blockquote>
                    <div class="devotion-content">${content}</div>
                </body>
                </html>
            `);
            previewWindow.document.close();
        }

        function showLiveUpdateStatus(message, type = 'info') {
            // Remove existing status
            const existing = document.querySelector('.update-status');
            if (existing) existing.remove();

            // Create new status
            const statusDiv = document.createElement('div');
            statusDiv.className = 'update-status';
            statusDiv.innerHTML = `
                <div class="alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'}"></i>
                    ${message}
                    <button type="button" class="btn-close" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;
            
            document.body.appendChild(statusDiv);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (statusDiv.parentElement) {
                    statusDiv.remove();
                }
            }, 5000);
        }

        // Add live update indicators to forms
        document.addEventListener('DOMContentLoaded', function() {
            // Add live indicators to forms
            const forms = ['devotion-form', 'homepage-form'];
            forms.forEach(formId => {
                const form = document.getElementById(formId);
                if (form) {
                    const indicator = document.createElement('div');
                    indicator.className = 'live-update-indicator';
                    indicator.innerHTML = '<span>Live Website Updates Enabled</span>';
                    form.appendChild(indicator);
                }
            });
        });
    </script>
</body>
</html>
