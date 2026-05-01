<?php
require_once 'config.php';

$company_name = "HueShow";
$slogan = "Kiến tạo sự kiện – Kết nối thành công";
$founded_year = "2020";

$recentPosts = getLatestArticles(4);

include 'includes/fe/header.php';
?>

<style>
    .intro-hero {
        background: linear-gradient(120deg, #0f172a 0%, #1e293b 100%);
        color: white;
        padding: 80px 0;
        text-align: center;
        margin-bottom: 40px;
    }
    .intro-hero h1 {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 16px;
        letter-spacing: -0.02em;
    }
    .intro-hero p {
        font-size: 1.2rem;
        max-width: 700px;
        margin: 0 auto;
        opacity: 0.9;
    }
    .row-large {
        display: flex;
        gap: 40px;
        flex-wrap: wrap;
        margin-bottom: 40px;
    }
    .main-content {
        flex: 2;
        min-width: 260px;
        background: white;
        border-radius: 28px;
        padding: 28px;
        box-shadow: 0 20px 35px -12px rgba(0,0,0,0.08);
    }
    .sidebar {
        flex: 1;
        min-width: 260px;
        position: sticky;
        top: 100px;
        align-self: start;
    }
    .main-content h2 {
        font-size: 1.8rem;
        margin: 24px 0 12px;
        color: #0f172a;
        font-weight: 700;
    }
    .main-content p {
        margin-bottom: 1rem;
        line-height: 1.6;
        color: #334155;
    }
    .highlight-box {
        background: #fef9e3;
        border-left: 5px solid #facc15;
        padding: 16px 20px;
        border-radius: 20px;
        margin: 20px 0;
    }
    .btn-group {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin: 24px 0 16px;
    }
    .btn-icon {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 24px;
        background: linear-gradient(105deg, #facc15, #f59e0b);
        color: #0f172a;
        font-weight: 700;
        border-radius: 60px;
        text-decoration: none;
        transition: all 0.25s ease;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        border: none;
        cursor: pointer;
    }
    .btn-icon i {
        font-size: 1.1rem;
    }
    .btn-icon:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 18px -6px rgba(234,179,8,0.5);
        background: linear-gradient(105deg, #f59e0b, #d97706);
    }
    .btn-outline-icon {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 24px;
        background: linear-gradient(105deg, #facc15, #f59e0b);
        border: none;
        color: #0f172a;
        font-weight: 700;
        border-radius: 60px;
        text-decoration: none;
        transition: all 0.25s ease;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
    .btn-outline-icon:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 18px -6px rgba(234,179,8,0.5);
        background: linear-gradient(105deg, #f59e0b, #d97706);
    }

    .widget {
        background: white;
        border-radius: 24px;
        padding: 20px;
        margin-bottom: 24px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }
    .widget-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 16px;
        border-left: 5px solid #facc15;
        padding-left: 14px;
    }
    .recent-posts, .categories {
        list-style: none;
    }
    .recent-posts li, .categories li {
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e2e8f0;
    }
    .video-widget video {
        width: 100%;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .services-section {
        margin: 60px 0 40px;
    }
    .section-title {
        text-align: center;
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 40px;
        color: #0f172a;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }
    .section-title::after {
        content: '';
        display: block;
        width: 70px;
        height: 3px;
        background: linear-gradient(90deg, #facc15, #f59e0b);
        margin: 12px auto 0;
    }
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
    }
    .service-card {
        background: #1f2937;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    .service-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(250,204,21,0.15);
    }
    .service-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }
    .service-content {
        padding: 24px;
    }
    .founder-avatar {
        flex: 0 0 220px;
        text-align: center;
    }
    .avatar-wrapper {
        width: 200px;
        height: 200px;
        margin: 0 auto;
        border-radius: 50%;
        background: linear-gradient(135deg, #facc15, #f59e0b);
        padding: 4px;
        box-shadow: 0 20px 30px -12px rgba(0,0,0,0.25);
        transition: transform 0.3s ease;
    }
    .avatar-wrapper:hover {
        transform: scale(1.02);
    }
    .founder-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid white;
        display: block;
        background: #f1f5f9;
    }
    .avatar-social {
        margin-top: 16px;
        display: flex;
        justify-content: center;
        gap: 16px;
    }
    .avatar-social a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        background: #f1f5f9;
        border-radius: 50%;
        color: #0f172a;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .avatar-social a:hover {
        background: #facc15;
        transform: translateY(-3px);
    }
    .service-title {
        font-size: 1.3rem;
        font-weight: 800;
        color: #facc15;
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .service-title::before {
        content: '';
        display: block;
        width: 50px;
        height: 3px;
        background: #facc15;
        margin-bottom: 10px;
    }
    .service-description {
        color: #d1d5db;
        line-height: 1.6;
        font-size: 0.9rem;
    }
    .founder-info h3 {
        font-size: 1.8rem;
        margin-bottom: 5px;
    }
    .founder-info .title {
        font-size: 1rem;
        margin-bottom: 15px;
    }
    .founder-info p {
        margin-bottom: 12px;
        line-height: 1.6;
    }
    .founder-quote {
        font-style: italic;
        border-left: 4px solid #facc15;
        padding-left: 20px;
        margin-top: 16px;
    }
    .team-section {
        margin: 40px 0 60px;
    }
    .team-title {
        text-align: center;
        font-size: 1.8rem;
        font-weight: 800;
        margin-bottom: 30px;
    }
    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 25px;
    }
    .team-card {
        background: white;
        border-radius: 24px;
        text-align: center;
        padding: 20px 12px;
        transition: 0.3s;
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }
    .team-card:hover {
        transform: translateY(-6px);
    }
    .team-avatar-img {
        width: 90px;
        height: 90px;
        margin: 0 auto 12px;
        border-radius: 50%;
        overflow: hidden;
    }
    .team-card h4 {
        font-size: 1rem;
        margin-bottom: 4px;
    }
    .team-card p {
        font-size: 0.85rem;
        color: #64748b;
    }
    @media (max-width: 768px) {
        .row-large {
            flex-direction: column;
        }
        .intro-hero h1 {
            font-size: 2rem;
        }
        .section-title {
            font-size: 1.6rem;
        }
        .services-grid {
            grid-template-columns: 1fr;
        }
        .founder-grid {
            flex-direction: column;
            text-align: center;
        }
        .founder-quote {
            text-align: left;
        }
        .main-content {
            padding: 20px;
        }
        .sidebar {
            position: static;
        }
        .avatar-wrapper {
            width: 150px;
            height: 150px;
        }
        .founder-avatar {
            flex: 0 0 auto;
        }
        .avatar-social {
            margin-bottom: 10px;
        }
    }
</style>

<!-- Hero Section -->
<section class="intro-hero">
    <div class="container">
        <h1>Chào mừng đến với <?= htmlspecialchars($company_name) ?></h1>
        <p><?= htmlspecialchars($slogan) ?> – Nơi những ý tưởng sáng tạo trở thành hiện thực.</p>
    </div>
</section>

<div class="container">
    <!-- NHÀ SÁNG LẬP -->
    <div class="founder-section">
        <div class="founder-grid" style="display: flex; gap: 40px; flex-wrap: wrap; margin-bottom: 60px;">
            <div class="founder-avatar">
                <div class="avatar-wrapper">
                    <img src="uploads/team/Tran_The_Phong.jpg" 
                         alt="Trần Thế Phong - Nhà sáng lập HueShow" 
                         class="founder-img"
                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?background=facc15&color=0f172a&bold=true&size=180&name=TP';">
                </div>
                <div class="avatar-social">
                    <a href="https://www.facebook.com/share/183np1E25S/" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://zalo.me/0979663727" aria-label="Email"><i class="fas fa-comment-dots"></i></a>
                </div>
            </div>
            <div class="founder-info">
                <h3>Trần Thế Phong</h3>
                <div class="title">Nhà sáng lập & Giám đốc Điều hành</div>
                <p>Với hơn 10 năm kinh nghiệm trong lĩnh vực tổ chức sự kiện và quản lý doanh nghiệp, anh Trần Thế Phong đã sáng lập <strong><?= $company_name ?></strong> với khát vọng đưa dịch vụ team building và event Việt Nam vươn tầm khu vực. Nhờ tầm nhìn chiến lược và sự sáng tạo không ngừng, HueShow đã trở thành đối tác tin cậy của nhiều thương hiệu lớn.</p>
                <div class="founder-quote">
                    <i class="fas fa-quote-left" style="color:#facc15; margin-right:8px;"></i>
                    "Chúng tôi không chỉ tạo ra sự kiện, chúng tôi kiến tạo cảm xúc và kết nối bền vững."
                </div>
            </div>
        </div>
    </div>

    <div class="row-large">
        <div class="main-content">
            <h2>📌 Về chúng tôi</h2>
            <p><strong><?= htmlspecialchars($company_name) ?></strong> được thành lập từ năm <?= htmlspecialchars($founded_year) ?> với sứ mệnh mang đến những chương trình Team Building, sự kiện doanh nghiệp chuyên nghiệp, sáng tạo và giàu cảm xúc. Chúng tôi tin rằng mỗi sự kiện không chỉ là nơi kết nối mà còn là cơ hội để thổi lửa đam mê và xây dựng văn hóa doanh nghiệp bền vững.</p>
            
            <div class="highlight-box">
                <i class="fas fa-quote-left" style="color:#facc15; margin-right:8px;"></i>
                Sự tin tưởng của khách hàng là giá trị tốt nhất mà chúng tôi có.
            </div>

            <p>Tập hợp những chuyên gia giàu kinh nghiệm, hội tụ đầy đủ yếu tố "Tâm - Trí - Lực". Chúng tôi không ngừng sáng tạo để biến mọi ý tưởng thành hiện thực hoàn hảo nhất. Chúng tôi đã tổ chức thành công hàng trăm sự kiện lớn nhỏ cho các tập đoàn, doanh nghiệp trong và ngoài nước.</p>

            <h2>🎯 Lĩnh vực hoạt động chính</h2>
            <ul style="margin-left: 24px; color: #334155; margin-bottom: 24px;">
                <li><strong>Tổ chức sự kiện doanh nghiệp:</strong> Hội nghị, hội thảo, Gala dinner, khai trương, kỷ niệm.</li>
                <li><strong>Team Building:</strong> Các chương trình gắn kết nhân viên, đào tạo kỹ năng mềm, thể thao đồng đội.</li>
                <li><strong>Sản xuất Media & Truyền thông:</strong> Quay phim, chụp ảnh, thiết kế hình ảnh, video clip quảng bá.</li>
                <li><strong>Cho thuê thiết bị & Đạo cụ:</strong> Âm thanh, ánh sáng, sân khấu, dụng cụ team building.</li>
                <li><strong> Hỗ trợ nhân sự – Cộng tác viên sự kiện:</strong> Cung cấp MC chuyên nghiệp, PG (Promotion Girl), khánh tiết, lễ tân, mascot, dancer, nhân viên hậu cần, bảo vệ, kỹ thuật ánh sáng – âm thanh,… Đáp ứng mọi quy mô sự kiện.</li>
            </ul>

            <div class="btn-group">
                <a href="contact.php" class="btn-icon"><i class="fas fa-calendar-check"></i> Nhận tư vấn ngay</a>
                <a href="teambuilding.php" class="btn-outline-icon"><i class="fas fa-users"></i> Xem các chương trình</a>
                <a href="tel:0989898989" class="btn-icon"><i class="fas fa-phone-alt"></i> Gọi hotline</a>
            </div>

            <p style="margin-top: 12px; font-style: italic;">Hãy để <?= htmlspecialchars($company_name) ?> đồng hành cùng bạn trong những sự kiện quan trọng nhất!</p>
        </div>

        <div class="sidebar">
            <div class="widget">
                <div class="widget-title">🤝 Giải pháp trọn gói</div>
                <p><strong>𝓗𝓤𝓔 𝓢𝓗𝓞𝓦 – GIẢI PHÁP TRỌN GÓI CHO MỌI SỰ KIỆN 🎯</strong></p>
                <p>Bạn đang không biết tìm một đơn vị tổ chức sự kiện chuyên nghiệp – sáng tạo – tối ưu chi phí?<br>✨ Đừng lo lắng, đã có 𝓗𝓤𝓔 𝓢𝓗𝓞𝓦!!!</p>
                <p>💪 Nhằm mang đến giải pháp giúp bạn biến ý tưởng thành những trải nghiệm thực tế ấn tượng với những dịch vụ nổi bật:<br>
                ⭕ <strong>Event</strong> – Tổ chức sự kiện trọn gói (Khai trương, Lễ ra mắt, hội nghị, Gala, lửa trại,...)<br>
                ⭕ <strong>Media</strong> – Sản xuất hình ảnh & video (Quay phim, chụp ảnh, Video highlight, TVC…)<br>
                ⭕ <strong>Teambuilding</strong> – Kết nối đội ngũ (Kịch bản sáng tạo, hoạt động hấp dẫn)<br>
                ⭕ <strong>Support</strong> – Nhân sự & thiết bị (MC, PG, dancer, mascot, âm thanh, ánh sáng, sân khấu,…)<br>
                ⭕ Ngoài ra, còn đa dạng các dịch vụ khác theo yêu cầu.</p>
                <p>💡 Vậy còn ngần ngại gì mà không chọn 𝓗𝓤𝓔 𝓢𝓗𝓞𝓦?<br>Bạn chỉ cần đưa ra ý tưởng – 𝓗𝓤𝓔 𝓢𝓗𝓞𝓦 sẽ giúp bạn hoàn thiện mọi thứ một cách trọn vẹn.</p>
            </div>

            <div class="widget">
                <div class="widget-title">📌 Về chúng tôi</div>
                <p><?= htmlspecialchars($company_name) ?> – Đơn vị tổ chức team building và sự kiện hàng đầu, mang đến trải nghiệm độc đáo và chuyên nghiệp.</p>
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
                <div class="widget-title">🎥 Video giới thiệu</div>
                <video controls muted loop>
                    <source src="uploads/video/first.mp4" type="video/mp4">
                    Trình duyệt của bạn không hỗ trợ video HTML5.
                </video>
                <p style="font-size: 0.8rem; margin-top: 8px; color: #64748b;">Video giới thiệu về <?= htmlspecialchars($company_name) ?></p>
            </div>
            <div class="widget">
                <div class="widget-title">📂 Chuyên mục</div>
                <ul class="categories">
                    <li><a href="#"><i class="fas fa-tag"></i> Team Building</a></li>
                    <li><a href="#"><i class="fas fa-tag"></i> Tổ chức sự kiện</a></li>
                    <li><a href="#"><i class="fas fa-tag"></i> Hội nghị khách hàng</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="services-section">
        <h2 class="section-title">Dịch vụ nổi bật</h2>
        <div class="services-grid">
            <div class="service-card">
                <div class="service-content">
                    <h3 class="service-title">Tư vấn & Tổ chức sự kiện</h3>
                    <p class="service-description">Lên ý tưởng sáng tạo, lập kế hoạch chi tiết và triển khai các sự kiện chuyên nghiệp với quy mô đa dạng, đáp ứng mọi yêu cầu.</p>
                </div>
            </div>
            <div class="service-card">
                <div class="service-content">
                    <h3 class="service-title">Sáng tạo Media</h3>
                    <p class="service-description">Sản xuất hình ảnh, video chất lượng cao, mang đậm tính nghệ thuật, phục vụ hiệu quả cho chiến dịch truyền thông và quảng bá thương hiệu.</p>
                </div>
            </div>
            <div class="service-card">
                <div class="service-content">
                    <h3 class="service-title">Teambuilding</h3>
                    <p class="service-description">Thiết kế và tổ chức các chương trình hoạt động tập thể độc đáo, giúp gắn kết nhân sự, khơi dậy năng lượng và tinh thần đồng đội.</p>
                </div>
            </div>
            <div class="service-card">
                <div class="service-content">
                    <h3 class="service-title">Cung cấp nhân sự & Đào tạo</h3>
                    <p class="service-description">
                        <strong>📌 Đội ngũ hỗ trợ đa dạng:</strong> MC, PG, khánh tiết, lễ tân, mascot, dancer, nhân viên hậu cần, kỹ thuật viên âm thanh – ánh sáng.<br>
                        <strong>🎓 Đào tạo:</strong> Kỹ năng tổ chức sự kiện, kỹ năng làm việc nhóm, giao tiếp khách hàng.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/fe/footer.php'; ?>