<?php
// event_detail.php - Chi tiết sự kiện
require_once 'config.php';

// Định nghĩa hàm fixImagePath nếu chưa có (config.php thiếu)
if (!function_exists('fixImagePath')) {
    function fixImagePath($path) {
        if (empty($path)) return '';
        if (preg_match('#^(https?:)?//#i', $path)) return $path;
        if (strpos($path, 'uploads/') === 0) return $path;
        if (strpos($path, 'uploads/') === 0) return '' . $path;
        return $path;
    }
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: events.php');
    exit;
}

$event = getEventById($id);
if (!$event) {
    // Thử lấy tất cả sự kiện để debug (tạm thời)
    $all = getAllEvents();
    die("Không tìm thấy sự kiện ID = $id. Có " . count($all) . " sự kiện trong DB. 
         Kiểm tra bảng events có bản ghi id=$id và status='published' không.");
}

$page_title = htmlspecialchars($event['title']);

// Lấy 4 sự kiện khác (không bao gồm sự kiện hiện tại)
$allEvents = getLatestEvents(6);
$otherEvents = array_filter($allEvents, function($e) use ($id) {
    return $e['id'] != $id;
});
$otherEvents = array_slice($otherEvents, 0, 4);

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
    font-family: 'Arial', 'Calibri', sans-serif;
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
}
.brand-tagline {
    color: #333;
    font-size: 0.7rem;
    letter-spacing: 2px;
    font-weight: 600;
    opacity: 0.8;
}
.header-nav { flex: 1; display: flex; justify-content: center; }
.nav-list {
    display: flex;
    list-style: none;
    gap: 16px;
}
.nav-link {
    color: var(--primary-gold);
    padding: 8px 20px;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.95rem;
    border-radius: 30px;
    transition: var(--transition);
    background: rgba(212, 161, 71, 0.08);
    border: 1px solid transparent;
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
}
.nav-toggle {
    display: none;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 10px;
    color: white;
    padding: 8px;
    cursor: pointer;
}
.nav-toggle:hover {
    background: var(--primary-gold);
    color: #1a1a1a;
}
.header-cta { flex-shrink: 0; display: flex; gap: 8px; }
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
    box-shadow: var(--shadow-gold);
    transition: var(--transition);
}
.btn-phone:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(212,161,71,0.5);
}

/* ==================== EVENT DETAIL HERO ==================== */
.event-detail-hero {
    background: linear-gradient(120deg, #0f172a, #1a1a2e);
    padding: 60px 0;
    text-align: center;
    margin-bottom: 48px;
    border-bottom: 2px solid var(--primary-gold);
}
.event-detail-hero h1 {
    font-size: 2.8rem;
    font-weight: 800;
    margin-bottom: 16px;
    color: var(--primary-gold);
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}
.event-detail-hero .event-meta {
    font-size: 1rem;
    display: flex;
    justify-content: center;
    gap: 24px;
    flex-wrap: wrap;
    color: var(--text-secondary);
}
.event-detail-hero .event-meta i {
    margin-right: 6px;
    color: var(--primary-gold);
}

/* ==================== NÚT QUAY LẠI ==================== */
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-light));
    color: #1a1a1a;
    padding: 10px 24px;
    border-radius: 40px;
    text-decoration: none;
    font-weight: 700;
    transition: var(--transition);
    box-shadow: var(--shadow-gold);
    margin-bottom: 20px;
    border: none;
    cursor: pointer;
}
.btn-back:hover {
    transform: translateX(-3px);
    box-shadow: 0 8px 18px rgba(212,161,71,0.4);
    background: linear-gradient(135deg, var(--primary-gold-light), var(--primary-gold));
}

/* ==================== NỘI DUNG CHÍNH ==================== */
.event-detail-content {
    background: linear-gradient(135deg, #1A1A1A, #242424);
    border-radius: 28px;
    padding: 40px;
    margin-bottom: 60px;
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(212,161,71,0.2);
}
.event-detail-content img {
    width: 100%;
    max-height: 500px;
    object-fit: cover;
    border-radius: 20px;
    margin-bottom: 30px;
    border: 1px solid rgba(212,161,71,0.3);
}
.event-detail-content .description {
    font-size: 1.05rem;
    line-height: 1.8;
    color: var(--text-secondary);
}
.event-detail-content .description p,
.event-detail-content .description li {
    color: var(--text-secondary);
}

/* ==================== CÁC SỰ KIỆN KHÁC ==================== */
.other-events {
    margin: 60px 0 40px;
}
.other-title {
    font-size: 1.8rem;
    font-weight: 700;
    text-align: center;
    margin-bottom: 30px;
    position: relative;
    padding-bottom: 15px;
    color: var(--primary-gold);
}
.other-title::before {
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
.events-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
}
.event-item {
    background: linear-gradient(135deg, #1A1A1A, #242424);
    border-radius: 24px;
    overflow: hidden;
    transition: var(--transition);
    text-decoration: none;
    color: inherit;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    border: 1px solid rgba(212,161,71,0.2);
    display: flex;
    flex-direction: column;
}
.event-item:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
    border-color: rgba(212,161,71,0.5);
}
.event-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}
.event-item .info {
    padding: 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.event-item h4 {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 10px;
    color: var(--text-primary);
    line-height: 1.4;
}
.event-item .date {
    font-size: 0.85rem;
    color: var(--primary-gold);
    margin-bottom: 12px;
}
.event-item p {
    font-size: 0.9rem;
    color: var(--text-secondary);
    line-height: 1.5;
    margin-bottom: 12px;
}
.read-more {
    margin-top: auto;
    font-weight: 600;
    font-size: 0.85rem;
    color: var(--primary-gold);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: var(--transition);
}
.read-more:hover {
    color: var(--primary-gold-light);
    transform: translateX(5px);
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
    .events-grid { gap: 24px; }
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
    .event-detail-hero h1 { font-size: 2rem; }
    .event-detail-content { padding: 24px; }
    .event-detail-content img { max-height: 300px; }
    .events-grid { grid-template-columns: 1fr; }
}
@media (max-width: 576px) {
    .event-detail-hero h1 { font-size: 1.6rem; }
    .event-detail-hero .event-meta { flex-direction: column; gap: 8px; align-items: center; }
    .other-title { font-size: 1.4rem; }
}
</style>

<section class="event-detail-hero">
    <div class="container">
        <h1><?= htmlspecialchars($event['title']) ?></h1>
        <div class="event-meta">
            <span><i class="far fa-calendar-alt"></i> Đăng: <?= date('d/m/Y', strtotime($event['created_at'])) ?></span>
            <?php if (!empty($event['event_date'])): ?>
            <span><i class="fas fa-clock"></i> Diễn ra: <?= date('d/m/Y', strtotime($event['event_date'])) ?></span>
            <?php endif; ?>
            <?php if ($event['featured']): ?>
            <span><i class="fas fa-star"></i> Sự kiện nổi bật</span>
            <?php endif; ?>
        </div>
    </div>
</section>

<div class="container">
    <a href="events.php" class="btn-back"><i class="fas fa-arrow-left"></i> Quay lại danh sách sự kiện</a>
    
    <div class="event-detail-content">
        <?php if (!empty($event['image'])): ?>
            <img src="<?= htmlspecialchars(fixImagePath($event['image'])) ?>" alt="<?= htmlspecialchars($event['title']) ?>">
        <?php endif; ?>
        <div class="description">
            <?= cleanText($event['description']) ?>
        </div>
    </div>

    <?php if (!empty($otherEvents)): ?>
    <div class="other-events">
        <div class="other-title">📌 Các sự kiện khác</div>
        <div class="events-grid">
            <?php foreach ($otherEvents as $other): ?>
                <a href="event_detail.php?id=<?= $other['id'] ?>" class="event-item">
                    <?php if (!empty($other['image'])): ?>
                        <img src="<?= htmlspecialchars(fixImagePath($other['image'])) ?>" alt="<?= htmlspecialchars($other['title']) ?>">
                    <?php else: ?>
                        <div style="height:200px; background:#2a2a2a; display:flex; align-items:center; justify-content:center; color:var(--primary-gold);">📸</div>
                    <?php endif; ?>
                    <div class="info">
                        <h4><?= htmlspecialchars($other['title']) ?></h4>
                        <div class="date"><i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($other['created_at'])) ?></div>
                        <p><?= cleanText(substr($other['description'] ?? '', 0, 100)) ?>...</p>
                        <div class="read-more">Xem chi tiết <i class="fas fa-arrow-right"></i></div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php else: ?>
        <div style="text-align: center; padding: 40px; color: var(--text-secondary);">Hiện chưa có sự kiện nào khác.</div>
    <?php endif; ?>
</div>

<?php include 'includes/fe/footer.php'; ?>