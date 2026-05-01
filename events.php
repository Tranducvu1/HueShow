<?php
// events.php - Danh sách sự kiện đã tổ chức
require_once 'config.php';

// Định nghĩa hàm fixImagePath nếu chưa có (để dùng ngay)
if (!function_exists('fixImagePath')) {
    function fixImagePath($path) {
        if (empty($path)) return '';
        if (preg_match('#^(https?:)?//#i', $path)) return $path;
        if (strpos($path, 'uploads/') === 0) return $path;
        if (strpos($path, 'uploads/') === 0) return '../' . $path;
        return $path;
    }
}

$page_title = 'Sự kiện';

if (function_exists('getLatestEvents')) {
    $events = getLatestEvents(8);  // lấy 8 sự kiện mới nhất
} else {
    $events = getAllEvents();      // fallback nếu không có hàm
}
$recentPosts = getLatestArticles(5);

include 'includes/fe/header.php';
?>

<style>
    /* ========== EVENT SPECIFIC STYLES ========== */
    .hero-event {
        background: linear-gradient(120deg, #0f172a, #1e293b);
        color: white;
        padding: 60px 0;
        text-align: center;
        margin-bottom: 48px;
    }
    .hero-event h1 {
        font-size: 2.8rem;
        font-weight: 800;
        margin-bottom: 16px;
    }
    .hero-event p {
        max-width: 700px;
        margin: 0 auto;
        font-size: 1.1rem;
        opacity: 0.9;
    }
    .section-heading {
        text-align: center;
        margin: 40px 0 30px;
    }
    .section-heading h2 {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(120deg, #0f172a, #334155);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    .section-heading .underline {
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #facc15, #eab308);
        margin: 12px auto 0;
        border-radius: 2px;
    }
    .grid-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 32px;
        margin: 40px 0 60px;
    }
    .event-card {
        background: white;
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        transition: all 0.3s cubic-bezier(0.2,0.9,0.4,1.1);
        text-decoration: none;
        display: block;
        color: inherit;
        cursor: pointer;
    }
    .event-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 35px -12px rgba(0,0,0,0.2);
    }
    .event-card:active {
        transform: scale(0.97);
        transition: transform 0.08s linear;
    }
    .card-img {
        width: 100%;
        aspect-ratio: 4/3;
        overflow: hidden;
        background: #e2e8f0;
    }
    .card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .event-card:hover .card-img img {
        transform: scale(1.05);
    }
    .card-content {
        padding: 20px;
    }
    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 10px;
        line-height: 1.4;
    }
    .card-date {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 12px;
    }
    .card-desc {
        color: #475569;
        font-size: 0.9rem;
        line-height: 1.5;
    }
    .two-columns {
        display: flex;
        gap: 40px;
        flex-wrap: wrap;
        margin-top: 20px;
    }
    .main-col {
        flex: 2;
        min-width: 260px;
    }
    .side-col {
        flex: 1;
        min-width: 260px;
        position: sticky;
        top: 100px;
        align-self: start;
    }
    .widget {
        background: white;
        border-radius: 24px;
        padding: 24px;
        margin-bottom: 30px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }
    .widget-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 20px;
        border-left: 5px solid #facc15;
        padding-left: 14px;
    }
    .recent-posts {
        list-style: none;
    }
    .recent-posts li {
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e2e8f0;
    }
    .recent-posts a {
        text-decoration: none;
        color: #1e293b;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: 0.3s;
    }
    .recent-posts a:hover {
        color: #facc15;
    }
    .video-widget iframe {
        width: 100%;
        border-radius: 16px;
    }
    @media (max-width: 768px) {
        .two-columns {
            flex-direction: column;
        }
        .hero-event h1 {
            font-size: 2rem;
        }
    }
</style>

<section class="hero-event">
    <div class="container">
        <h1>🎉 DỰ ÁN TIỂU BIỂU</h1>
        <p>Chúng tôi tự hào là đơn vị tổ chức hàng trăm sự kiện lớn nhỏ – từ hội nghị, lễ hội đến các chương trình tri ân khách hàng.</p>
    </div>
</section>

<div class="container">
    <div class="two-columns">
        <div class="main-col">
    
            <div class="grid-cards">
                <?php foreach ($events as $event): ?>
                <a href="event_detail.php?id=<?= $event['id'] ?>" class="event-card">
                    <div class="card-img">
                        <?php if (!empty($event['image'])): ?>
                            <img src="<?= htmlspecialchars(fixImagePath($event['image'])) ?>" alt="<?= htmlspecialchars($event['title']) ?>">
                        <?php else: ?>
                            <div style="background: linear-gradient(135deg,#1e3c00,#99cc05); width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:white; font-size:2rem;">🎪</div>
                        <?php endif; ?>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title"><?= htmlspecialchars($event['title']) ?></h3>
                        <div class="card-date"><i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($event['created_at'])) ?></div>
                    <p class="card-desc"><?= cleanText(substr($event['description'] ?? '', 0, 100)) ?>...</p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="side-col">
            <div class="widget">
                <div class="widget-title">📌 Về sự kiện</div>
                <p>HueShow chuyên tổ chức các chương trình hội nghị, lễ hội, sự kiện doanh nghiệp với quy mô từ 50 đến hàng nghìn khách mời.</p>
            </div>
            <div class="widget">
                <div class="widget-title">📰 Bài viết mới</div>
                <ul class="recent-posts">
                    <?php if (!empty($recentPosts)): ?>
                        <?php foreach ($recentPosts as $post): ?>
                            <li><a href="articles.php?id=<?= $post['id'] ?>"><i class="fas fa-angle-right" style="color:#facc15;"></i> <?= htmlspecialchars($post['title']) ?></a></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>Chưa có bài viết nào.</li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="widget video-widget">
                <div class="widget-title">🎥 Video sự kiện</div>
                <iframe src="https://www.youtube.com/embed/_ltAd7y-jRw" frameborder="0" allowfullscreen></iframe>
            </div>
            <div class="widget">
                <div class="widget-title">🤝 Khách hàng tiêu biểu</div>
                <p>Nha Khoa Nụ Cười Việt, Honda, Sunhouse, Panasonic, VPBank, Agribank, Tetra Pak, Boehringer...</p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/fe/footer.php'; ?>