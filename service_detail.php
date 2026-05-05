<?php
require_once 'config.php';

if (!function_exists('fixImagePath')) {
    function fixImagePath($path) {
        if (empty($path)) return '';
        if (preg_match('#^(https?:)?//#i', $path)) return $path;
        if (strpos($path, 'uploads/') === 0) return $path;
        return $path;
    }
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: services.php');
    exit;
}

$service = getServiceById($id);
if (!$service || $service['status'] !== 'published') {
    header('Location: services.php');
    exit;
}

$page_title = htmlspecialchars($service['title']);

// Lấy 4 dịch vụ khác
$allServices = getLatestServices(6);
$otherServices = array_filter($allServices, function($s) use ($id) {
    return $s['id'] != $id;
});
$otherServices = array_slice($otherServices, 0, 4);

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

.service-detail-hero {
    background: linear-gradient(120deg, #0f172a, #1a1a2e);
    color: var(--text-primary);
    padding: 60px 0;
    text-align: center;
    margin-bottom: 48px;
    border-bottom: 2px solid var(--primary-gold);
}
.service-detail-hero h1 {
    font-family: 'Arial', 'Calibri', sans-serif;
    font-size: 2.8rem;
    font-weight: 800;
    margin-bottom: 16px;
    color: var(--primary-gold);
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}
.service-meta {
    font-family: 'Arial', 'Calibri', sans-serif;
    font-size: 1rem;
    display: flex;
    justify-content: center;
    gap: 24px;
    flex-wrap: wrap;
    color: var(--text-secondary);
}
.service-meta i { margin-right: 6px; color: var(--primary-gold); }

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

.service-detail-content {
    background: linear-gradient(135deg, #1A1A1A, #242424);
    border-radius: 28px;
    padding: 40px;
    margin-bottom: 60px;
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(212,161,71,0.2);
}
.service-detail-content img {
    width: 100%;
    max-height: 500px;
    object-fit: cover;
    border-radius: 20px;
    margin-bottom: 30px;
    border: 1px solid rgba(212,161,71,0.3);
}
.description {
    font-size: 1.05rem;
    line-height: 1.8;
    color: var(--text-secondary);
}
.description p, .description li {
    color: var(--text-secondary);
}

.other-services {
    margin: 60px 0 40px;
}
.other-title {
    font-family: 'Arial', 'Calibri', sans-serif;
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

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
}
.service-item {
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
.service-item:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
    border-color: rgba(212,161,71,0.5);
}
.service-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}
.service-item .info {
    padding: 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.service-item h4 {
    font-family: 'Arial', 'Calibri', sans-serif;
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 10px;
    color: var(--text-primary);
}
.service-item .date {
    font-size: 0.85rem;
    color: var(--primary-gold);
    margin-bottom: 12px;
}
.service-item p {
    font-family: 'Arial', 'Calibri', sans-serif;
    font-size: 0.9rem;
    color: var(--text-secondary);
    margin-bottom: 12px;
    line-height: 1.5;
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

/* Responsive */
@media (max-width: 768px) {
    .service-detail-hero h1 { font-size: 2rem; }
    .service-detail-content { padding: 24px; }
    .services-grid { grid-template-columns: 1fr; }
    .service-meta { flex-direction: column; gap: 8px; align-items: center; }
}
@media (max-width: 576px) {
    .service-detail-hero h1 { font-size: 1.6rem; }
    .other-title { font-size: 1.4rem; }
}
</style>

<section class="service-detail-hero">
    <div class="container">
        <h1><?= htmlspecialchars($service['title']) ?></h1>
        <div class="service-meta">
            <span><i class="far fa-calendar-alt"></i> Đăng: <?= date('d/m/Y', strtotime($service['created_at'])) ?></span>
            <?php if (!empty($service['event_date'])): ?>
            <span><i class="fas fa-clock"></i> Thực hiện: <?= date('d/m/Y', strtotime($service['event_date'])) ?></span>
            <?php endif; ?>
            <?php if ($service['featured']): ?>
            <span><i class="fas fa-star"></i> Dịch vụ nổi bật</span>
            <?php endif; ?>
        </div>
    </div>
</section>

<div class="container">
    <a href="teambuilding.php" class="btn-back"><i class="fas fa-arrow-left"></i> Quay lại danh sách dịch vụ</a>
    
    <div class="service-detail-content">
        <?php if (!empty($service['image'])): ?>
            <img src="<?= htmlspecialchars(fixImagePath($service['image'])) ?>" alt="<?= htmlspecialchars($service['title']) ?>">
        <?php endif; ?>
        <div class="description">
            <?= cleanText($service['description']) ?>
        </div>
    </div>

    <?php if (!empty($otherServices)): ?>
    <div class="other-services">
        <div class="other-title">📌 Các dịch vụ khác</div>
        <div class="services-grid">
            <?php foreach ($otherServices as $other): ?>
                <a href="service_detail.php?id=<?= $other['id'] ?>" class="service-item">
                    <?php if (!empty($other['image'])): ?>
                        <img src="<?= htmlspecialchars(fixImagePath($other['image'])) ?>" alt="<?= htmlspecialchars($other['title']) ?>">
                    <?php else: ?>
                        <div style="height:200px; background: #2a2a2a; display:flex; align-items:center; justify-content:center; color: var(--primary-gold);">🎬</div>
                    <?php endif; ?>
                    <div class="info">
                        <h4><?= htmlspecialchars($other['title']) ?></h4>
                        <div class="date"><i class="far fa-calendar-alt"></i> <?= !empty($other['event_date']) ? date('d/m/Y', strtotime($other['event_date'])) : 'Liên hệ' ?></div>
                        <p><?= htmlspecialchars(substr($other['description'] ?? '', 0, 100)) ?>...</p>
                        <div class="read-more">Xem chi tiết <i class="fas fa-arrow-right"></i></div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/fe/footer.php'; ?>