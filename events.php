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

* { margin: 0; padding: 0; box-sizing: border-box; }
html { scroll-behavior: smooth; }

body {
font-family: 'Times New Roman', sans-serif;
    background: linear-gradient(135deg, #1A1A1A 0%, #242424 50%, #1F1F1F 100%);
    color: var(--text-primary);
    line-height: 1.6;
    min-height: 100vh;
}

.container { max-width: 1320px; margin: 0 auto; padding: 0 24px; }

/* ==================== HEADER ==================== */
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
.header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 30px;
}
.header-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    background: linear-gradient(135deg, var(--primary-gold) 0%, var(--primary-gold-light) 100%);
    padding: 10px 16px;
    border-radius: 16px;
    box-shadow: var(--shadow-gold);
    transition: var(--transition);
    flex-shrink: 0;
}
.header-brand:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(212, 161, 71, 0.35);
}
.brand-logo {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #FFD700, #FFA500);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    border: 2px solid rgba(255,255,255,0.3);
    transition: var(--transition);
}
.brand-logo:hover { transform: scale(1.05); }
.brand-info { display: flex; flex-direction: column; }
.brand-title {
    color: #1a1a1a;
    font-weight: 800;
    font-size: 1.3rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    line-height: 1.2;
}
.brand-tagline {
    color: #333;
    font-size: 0.7rem;
    letter-spacing: 2px;
    text-transform: uppercase;
    font-weight: 600;
    opacity: 0.8;
}
.header-nav { flex: 1; display: flex; align-items: center; justify-content: center; }
.nav-list {
    display: flex;
    list-style: none;
    gap: 16px;
    margin: 0;
    padding: 0;
}
.nav-link {
    color: var(--primary-gold);
    padding: 8px 20px;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.95rem;
    border-radius: 30px;
    transition: var(--transition);
    white-space: nowrap;
    background: rgba(212, 161, 71, 0.08);
    border: 1px solid transparent;
    letter-spacing: 0.3px;
}
.nav-link:hover {
    background: rgba(212, 161, 71, 0.15);
    border-color: var(--primary-gold);
    color: var(--primary-gold-light);
    transform: translateY(-2px);
}
.nav-link.active {
    background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-light)) !important;
    color: #1a1a1a !important;
    box-shadow: var(--shadow-gold) !important;
    border-color: var(--primary-gold) !important;
}
.nav-toggle {
    display: none;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 10px;
    color: white;
    font-size: 1.1rem;
    cursor: pointer;
    padding: 8px;
    transition: var(--transition);
}
.nav-toggle:hover {
    background: var(--primary-gold);
    color: #1a1a1a;
    border-color: var(--primary-gold);
}
.header-cta { flex-shrink: 0; display: flex; align-items: center; gap: 8px; }
.btn-phone {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 22px;
    background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-light));
    color: #1a1a1a;
    text-decoration: none;
    border-radius: 30px;
    font-weight: 700;
    font-size: 0.9rem;
    transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
    box-shadow: var(--shadow-gold);
    border: none;
    cursor: pointer;
}
.btn-phone:hover {
    background: linear-gradient(135deg, var(--primary-gold-light), var(--primary-gold));
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 8px 20px rgba(212, 161, 71, 0.5);
    color: #1a1a1a;
}

.hero-event {
    background: linear-gradient(120deg, #0f172a, #1a1a2e);
    padding: 80px 0;
    text-align: center;
        font-family: 'Times New Roman', sans-serif;
    margin-bottom: 48px;
    border-bottom: 2px solid var(--primary-gold);
}
.hero-event h1 {
    font-size: 1.6rem;
    font-weight: 800;
    margin-bottom: 16px;
    color: var(--primary-gold);
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}
.hero-event p {
    font-size: 1.2rem;
    color: var(--text-secondary);
    max-width: 700px;
    margin: 0 auto;
}

/* ==================== LAYOUT 2 CỘT ==================== */
.two-columns {
    display: flex;
    gap: 40px;
    margin-bottom: 60px;
}
.main-col { flex: 2; }
.side-col { flex: 1; }

/* ==================== GRID CARDS ==================== */
.grid-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 32px;
    margin-bottom: 40px;
}

.event-card {
    background: linear-gradient(135deg, #1A1A1A, #242424);
    border-radius: 24px;
    overflow: hidden;
    transition: var(--transition);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(212, 161, 71, 0.2);
    text-decoration: none;
    display: flex;
    flex-direction: column;
    height: 100%;
    color: inherit;
}
.event-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
    border-color: rgba(212, 161, 71, 0.5);
}
.card-img {
    height: 220px;
    background: #2a2a2a;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.card-img img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}
.event-card:hover .card-img img { transform: scale(1.05); }
.card-content {
    padding: 24px;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.card-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 8px;
    color: var(--text-primary);
    line-height: 1.4;
}
.card-date {
    font-size: 0.85rem;
    color: var(--primary-gold);
    margin-bottom: 12px;
    font-weight: 600;
}
.card-desc {
    font-size: 0.95rem;
    color: var(--text-secondary);
    line-height: 1.5;
    flex: 1;
}

/* ==================== SIDEBAR WIDGETS ==================== */
.widget {
    background: linear-gradient(135deg, #1A1A1A, #242424);
    border-radius: 24px;
    padding: 20px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-md);
    border: 1px solid rgba(212,161,71,0.2);
}
.widget-title {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 16px;
    border-left: 5px solid var(--primary-gold);
    padding-left: 14px;
    color: var(--primary-gold);
}
.widget p, .widget div {
    font-size: 0.95rem;
    line-height: 1.5;
    color: var(--text-secondary);
}
.recent-posts {
    list-style: none;
}
.recent-posts li {
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(212,161,71,0.2);
}
.recent-posts a {
    text-decoration: none;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 8px;
    transition: 0.3s;
    font-size: 0.9rem;
}
.recent-posts a:hover {
    color: var(--primary-gold);
}
.recent-posts a i {
    color: var(--primary-gold);
}
.video-widget iframe {
    width: 100%;
    border-radius: 16px;
    border: 1px solid rgba(212,161,71,0.3);
    aspect-ratio: 16/9;
}

/* ==================== FOOTER ==================== */
.footer {
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
    bottom: 0; left: 0;
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
.footer-col i { width: 28px; color: var(--primary-gold); }
.social-icons { display: flex; gap: 14px; margin-top: 18px; }
.social-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, rgba(212,161,71,0.15), rgba(212,161,71,0.05));
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

/* ==================== RESPONSIVE ==================== */
@media (max-width: 992px) {
    .two-columns { flex-direction: column; }
}
@media (max-width: 768px) {
    .btn-phone .phone-text { display: none; }
    .nav-toggle { display: flex; }
    .nav-list {
        position: fixed; top: 0; right: -100%;
        width: 280px; height: 100vh;
        background: #242424;
        flex-direction: column;
        padding: 70px 20px;
        transition: right 0.3s;
        z-index: 9999;
    }
    .nav-list.show { right: 0; }
    .nav-link { color: var(--text-secondary); }
    .hero-event h1 { font-size: 2rem; }
    .grid-cards { grid-template-columns: 1fr; gap: 24px; }
    .widget { margin-bottom: 20px; }
}
@media (max-width: 576px) {
    .hero-event h1 { font-size: 1.6rem; }
    .hero-event p { font-size: 1rem; }
}
</style>

<section class="hero-event">
    <div class="container">
        <h1>🎉 DỰ ÁN TIÊU BIỂU</h1>
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
                            <div style="background: #2a2a2a; width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:var(--primary-gold); font-size:2rem;">🎪</div>
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
                            <li><a href="articles.php?id=<?= $post['id'] ?>"><i class="fas fa-angle-right"></i> <?= htmlspecialchars($post['title']) ?></a></li>
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