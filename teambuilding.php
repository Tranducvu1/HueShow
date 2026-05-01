<?php
// teambuilding.php - Danh sách dịch vụ mới nhất (4 dịch vụ)
require_once 'config.php';

// Đảm bảo hàm fixImagePath tồn tại
if (!function_exists('fixImagePath')) {
    function fixImagePath($path) {
        if (empty($path)) return '';
        if (preg_match('#^(https?:)?//#i', $path)) return $path;
        if (strpos($path, 'uploads/') === 0) return $path;
        return $path;
    }
}

$page_title = 'Dịch vụ nổi bật';

// Lấy 4 dịch vụ mới nhất (đã publish)
if (function_exists('getLatestServices')) {
    $services = getLatestServices(4);
} else {
    // Fallback dữ liệu mẫu (phòng khi hàm chưa được định nghĩa trong config)
    $services = [
        ['id' => 1, 'title' => 'Setup sân khấu chuyên nghiệp', 'description' => 'Thiết kế và thi công sân khấu theo yêu cầu, hệ thống âm thanh ánh sáng hiện đại...', 'image' => 'https://event5.mauthemewp.com/wp-content/uploads/2018/05/post8-300x180.jpg', 'created_at' => date('Y-m-d H:i:s'), 'event_date' => date('Y-m-d')],
        ['id' => 2, 'title' => 'MC – Dẫn chương trình chuyên nghiệp', 'description' => 'Đội ngũ MC giàu kinh nghiệm, phong cách đa dạng, phù hợp mọi sự kiện...', 'image' => 'https://event5.mauthemewp.com/wp-content/uploads/2018/05/post7-300x180.jpg', 'created_at' => date('Y-m-d H:i:s'), 'event_date' => date('Y-m-d')],
        ['id' => 3, 'title' => 'Vũ đoàn & Dancer chuyên nghiệp', 'description' => 'Các tiết mục múa hiện đại, Kpop, contemporary, dân gian biến tấu, phục vụ sự kiện đa dạng...', 'image' => 'https://event5.mauthemewp.com/wp-content/uploads/2018/05/post4-300x195.jpg', 'created_at' => date('Y-m-d H:i:s'), 'event_date' => date('Y-m-d')],
        ['id' => 4, 'title' => 'Kịch bản sự kiện trọn gói', 'description' => 'Viết kịch bản chi tiết, kịch bản dẫn, kịch bản sân khấu hóa, phù hợp với mọi loại hình chương trình...', 'image' => 'https://event5.mauthemewp.com/wp-content/uploads/2018/05/post3-300x195.jpg', 'created_at' => date('Y-m-d H:i:s'), 'event_date' => date('Y-m-d')],
    ];
}

// Lấy bài viết mới cho sidebar
$recentPosts = getLatestArticles(5);

include 'includes/fe/header.php';
?>

<style>
    /* ========== TEAMBUILDING SPECIFIC STYLES (giữ nguyên giao diện) ========== */
    .hero-teambuilding {
        background: linear-gradient(120deg, #0f172a, #1e293b);
        color: white;
        padding: 60px 0;
        text-align: center;
        margin-bottom: 48px;
    }
    .hero-teambuilding h1 {
        font-size: 2.8rem;
        font-weight: 800;
        margin-bottom: 16px;
    }
    .hero-teambuilding p {
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
        .hero-teambuilding h1 {
            font-size: 2rem;
        }
    }
</style>

<section class="hero-teambuilding">
    <div class="container">
        <h1>✨ DỊCH VỤ TIÊU BIỂU</h1>
        <p>Trọn gói từ ý tưởng đến sân khấu – HueShow đồng hành cùng sự kiện của bạn.</p>
    </div>
</section>

<div class="container">
    <div class="two-columns">
        <div class="main-col">
            <div class="grid-cards">
                <?php foreach ($services as $service): ?>
                <a href="service_detail.php?id=<?= $service['id'] ?>" class="event-card">
                    <div class="card-img">
                        <?php if (!empty($service['image'])): ?>
                            <img src="<?= htmlspecialchars(fixImagePath($service['image'])) ?>" alt="<?= htmlspecialchars($service['title']) ?>">
                        <?php else: ?>
                            <div style="background: linear-gradient(135deg,#0f172a,#334155); width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:white; font-size:2rem;">🎭</div>
                        <?php endif; ?>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title"><?= htmlspecialchars($service['title']) ?></h3>
                        <div class="card-date">
                            <i class="far fa-calendar-alt"></i> 
                            <?= !empty($service['event_date']) ? date('d/m/Y', strtotime($service['event_date'])) : 'Liên hệ' ?>
                        </div>
                        <p class="card-desc"><?= cleanText(substr($service['description'] ?? '', 0, 100)) ?>...</p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="side-col">
            <div class="widget">
                <div class="widget-title">🎤 DẤU ẤN SỰ KIỆN THỰC TẾ</div>
                <div style="font-size:0.95rem; line-height:1.5; text-align: justify;">
                    <strong>Lễ hội âm nhạc quốc tế, Concert "Chào năm mới" & các show diễn nghệ thuật đỉnh cao</strong> quy tụ hàng chục nghìn khán giả mỗi đêm.<br><br>
                    <strong>Huế Show</strong> tự hào mang đến không gian âm nhạc <strong>bùng nổ</strong>, hệ thống âm thanh <strong>line-array L’Acoustics</strong>, sân khấu <strong>LED mapping 3D</strong> cùng hiệu ứng ánh sáng laser <strong>đẳng cấp quốc tế</strong>, kiến tạo những trải nghiệm nghệ thuật <strong>khó quên</strong> cho khán giả và nghệ sĩ.
                </div>
            </div>
            <div class="widget">
                <div class="widget-title">🏀 DẤU ẤN SỰ KIỆN THỂ THAO CHUYÊN NGHIỆP</div>
                <div style="font-size:0.95rem; line-height:1.5; text-align: justify;">
                    <strong>Giải Vô Địch Bóng Rổ 3x3 U20 và U23 Quốc Gia</strong> cùng các giải đấu thể thao quy mô lớn.<br><br>
                    <strong>Huế Show</strong> tự hào mang đến không gian thi đấu bùng nổ, <strong>chuyên nghiệp</strong>, đáp ứng các tiêu chuẩn khắt khe nhất của thể thao thành tích cao, kiến tạo những khoảnh khắc <strong>thăng hoa</strong> cho vận động viên và khán giả.
                </div>
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
                <div class="widget-title">🎥 Video nổi bật</div>
                <iframe src="https://www.youtube.com/embed/_ltAd7y-jRw" frameborder="0" allowfullscreen></iframe>
            </div>
            <div class="widget">
                <div class="widget-title">🤝 Đối tác tiêu biểu</div>
                <p>Dự án nụ cười Việt ,VPBank, Sony, Agribank, Honda, Sunhouse, Tetra Pak, Boehringer...</p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/fe/footer.php'; ?>

<script>
    document.querySelectorAll('.event-card').forEach(card => {
        card.addEventListener('click', function() {
            this.style.transform = 'scale(0.97)';
            setTimeout(() => { this.style.transform = ''; }, 120);
        });
    });
</script>