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
    <title><?= htmlspecialchars($event['title']) ?> - Event5</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background: #f6f6f6;
            color: #262626;
            line-height: 1.6;
        }

        .container {
            max-width: 1170px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* ========== HEADER ========== */
        .main-header {
            background: #0a0a0a;
            color: white;
            padding: 20px 0;
            position: sticky;
            top: 0;
            z-index: 100;
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
            color: #99cc00;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            font-size: 1rem;
            text-transform: uppercase;
        }

        .nav-links a:hover {
            color: #ffffff;
        }

        .btn-phone {
            background: #99cc05;
            color: #0a0a0a;
            padding: 10px 24px;
            border-radius: 7px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
        }

        /* ========== MAIN CONTENT ========== */
        .blog-wrapper {
            display: flex;
            gap: 40px;
            padding: 30px 0;
        }

        .blog-single {
            flex: 1;
            background: white;
            padding: 30px 15px;
        }

        .post-sidebar {
            width: 300px;
        }

        .widget-area {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .widget {
            background: white;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 2px 3px rgba(0,0,0,.18);
        }

        .widget-title {
            color: #99cc05;
            margin-bottom: 15px;
            font-size: 1.2rem;
            font-weight: 600;
            border-bottom: 2px solid #99cc05;
            padding-bottom: 10px;
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
            background: #99cc05;
            color: #0a0a0a;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .entry-category a {
            color: #0a0a0a;
            text-decoration: none;
        }

        .entry-title {
            font-size: 2rem;
            font-weight: 700;
            color: #0a0a0a;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .is-divider {
            height: 2px;
            background: #e2e8f0;
            margin: 12px 0 12px 0;
        }

        .entry-meta {
            display: flex;
            gap: 20px;
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .entry-meta i {
            margin-right: 5px;
        }

        .entry-image {
            margin: 20px 0 30px 0;
        }

        .entry-image img {
            width: 100%;
            height: auto;
            border-radius: 4px;
            box-shadow: 0 2px 3px rgba(0,0,0,.18);
        }

        .entry-content {
            line-height: 1.8;
            color: #475569;
        }

        .entry-content h2 {
            font-size: 1.6rem;
            color: #0a0a0a;
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
            border-radius: 4px;
        }

        .entry-content a {
            color: #4c9616;
            text-decoration: none;
        }

        .entry-content a:hover {
            text-decoration: underline;
        }

        /* ========== RELATED SECTION ========== */
        .html-before-comments {
            margin-top: 60px;
            padding-top: 40px;
            border-top: 2px solid #e2e8f0;
        }

        .section-title {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            color: #0a0a0a;
            margin-bottom: 40px;
        }

        .row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .row.large-columns-3 {
            grid-template-columns: repeat(3, 1fr);
        }

        .post-item {
            background: white;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 2px 3px rgba(0,0,0,.18);
            transition: all 0.35s;
        }

        .post-item:hover {
            box-shadow: 0 10px 25px rgba(0,0,0,.15);
            transform: translateY(-5px);
        }

        .post-item a {
            text-decoration: none;
            color: inherit;
        }

        .box-image {
            width: 100%;
            aspect-ratio: 4 / 3;
            overflow: hidden;
            background: #e2e8f0;
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
            padding: 15px;
            background: white;
        }

        .post-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0a0a0a;
            margin-bottom: 8px;
        }

        .post-date {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 8px;
        }

        /* ========== FOOTER ========== */
        .footer-wrapper {
            background: #0a0a0a;
            color: #cbd5e1;
            padding: 48px 0 24px;
            margin-top: 60px;
        }

        .footer-section {
            background: url(/wp-content/uploads/2018/05/bg.jpg);
            padding: 40px 0;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-col h4 {
            color: #99cc05;
            margin-bottom: 16px;
            font-size: 1.2rem;
        }

        .footer-col p {
            color: #94a3b8;
            line-height: 1.8;
        }

        .social-icons {
            display: flex;
            gap: 12px;
            margin-top: 16px;
        }

        .social-icons a {
            width: 40px;
            height: 40px;
            background: #1e293b;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #99cc05;
            transition: 0.3s;
            text-decoration: none;
        }

        .social-icons a:hover {
            background: #99cc05;
            color: #0a0a0a;
        }

        .copyright {
            text-align: center;
            padding-top: 24px;
            border-top: 1px solid #1e293b;
            font-size: 0.85rem;
        }

        .back-link {
            margin-bottom: 20px;
        }

        .back-link a {
            color: #4c9616;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link a:hover {
            color: #99cc05;
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
            }
            .row {
                grid-template-columns: 1fr;
            }
            .entry-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<main>
    <div class="container">
        <div class="back-link">
            <a href="index.php"><i class="fas fa-arrow-left"></i> Quay lại</a>
        </div>

        <div class="blog-wrapper">
            <!-- LEFT SIDEBAR -->
            <div class="post-sidebar">
                <div id="secondary" class="widget-area">
                    <!-- About Widget -->
                    <div class="widget widget_nav_menu">
                        <span class="widget-title">VỀ CHÚNG TÔI</span>
                        <p style="font-size: 0.9rem; color: #64748b;">Event5 chuyên tổ chức team building, sự kiện doanh nghiệp chuyên nghiệp sáng tạo.</p>
                    </div>

                    <!-- Recent Posts Widget -->
                    <div class="widget widget_recent_entries">
                        <span class="widget-title">BÀI VIẾT MỚI</span>
                        <ul style="list-style: none; margin: 0;">
                            <?php 
                            $recent = $conn->query("SELECT id, title FROM events WHERE status = 'published' ORDER BY created_at DESC LIMIT 5");
                            if ($recent) {
                                while ($row = $recent->fetch_assoc()) {
                                    echo '<li style="margin-bottom: 8px; border-bottom: 1px solid #e0e0e0; padding-bottom: 8px;">';
                                    echo '<a href="detail.php?id=' . $row['id'] . '" style="color: #4c9616; text-decoration: none; font-size: 0.9rem;">' . htmlspecialchars($row['title']) . '</a>';
                                    echo '</li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>

                    <!-- Categories Widget -->
                    <div class="widget widget_categories">
                        <span class="widget-title">CHUYÊN MỤC</span>
                        <ul style="list-style: none; margin: 0;">
                            <li style="margin-bottom: 5px; color: #99cc05;"><a href="#" style="color: #4c9616; text-decoration: none;">Team Building</a></li>
                            <li style="margin-bottom: 5px; color: #99cc05;"><a href="#" style="color: #4c9616; text-decoration: none;">Hội nghị khách hàng</a></li>
                            <li style="margin-bottom: 5px; color: #99cc05;"><a href="#" style="color: #4c9616; text-decoration: none;">Sự kiện đặc biệt</a></li>
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

                    <!-- Featured Image -->
                    <?php if ($event['image']): ?>
                    <div class="entry-image">
                        <img src="<?= htmlspecialchars($event['image']) ?>" alt="<?= htmlspecialchars($event['title']) ?>">
                    </div>
                    <?php endif; ?>

                    <!-- Article Content -->
                    <div class="entry-content">
                        <?= $event['description'] ?>
                        
                        <?php if ($event_details && isset($event_details['content'])): ?>
                        <div style="margin-top: 24px; padding-top: 24px; border-top: 2px solid #e2e8f0;">
                            <h2>Chi tiết sự kiện</h2>
                            <p><?= nl2br(htmlspecialchars($event_details['content'])) ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </article>

                <!-- RELATED EVENTS -->
                <div class="html-before-comments">
                    <h2 class="section-title">Các sự kiện khác</h2>
                    
                    <div class="row large-columns-3 medium-columns-1 small-columns-1 row-small">
                        <?php foreach ($relatedEvents as $related): ?>
                        <div class="col post-item">
                            <div class="col-inner">
                                <a href="detail.php?id=<?= $related['id'] ?>" class="plain">
                                    <div class="box box-normal box-text-bottom box-blog-post has-hover">
                                        <div class="box-image">
                                            <div class="image-overlay-add image-cover">
                                                <?php if ($related['image']): ?>
                                                    <img src="<?= htmlspecialchars($related['image']) ?>" alt="<?= htmlspecialchars($related['title']) ?>">
                                                <?php else: ?>
                                                    <div style="background: #99cc05; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 2rem;">🎯</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="box-text text-left is-small">
                                            <div class="box-text-inner blog-post-inner">
                                                <h5 class="post-title is-large"><?= htmlspecialchars($related['title']) ?></h5>
                                                <div class="is-divider"></div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include 'includes/fe/footer.php'; ?>


<a href="tel:0989898989" style="position: fixed; bottom: 24px; right: 24px; background: #99cc05; padding: 12px 20px; border-radius: 60px; display: flex; align-items: center; gap: 12px; font-weight: bold; color: #0a0a0a; text-decoration: none; box-shadow: 0 10px 25px rgba(0,0,0,.2); z-index: 99; transition: all 0.3s;">
    <i class="fas fa-phone-alt"></i> Hotline: 0989.898.989
</a>

</body>
</html>