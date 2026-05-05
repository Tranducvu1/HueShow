<?php
require_once 'config.php';

$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($event_id <= 0) {
    header("Location: index.php");
    exit;
}

$event = getEventById($event_id);

if (!$event) {
    header("Location: index.php");
    exit;
}

$event_details = getEventDetails($event_id);

// Get related events
function getRelatedEvents($current_id, $limit = 9) {
    global $conn;
    $current_id = intval($current_id);
    $query = "SELECT * FROM events WHERE status = 'published' AND id != $current_id ORDER BY created_at DESC LIMIT $limit";
    $result = $conn->query($query);
    
    $events = array();
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }
    return $events;
}

$relatedEvents = getRelatedEvents($event_id);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($event['title']) ?> - HueShow</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* ==================== DARK THEME VARIABLES ==================== */
        :root {
            --primary-gold: #D4A147;
            --primary-gold-light: #E5B563;
            --primary-gold-dark: #B8862F;
            --text-primary: #F5F5F0;
            --text-secondary: #D0D0C8;
            --text-muted: #A0A098;
            --dark-bg: #1A1A1A;
            --dark-bg-secondary: #242424;
            --dark-bg-tertiary: #2E2E2E;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --shadow-gold: 0 8px 20px rgba(212, 161, 71, 0.25);
            --shadow-lg: 0 10px 30px 0 rgba(0, 0, 0, 0.4);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
font-family: 'Times New Roman', sans-serif;
            background: linear-gradient(135deg, #1A1A1A 0%, #242424 50%, #1F1F1F 100%);
            color: var(--text-primary);
            line-height: 1.6;
        }

        .container {
            max-width: 1320px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* ========== HEADER ========== */
        .main-header {
            background: linear-gradient(180deg, rgba(26,26,26,0.98) 0%, rgba(36,36,36,0.95) 100%);
            padding: 12px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
            border-bottom: 2px solid var(--primary-gold);
            backdrop-filter: blur(10px);
        }

        .header-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .logo img {
            height: 70px;
            width: auto;
        }

        .nav-links {
            display: flex;
            gap: 32px;
            list-style: none;
            margin: 0;
        }

        .nav-links a {
            color: var(--primary-gold);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            font-size: 1rem;
            text-transform: uppercase;
        }

        .nav-links a:hover {
            color: var(--primary-gold-light);
        }

        .btn-phone {
            background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-light));
            color: #1a1a1a;
            padding: 10px 24px;
            border-radius: 30px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
            box-shadow: var(--shadow-gold);
        }

        .btn-phone:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212,161,71,0.5);
        }

        /* ========== MAIN CONTENT ========== */
        .blog-wrapper {
            display: flex;
            gap: 40px;
            padding: 30px 0;
        }

        .blog-single {
            flex: 1;
            background: linear-gradient(135deg, #1A1A1A, #242424);
            padding: 40px;
            border-radius: 28px;
            border: 1px solid rgba(212,161,71,0.2);
            box-shadow: var(--shadow-md);
        }

        .post-sidebar {
            width: 320px;
        }

        .widget-area {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .widget {
            background: linear-gradient(135deg, #1A1A1A, #242424);
            padding: 24px;
            border-radius: 24px;
            border: 1px solid rgba(212,161,71,0.2);
            box-shadow: var(--shadow-md);
        }

        .widget-title {
            color: var(--primary-gold);
            margin-bottom: 16px;
            font-size: 1.2rem;
            font-weight: 700;
            border-left: 5px solid var(--primary-gold);
            padding-left: 14px;
        }

        .widget p, .widget ul li {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .widget ul {
            list-style: none;
        }

        .widget ul li {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(212,161,71,0.2);
        }

        .widget ul li a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: var(--transition);
        }

        .widget ul li a:hover {
            color: var(--primary-gold);
        }

        /* ========== ARTICLE ========== */
        .article-inner {
            margin-bottom: 30px;
        }

        .entry-header {
            margin-bottom: 30px;
        }

        .entry-category {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-light));
            color: #1a1a1a;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .entry-category a {
            color: #1a1a1a;
            text-decoration: none;
        }

        .entry-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .is-divider {
            height: 2px;
            background: rgba(212,161,71,0.3);
            margin: 12px 0;
        }

        .entry-meta {
            display: flex;
            gap: 20px;
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .entry-meta i {
            margin-right: 5px;
            color: var(--primary-gold);
        }

        .entry-image {
            margin: 20px 0 30px 0;
        }

        .entry-image img {
            width: 100%;
            height: auto;
            border-radius: 20px;
            border: 1px solid rgba(212,161,71,0.3);
        }

        .entry-content {
            line-height: 1.8;
            color: var(--text-secondary);
        }

        .entry-content h2 {
            font-size: 1.6rem;
            color: var(--primary-gold);
            margin: 24px 0 16px 0;
        }

        .entry-content p {
            margin-bottom: 16px;
            text-align: justify;
        }

        .entry-content img {
            max-width: 100%;
            height: auto;
            margin: 20px 0;
            border-radius: 16px;
        }

        .entry-content a {
            color: var(--primary-gold);
            text-decoration: none;
        }

        .entry-content a:hover {
            text-decoration: underline;
        }

        /* ========== RELATED SECTION ========== */
        .html-before-comments {
            margin-top: 60px;
            padding-top: 40px;
            border-top: 1px solid rgba(212,161,71,0.2);
        }

        .section-title {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-gold);
            margin-bottom: 40px;
            position: relative;
            padding-bottom: 15px;
        }

        .section-title::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-gold), var(--primary-gold-light));
            border-radius: 2px;
        }

        .row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .post-item {
            background: linear-gradient(135deg, #1A1A1A, #242424);
            border-radius: 24px;
            overflow: hidden;
            transition: var(--transition);
            border: 1px solid rgba(212,161,71,0.2);
        }

        .post-item:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(212,161,71,0.5);
        }

        .post-item a {
            text-decoration: none;
            color: inherit;
        }

        .box-image {
            width: 100%;
            aspect-ratio: 4 / 3;
            overflow: hidden;
        }

        .image-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .post-item:hover .image-cover img {
            transform: scale(1.05);
        }

        .box-text {
            padding: 20px;
        }

        .post-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        /* ========== BACK LINK ========== */
        .back-link {
            margin-bottom: 20px;
        }

        .back-link a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-light));
            color: #1a1a1a;
            padding: 8px 20px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            box-shadow: var(--shadow-gold);
        }

        .back-link a:hover {
            transform: translateX(-3px);
            box-shadow: 0 8px 18px rgba(212,161,71,0.4);
        }

        /* ========== FOOTER ========== */
        .footer-wrapper {
            background: linear-gradient(180deg, #0a0a0a 0%, #1a1a1a 100%);
            color: var(--text-secondary);
            padding: 60px 0 30px;
            margin-top: 80px;
            border-top: 2px solid var(--primary-gold);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-col h4 {
            color: var(--primary-gold);
            font-size: 1.3rem;
            font-weight: 800;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 12px;
        }

        .footer-col h4::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-gold), transparent);
        }

        .footer-col p {
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-col i {
            width: 28px;
            color: var(--primary-gold);
        }

        .social-icons {
            display: flex;
            gap: 14px;
            margin-top: 18px;
        }

        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            background: rgba(212,161,71,0.15);
            border: 1px solid rgba(212,161,71,0.3);
            border-radius: 50%;
            color: var(--primary-gold);
            font-size: 1.3rem;
            transition: var(--transition);
            text-decoration: none;
        }

        .social-icon:hover {
            background: var(--primary-gold);
            color: #1a1a1a;
            transform: translateY(-5px);
            box-shadow: var(--shadow-gold);
        }

        .copyright {
            text-align: center;
            padding-top: 24px;
            border-top: 1px solid rgba(212,161,71,0.2);
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        /* ========== HOTLINE FIXED ========== */
        .hotline-fixed {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-dark));
            color: #1a1a1a;
            padding: 12px 24px;
            border-radius: 60px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: bold;
            text-decoration: none;
            z-index: 100;
            box-shadow: var(--shadow-gold);
            transition: var(--transition);
        }

        .hotline-fixed:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(212,161,71,0.5);
            color: #1a1a1a;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 1024px) {
            .blog-wrapper {
                flex-direction: column;
            }
            .post-sidebar {
                width: 100%;
            }
            .row {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .header-inner {
                flex-direction: column;
                text-align: center;
            }
            .nav-links {
                justify-content: center;
                flex-wrap: wrap;
                gap: 16px;
            }
            .row {
                grid-template-columns: 1fr;
            }
            .entry-title {
                font-size: 1.5rem;
            }
            .blog-single {
                padding: 24px;
            }
        }

        @media (max-width: 576px) {
            .section-title {
                font-size: 1.6rem;
            }
        }
    </style>
</head>
<body>

<!-- HEADER (giữ nguyên nội dung nhưng style đã được đồng bộ) -->
<header class="main-header">
    <div class="container">
        <div class="header-inner">
            <div class="logo">
                <a href="index.php">
                    <img src="uploads/logo/logo.png" alt="HueShow" onerror="this.src='https://via.placeholder.com/200x70?text=HueShow'">
                </a>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="events.php">Sự kiện</a></li>
                <li><a href="teambuilding.php">Dịch vụ</a></li>
                <li><a href="about.php">Giới thiệu</a></li>
                <li><a href="contact.php">Liên hệ</a></li>
            </ul>
            <a href="tel:0989898989" class="btn-phone"><i class="fas fa-phone-alt"></i> 0989.898.989</a>
        </div>
    </div>
</header>

<main>
    <div class="container">
        <div class="back-link">
            <a href="index.php"><i class="fas fa-arrow-left"></i> Quay lại</a>
        </div>

        <div class="blog-wrapper">
            <!-- LEFT SIDEBAR -->
            <div class="post-sidebar">
                <div id="secondary" class="widget-area">
                    <div class="widget">
                        <span class="widget-title">VỀ CHÚNG TÔI</span>
                        <p>HueShow chuyên tổ chức team building, sự kiện doanh nghiệp chuyên nghiệp sáng tạo với phong cách chuyên nghiệp và giàu cảm xúc.</p>
                    </div>

                    <div class="widget">
                        <span class="widget-title">BÀI VIẾT MỚI</span>
                        <ul>
                            <?php 
                            $recent = $conn->query("SELECT id, title FROM events WHERE status = 'published' ORDER BY created_at DESC LIMIT 5");
                            if ($recent) {
                                while ($row = $recent->fetch_assoc()) {
                                    echo '<li><a href="detail.php?id=' . $row['id'] . '">' . htmlspecialchars($row['title']) . '</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>

                    <div class="widget">
                        <span class="widget-title">CHUYÊN MỤC</span>
                        <ul>
                            <li><a href="#">Team Building</a></li>
                            <li><a href="#">Hội nghị khách hàng</a></li>
                            <li><a href="#">Sự kiện đặc biệt</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- MAIN CONTENT -->
            <div class="blog-single">
                <article class="article-inner">
                    <header class="entry-header">
                        <span class="entry-category">
                            <a href="#">Team Building</a>
                        </span>

                        <h1 class="entry-title"><?= htmlspecialchars($event['title']) ?></h1>
                        
                        <div class="is-divider"></div>

                        <div class="entry-meta">
                            <span><i class="far fa-calendar"></i> <?= date('d/m/Y', strtotime($event['created_at'])) ?></span>
                            <span><i class="fas fa-user"></i> Admin</span>
                            <span><i class="fas fa-eye"></i> 5 lượt xem</span>
                        </div>
                    </header>

                    <?php if ($event['image']): ?>
                    <div class="entry-image">
                        <img src="<?= htmlspecialchars($event['image']) ?>" alt="<?= htmlspecialchars($event['title']) ?>">
                    </div>
                    <?php endif; ?>

                    <div class="entry-content">
                        <?= $event['description'] ?>
                        
                        <?php if ($event_details && isset($event_details['content'])): ?>
                        <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid rgba(212,161,71,0.2);">
                            <h2>Chi tiết sự kiện</h2>
                            <p><?= nl2br(htmlspecialchars($event_details['content'])) ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </article>

                <!-- RELATED EVENTS -->
                <div class="html-before-comments">
                    <h2 class="section-title">Các sự kiện khác</h2>
                    
                    <div class="row">
                        <?php foreach ($relatedEvents as $related): ?>
                        <div class="post-item">
                            <a href="detail.php?id=<?= $related['id'] ?>">
                                <div class="box-image">
                                    <div class="image-cover">
                                        <?php if ($related['image']): ?>
                                            <img src="<?= htmlspecialchars($related['image']) ?>" alt="<?= htmlspecialchars($related['title']) ?>">
                                        <?php else: ?>
                                            <div style="background: #2a2a2a; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: var(--primary-gold);">🎯</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="box-text">
                                    <h5 class="post-title"><?= htmlspecialchars($related['title']) ?></h5>
                                    <div class="is-divider"></div>
                                </div>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- FOOTER (giữ nguyên nội dung, style đã đồng bộ) -->
<div class="footer-wrapper">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <h4>Về HueShow</h4>
                <p>Giải pháp sự kiện trọn gói – Chuyên nghiệp, Sáng tạo, Khác biệt.</p>
            </div>
            <div class="footer-col">
                <h4>Liên hệ</h4>
                <p><i class="fas fa-map-marker-alt"></i> 123 Đường Nguyễn Huệ, TP.HCM</p>
                <p><i class="fas fa-phone"></i> 0989.898.989</p>
                <p><i class="fas fa-envelope"></i> info@hueshow.com</p>
            </div>
            <div class="footer-col">
                <h4>Mạng xã hội</h4>
                <div class="social-icons">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
        <div class="copyright">
            &copy; 2025 HueShow. All rights reserved.
        </div>
    </div>
</div>

<a href="tel:0989898989" class="hotline-fixed">
    <i class="fas fa-phone-alt"></i> Hotline: 0989.898.989
</a>

</body>
</html>