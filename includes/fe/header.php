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
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
<header class="main-header">
    <div class="container header-content">
        <div class="header-brand">
            <img src="assets/logo.jpg" alt="HueShow" class="brand-logo">
            <div class="brand-info">
                <h1 class="brand-title">HueShow</h1>
                <p class="brand-tagline">Media & Event</p>
            </div>
        </div>

        <nav class="header-nav" id="headerNav" aria-label="Điều hướng chính">
            <button class="menu-close-btn" id="menuCloseBtn" type="button" aria-label="Đóng menu">
                <i class="fas fa-times"></i>
            </button>
            <ul class="nav-list" id="navList">
                <li><a href="index.php" class="nav-link <?= $current_page == 'index.php' ? 'active' : '' ?>">Trang chủ</a></li>
                <li><a href="intro.php" class="nav-link <?= $current_page == 'intro.php' ? 'active' : '' ?>">Giới thiệu</a></li>
                <li class="has-submenu">
                    <details class="nav-dropdown <?= in_array($current_page, ['teambuilding.php', 'service_detail.php']) ? 'active-dropdown' : '' ?>">
                        <summary class="nav-link nav-summary <?= in_array($current_page, ['teambuilding.php', 'service_detail.php']) ? 'active' : '' ?>">
                            <span>Dịch Vụ</span>
                            <i class="fas fa-chevron-down"></i>
                        </summary>
                        <div class="dropdown-menu dropdown-services">
                            <div class="dropdown-menu-inner">
                                <a href="teambuilding.php?service=market-setup-san-khau" class="dropdown-item">MARKET &amp; SETUP SÂN KHẤU</a>
                                <a href="teambuilding.php?service=ho-tro-nhan-su" class="dropdown-item">HỖ TRỢ NHÂN SỰ - MC, CA SĨ, PG, MASCOT “CÂY NHÀ...”</a>
                                <a href="teambuilding.php?service=ho-tro-kich-ban-tron-goi" class="dropdown-item">HỖ TRỢ LÊN KỊCH BẢN &amp; HOÀN THIỆN TRỌN GÓI SỰ KIỆN</a>
                                <a href="teambuilding.php?service=cung-cap-dancer-vu-doan" class="dropdown-item">CUNG CẤP DANCER, VŨ ĐOÀN - THỔI HỒN VÀO TỪNG BƯỚC</a>
                                <a href="teambuilding.php?service=to-chuc-khai-truong" class="dropdown-item">DỊCH VỤ TỔ CHỨC KHAI TRƯƠNG CHUYÊN NGHIỆP</a>
                                <a href="teambuilding.php?service=dem-nhac-nang-tho" class="dropdown-item">ĐÊM NHẠC NÀNG THƠ CÙNG ALTIS BAND</a>
                                <a href="teambuilding.php?service=le-ra-quan-bat-dong-san" class="dropdown-item">TỔ CHỨC LỄ RA QUÂN DỰ ÁN BẤT ĐỘNG SẢN</a>
                                <a href="teambuilding.php?service=le-ky-niem-20-nam" class="dropdown-item">TỔ CHỨC LỄ KỶ NIỆM 20 NĂM – SỰ KIỆN TRƯỜNG ĐẠI HỌC</a>
                            </div>
                        </div>
                    </details>
                </li>
                <li class="has-submenu">
                    <details class="nav-dropdown <?= in_array($current_page, ['events.php', 'event_detail.php']) ? 'active-dropdown' : '' ?>">
                        <summary class="nav-link nav-summary <?= in_array($current_page, ['events.php', 'event_detail.php']) ? 'active' : '' ?>">
                            <span>Dự Án</span>
                            <i class="fas fa-chevron-down"></i>
                        </summary>
                        <div class="dropdown-menu dropdown-projects">
                            <div class="dropdown-menu-inner">
                                <a href="events.php?project=entertainment-game-show" class="dropdown-item">ENTERTAINMENT GAME SHOW</a>
                                <a href="events.php?project=dance-festival" class="dropdown-item">DANCE FESTIVAL</a>
                                <a href="events.php?project=teambuilding-du-an" class="dropdown-item">TEAMBUILDING</a>
                                <a href="events.php?project=fashion-show" class="dropdown-item">FASHION SHOW</a>
                                <a href="events.php?project=chuong-trinh-yep" class="dropdown-item">CHƯƠNG TRÌNH YEP</a>
                            </div>
                        </div>
                    </details>
                </li>
                <li><a href="contact.php" class="nav-link <?= $current_page == 'contact.php' ? 'active' : '' ?>">Liên hệ</a></li>
            </ul>
        </nav>

        <div class="header-cta">
            <a href="tel:0979663727" class="btn-phone">
                <i class="fas fa-phone-alt"></i>
                <span class="phone-text">097 966 37 27</span>
            </a>
            <button class="nav-toggle" id="navToggle" type="button" aria-label="Mở menu" aria-controls="headerNav" aria-expanded="false">
                <i class="fas fa-bars"></i>
            </button>
        </div>

    </div>
</header>
<script src="assets/js/main.js"></script>
<script src="assets/js/script.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropdowns = document.querySelectorAll('.nav-dropdown');
    const desktopQuery = window.matchMedia('(min-width: 769px)');

    function closeAll(except = null) {
        dropdowns.forEach(dropdown => {
            if (dropdown !== except) {
                dropdown.removeAttribute('open');
            }
        });
    }

    dropdowns.forEach(dropdown => {
        const parentItem = dropdown.closest('.has-submenu');
        const summary = dropdown.querySelector('.nav-summary');

        if (!parentItem || !summary) return;

        parentItem.addEventListener('mouseenter', function () {
            if (!desktopQuery.matches) return;
            closeAll(dropdown);
            dropdown.setAttribute('open', 'open');
        });

        parentItem.addEventListener('mouseleave', function () {
            if (!desktopQuery.matches) return;
            dropdown.removeAttribute('open');
        });

        summary.addEventListener('click', function (e) {
            if (desktopQuery.matches) {
                e.preventDefault();
                const isOpen = dropdown.hasAttribute('open');
                closeAll();
                if (!isOpen) {
                    dropdown.setAttribute('open', 'open');
                }
            }
        });
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.has-submenu')) {
            closeAll();
        }
    });
});
</script>
