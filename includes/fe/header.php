<?php
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__, 2));
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title><?= $page_title ?? 'HueShow' ?> - Team Building & Sự kiện chuyên nghiệp</title>
    <link rel="icon" type="image/jpeg" href="assets/logo.jpg">
    <link rel="shortcut icon" type="image/jpeg" href="assets/logo.jpg">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/index.css">
</head>
<body>

<header class="main-header">
    <div class="container header-content">
        <!-- LEFT: Brand / Logo -->
        <div class="header-brand">
            <img src="assets/logo.jpg" alt="HueShow" class="brand-logo">
            <div class="brand-info">
                <h1 class="brand-title">HueShow</h1>
                <p class="brand-tagline">Media & Event</p>
            </div>
        </div>

        <!-- CENTER: Navigation -->
        <nav class="header-nav">
            <button class="nav-toggle" id="navToggle" aria-label="Menu">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="nav-list" id="navList">
                <li><a href="index.php" class="nav-link <?= $current_page == 'index.php' ? 'active' : '' ?>">Trang chủ</a></li>
                <li><a href="intro.php" class="nav-link <?= $current_page == 'intro.php' ? 'active' : '' ?>">Giới thiệu</a></li>
                <li><a href="teambuilding.php" class="nav-link <?= $current_page == 'teambuilding.php' ? 'active' : '' ?>">Dịch Vụ</a></li>
                <li><a href="events.php" class="nav-link <?= $current_page == 'events.php' ? 'active' : '' ?>">Dự Án</a></li>
                <li><a href="contact.php" class="nav-link <?= $current_page == 'contact.php' ? 'active' : '' ?>">Liên hệ</a></li>
            </ul>
        </nav>

        <div class="header-cta">
            <a href="tel:0979663727" class="btn-phone">
                <i class="fas fa-phone-alt"></i>
                <span class="phone-text">097 966 37 27</span>
            </a>
        </div>
    </div>
</header>

<script src="../assets/js/main.js"></script>
</body>
</html>