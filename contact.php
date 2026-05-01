<?php
// contact.php - Trang liên hệ với form, bản đồ và link chỉ đường
require_once 'config.php';
include 'includes/fe/header.php';

$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Vui lòng điền đầy đủ thông tin bắt buộc.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ.';
    } else {
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ - HueShow | Gửi yêu cầu tư vấn</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        .hero-contact { background: linear-gradient(120deg, #0f172a, #1e293b); color: white; padding: 60px 0; text-align: center; margin-bottom: 48px; }
        .hero-contact h1 { font-size: 2.8rem; font-weight: 800; margin-bottom: 16px; }
        .hero-contact p { max-width: 700px; margin: 0 auto; font-size: 1.1rem; opacity: 0.9; }

        .two-columns { display: flex; gap: 40px; flex-wrap: wrap; margin: 40px 0; }
        .main-col { flex: 2; min-width: 260px; }
        .side-col { flex: 1; min-width: 260px; position: sticky; top: 100px; align-self: start; }

        .contact-form-container { background: white; border-radius: 28px; padding: 32px; box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .contact-form-container h3 { font-size: 1.5rem; margin-bottom: 20px; color: #0f172a; border-left: 5px solid #facc15; padding-left: 15px; }
        .form-group { margin-bottom: 20px; }
        .form-group input, .form-group textarea { width: 100%; padding: 14px 18px; border: 1px solid #e2e8f0; border-radius: 20px; font-family: inherit; font-size: 0.95rem; transition: 0.2s; }
        .form-group input:focus, .form-group textarea:focus { outline: none; border-color: #facc15; box-shadow: 0 0 0 3px rgba(250,204,21,0.2); }
        .submit-btn { background: linear-gradient(95deg, #facc15, #eab308); color: #0f172a; font-weight: bold; padding: 14px 32px; border: none; border-radius: 40px; cursor: pointer; font-size: 1rem; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; }
        .submit-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(250,204,21,0.3); }
        .submit-btn:active { transform: scale(0.96); transition: transform 0.05s; }
        .alert { padding: 12px 20px; border-radius: 20px; margin-bottom: 20px; }
        .alert-success { background: #dcfce7; color: #166534; border-left: 4px solid #22c55e; }
        .alert-error { background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; }

        .info-card { background: white; border-radius: 28px; padding: 24px; margin-bottom: 30px; box-shadow: 0 8px 20px rgba(0,0,0,0.05); }
        .info-card h4 { font-size: 1.2rem; margin-bottom: 16px; border-left: 5px solid #facc15; padding-left: 14px; }
        .info-item { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; }
        .info-item i { width: 32px; color: #facc15; font-size: 1.2rem; }
        .info-item a { color: #1e293b; text-decoration: none; transition: color 0.2s; }
        .info-item a:hover { color: #facc15; text-decoration: underline; }
        .map-container { border-radius: 20px; overflow: hidden; margin-top: 20px; }
        .map-container iframe { width: 100%; height: 250px; border: 0; }

        .widget { background: white; border-radius: 24px; padding: 24px; margin-bottom: 30px; box-shadow: 0 8px 20px rgba(0,0,0,0.05); }
        .widget-title { font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; border-left: 5px solid #facc15; padding-left: 14px; }
        .recent-posts { list-style: none; }
        .recent-posts li { margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0; }
        .recent-posts a { text-decoration: none; color: #1e293b; font-weight: 500; display: flex; align-items: center; gap: 8px; transition: 0.3s; }
        .recent-posts a:hover { color: #facc15; }
        .video-widget iframe { width: 100%; border-radius: 16px; }
        @media (max-width: 768px) {
            .header-inner { flex-direction: column; text-align: center; }
            .two-columns { flex-direction: column; }
            .hero-contact h1 { font-size: 2rem; }
        }
    </style>
</head>
<body>


<section class="hero-contact">
    <div class="container">
        <h1>📞 Liên hệ với chúng tôi</h1>
        <p>Hãy để lại thông tin, đội ngũ HueShow sẽ tư vấn giải pháp sự kiện và team building phù hợp nhất cho bạn.</p>
    </div>
</section>

<div class="container">
    <div class="two-columns">
        <div class="main-col">
            <div class="contact-form-container">
                <h3>✉️ Gửi yêu cầu tư vấn</h3>
                <?php if ($success): ?>
                    <div class="alert alert-success">Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.</div>
                <?php elseif ($error): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Họ và tên *" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email *" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <input type="tel" name="phone" placeholder="Số điện thoại" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <textarea name="message" rows="5" placeholder="Nội dung cần tư vấn *" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="submit-btn"><i class="fas fa-paper-plane"></i> Gửi liên hệ</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="side-col">
            <!-- Thông tin liên hệ + map (link chỉ đường) -->
            <div class="info-card">
                <h4><i class="fas fa-map-pin"></i> Thông tin HueShow</h4>
                <div class="info-item">
                    <i class="fas fa-building"></i>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=26+Nguy%E1%BB%85n+C%C3%B4ng+Tr%E1%BB%A9,+Th%C3%A0nh+ph%E1%BB%91+Hu%E1%BA%BF" target="_blank" rel="noopener noreferrer">
                        26 Nguyễn Công Trứ, Thành Phố Huế
                    </a>
                </div>
                <div class="info-item">
                    <i class="fas fa-phone-alt"></i>
                    <a href="tel:0989898989">097 966 37 27</a>
                </div>
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:sukienhueshow@gmail.com">sukienhueshow@gmail.com</a>
                </div>
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <span>Thứ 2 – Thứ 7: 8:00 - 18:00</span>
                </div>
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1916.217476395723!2d107.590638017239!3d16.46393118850395!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3141a12f2f5f5f5f%3A0x0!2zMjYgTmd1eeG7hW4gQ8O0bmcgVHLDuiwgVHAuIEh14bq_LCBUaMOgbmggcGjhu5EgSOG7hywgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1700000000000!5m2!1svi!2s" allowfullscreen loading="lazy"></iframe>
                </div>
            </div>

            <div class="widget">
                <div class="widget-title">📰 Bài viết mới</div>
                <ul class="recent-posts">
                    <?php
                    if (function_exists('getAllEvents')) {
                        $recentPosts = getAllEvents(5);
                        foreach ($recentPosts as $post) {
                            echo '<li><a href="articles.php?id=' . $post['id'] . '"><i class="fas fa-angle-right" style="color:#facc15;"></i> ' . htmlspecialchars($post['title']) . '</a></li>';
                        }
                    } else {
                        echo '<li>Chưa có bài viết</li>';
                    }
                    ?>
                </ul>
            </div>

            <div class="widget video-widget">
                <div class="widget-title">🎥 Video giới thiệu</div>
                <iframe src="https://www.youtube.com/embed/_ltAd7y-jRw" frameborder="0" allowfullscreen></iframe>
            </div>

            <div class="widget">
                <div class="widget-title">📂 Chuyên mục</div>
                <ul style="list-style: none;">
                    <li><a href="#" style="display:block; margin-bottom:8px; color:#1e293b;"><i class="fas fa-tag" style="color:#facc15;"></i> Team Building</a></li>
                    <li><a href="#" style="display:block; margin-bottom:8px; color:#1e293b;"><i class="fas fa-tag" style="color:#facc15;"></i> Tổ chức sự kiện</a></li>
                    <li><a href="#" style="display:block; margin-bottom:8px; color:#1e293b;"><i class="fas fa-tag" style="color:#facc15;"></i> Hội nghị khách hàng</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/fe/footer.php'; ?>


<a href="tel:0989898989" class="hotline-fixed"><i class="fas fa-phone-alt"></i> Hotline: 0989.898.989</a>

<script>
    document.querySelector('.submit-btn')?.addEventListener('click', function() {
        this.style.transform = 'scale(0.96)';
        setTimeout(() => { this.style.transform = ''; }, 100);
    });
</script>
</body>
</html>