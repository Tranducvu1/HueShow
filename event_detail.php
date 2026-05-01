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
    /* Giữ nguyên style như bạn đã viết */
    .event-detail-hero {
        background: linear-gradient(120deg, #0f172a, #1e293b);
        color: white;
        padding: 60px 0;
        text-align: center;
        margin-bottom: 48px;
    }
    .event-detail-hero h1 { font-size: 2.8rem; font-weight: 800; margin-bottom: 16px; }
    .event-detail-hero .event-meta {
        font-size: 1rem; display: flex; justify-content: center; gap: 24px; flex-wrap: wrap;
    }
    .event-detail-hero .event-meta i { margin-right: 6px; color: #facc15; }
    .event-detail-content {
        background: white; border-radius: 28px; padding: 40px; margin-bottom: 60px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .event-detail-content img {
        width: 100%; max-height: 500px; object-fit: cover; border-radius: 20px; margin-bottom: 30px;
    }
    .event-detail-content .description { font-size: 1.1rem; line-height: 1.8; color: #334155; }
    .other-events { margin: 60px 0 40px; }
    .other-title {
        font-size: 1.8rem; font-weight: 700; text-align: center; margin-bottom: 30px;
        position: relative; padding-bottom: 15px;
    }
    .other-title::before {
        content: ''; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%);
        width: 80px; height: 3px; background: linear-gradient(90deg, #facc15, #eab308);
        border-radius: 2px;
    }
    .events-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px;
    }
    .event-item {
        background: white; border-radius: 24px; overflow: hidden; transition: 0.3s;
        text-decoration: none; color: inherit; box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        display: flex; flex-direction: column;
    }
    .event-item:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
    .event-item img { width: 100%; height: 200px; object-fit: cover; }
    .event-item .info { padding: 20px; flex: 1; display: flex; flex-direction: column; }
    .event-item h4 { font-size: 1.2rem; font-weight: 600; margin-bottom: 10px; line-height: 1.4; }
    .event-item .date { font-size: 0.85rem; color: #6b7280; margin-bottom: 12px; }
    .event-item p { font-size: 0.9rem; color: #4b5563; line-height: 1.5; margin-bottom: 12px; }
    .read-more { margin-top: auto; font-weight: 600; font-size: 0.85rem; color: #facc15; display: inline-flex; align-items: center; gap: 6px; }
    .btn-back {
        display: inline-flex; align-items: center; gap: 8px;
        background: linear-gradient(95deg, #facc15, #eab308); color: #0f172a;
        padding: 10px 24px; border-radius: 40px; text-decoration: none; font-weight: 600;
        transition: 0.2s; margin-bottom: 20px;
    }
    .btn-back:hover { transform: translateX(-3px); box-shadow: 0 5px 12px rgba(250,204,21,0.3); }
    @media (max-width: 768px) {
        .event-detail-hero h1 { font-size: 2rem; }
        .event-detail-content { padding: 24px; }
        .event-detail-content img { max-height: 300px; }
    }
    @media (max-width: 576px) { .events-grid { grid-template-columns: 1fr; } }
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
                        <div style="height:200px; background:#e2e8f0; display:flex; align-items:center; justify-content:center;">📸</div>
                    <?php endif; ?>
                    <div class="info">
                        <h4><?= htmlspecialchars($other['title']) ?></h4>
                        <div class="date"><i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($other['created_at'])) ?></div>
                       <div class="description">
   <?= cleanText(htmlspecialchars_decode(stripslashes($event['description']), ENT_QUOTES)) ?>
</div>
                        <div class="read-more">Xem chi tiết <i class="fas fa-arrow-right"></i></div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php else: ?>
        <div style="text-align: center; padding: 40px;">Hiện chưa có sự kiện nào khác.</div>
    <?php endif; ?>
</div>

<?php include 'includes/fe/footer.php'; ?>