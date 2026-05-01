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
    .service-detail-hero {
        background: linear-gradient(120deg, #0f172a, #1e293b);
        color: white;
        padding: 60px 0;
        text-align: center;
        margin-bottom: 48px;
    }
    .service-detail-hero h1 { font-size: 2.8rem; font-weight: 800; margin-bottom: 16px; }
    .service-meta { font-size: 1rem; display: flex; justify-content: center; gap: 24px; flex-wrap: wrap; }
    .service-meta i { margin-right: 6px; color: #facc15; }
    .service-detail-content {
        background: white; border-radius: 28px; padding: 40px; margin-bottom: 60px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .service-detail-content img {
        width: 100%; max-height: 500px; object-fit: cover; border-radius: 20px; margin-bottom: 30px;
    }
    .description { font-size: 1.1rem; line-height: 1.8; color: #334155; }
    .other-services { margin: 60px 0 40px; }
    .other-title {
        font-size: 1.8rem; font-weight: 700; text-align: center; margin-bottom: 30px;
        position: relative; padding-bottom: 15px;
    }
    .other-title::before {
        content: ''; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%);
        width: 80px; height: 3px; background: linear-gradient(90deg, #facc15, #eab308);
        border-radius: 2px;
    }
    .services-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px;
    }
    .service-item {
        background: white; border-radius: 24px; overflow: hidden; transition: 0.3s;
        text-decoration: none; color: inherit; box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        display: flex; flex-direction: column;
    }
    .service-item:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
    .service-item img { width: 100%; height: 200px; object-fit: cover; }
    .service-item .info { padding: 20px; flex: 1; display: flex; flex-direction: column; }
    .service-item h4 { font-size: 1.2rem; font-weight: 600; margin-bottom: 10px; }
    .service-item .date { font-size: 0.85rem; color: #6b7280; margin-bottom: 12px; }
    .service-item p { font-size: 0.9rem; color: #4b5563; margin-bottom: 12px; }
    .read-more { margin-top: auto; font-weight: 600; font-size: 0.85rem; color: #facc15; display: inline-flex; align-items: center; gap: 6px; }
    .btn-back {
        display: inline-flex; align-items: center; gap: 8px;
        background: linear-gradient(95deg, #facc15, #eab308); color: #0f172a;
        padding: 10px 24px; border-radius: 40px; text-decoration: none; font-weight: 600;
        transition: 0.2s; margin-bottom: 20px;
    }
    .btn-back:hover { transform: translateX(-3px); }
    @media (max-width: 768px) {
        .service-detail-hero h1 { font-size: 2rem; }
        .service-detail-content { padding: 24px; }
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
    <a href="services.php" class="btn-back"><i class="fas fa-arrow-left"></i> Quay lại danh sách dịch vụ</a>
    
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
                        <div style="height:200px; background:#e2e8f0; display:flex; align-items:center; justify-content:center;">🎬</div>
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