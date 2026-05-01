<?php
require_once 'config.php';
$page_title = 'Trang chủ';

$banners = getActiveBanners(); 
$teamBuildingEvents = getTeamBuildingEvents(4);
$latestEvents = getLatestEvents(8);
$testimonials = getAllTestimonials(8);
$latestArticles = getLatestArticles(4); 


include 'includes/fe/header.php';

function fixImagePath($path) {
    if (empty($path)) return '';
    if (preg_match('#^(https?:)?//#i', $path)) {
        return $path;
    }
    if (strpos($path, 'uploads/banner') === 0) {
        return $path;
    }
    if (strpos($path, 'uploads/') === 0) {
        return '' . $path;
    }
    return $path;
}
?>

<style>
    .vision-mission-section {
        background: #1a1a1a;
        padding: 60px 32px;
        margin: 60px 0;
        border-radius: 0;
    }
    
    .vmv-title {
        text-align: center;
        font-size: 2.8rem;
        font-weight: 800;
        color: #f4b544;
        margin-bottom: 50px;
        letter-spacing: 2px;
        text-transform: uppercase;
        position: relative;
        display: inline-block;
        width: 100%;
    }
    
    .vmv-title::after {
        content: '';
        position: absolute;
        bottom: -20px;
        left: 50%;
        transform: translateX(-50%);
        width: 200px;
        height: 3px;
        background: #f4b544;
        border-radius: 0;
    }
    
    .vmv-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 50px;
        margin-bottom: 60px;
        margin-top: 50px;
    }
    
    .vmv-card {
        background: #2a2a2a;
        border-left: 6px solid #f4b544;
        padding: 40px 30px;
        border-radius: 0;
        position: relative;
        transition: all 0.3s ease;
    }
    
    .vmv-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    }
    
    .vmv-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        background: #d85a3a;
        border-radius: 50%;
        font-size: 1.8rem;
        color: white;
        margin-bottom: 20px;
        box-shadow: 0 4px 12px rgba(216,90,58,0.3);
    }
    
    .vmv-card h3 {
        font-size: 1.8rem;
        font-weight: 800;
        color: #f4b544;
        margin-bottom: 20px;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    
    .vmv-card p {
        font-size: 0.95rem;
        color: #b8b8b8;
        line-height: 1.8;
        margin-bottom: 15px;
    }
    
    .vmv-card ul {
        list-style: none;
        padding: 0;
        margin: 15px 0 0 0;
    }
    
    .vmv-card ul li {
        font-size: 0.95rem;
        color: #b8b8b8;
        line-height: 1.8;
        margin-bottom: 12px;
        padding-left: 25px;
        position: relative;
    }
    
    .vmv-card ul li::before {
        content: '●';
        position: absolute;
        left: 0;
        color: #d85a3a;
        font-size: 1rem;
    }
    
    .core-values-title {
        text-align: center;
        font-size: 2.2rem;
        font-weight: 800;
        color: #f4b544;
        margin-bottom: 50px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }
    
    .core-values-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 20px;
        padding: 0 20px;
    }
    
    .core-value-item {
        background: #2a2a2a;
        border-top: 4px solid #d85a3a;
        padding: 32px 20px;
        border-radius: 0;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .core-value-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    }
    
    .core-value-item h4 {
        font-size: 1.05rem;
        font-weight: 700;
        color: #f5f5f5;
        margin: 0;
        line-height: 1.5;
    }
    
    @media (max-width: 1024px) {
        .vmv-container {
            grid-template-columns: 1fr;
            gap: 40px;
        }
        
        .core-values-grid {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 16px;
            padding: 0;
        }
    }
    
    @media (max-width: 768px) {
        .vmv-title {
            font-size: 2rem;
            margin-bottom: 40px;
            letter-spacing: 1px;
        }
        
        .vmv-title::after {
            width: 120px;
            bottom: -15px;
        }
        
        .vmv-container {
            gap: 30px;
            margin-top: 35px;
        }
        
        .vmv-card {
            padding: 30px 20px;
        }
        
        .vmv-card h3 {
            font-size: 1.4rem;
        }
        
        .core-values-title {
            font-size: 1.6rem;
            margin-bottom: 40px;
        }
        
        .core-values-grid {
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 14px;
        }
        
        .vision-mission-section {
            padding: 40px 20px;
            margin: 40px 0;
        }
    }
    
    @media (max-width: 576px) {
        .vision-mission-section {
            padding: 30px 16px;
            margin: 30px 0;
        }
        
        .vmv-title {
            font-size: 1.5rem;
            margin-bottom: 30px;
        }
        
        .vmv-container {
            gap: 20px;
            margin-bottom: 30px;
            margin-top: 25px;
        }
        
        .core-values-title {
            font-size: 1.3rem;
            margin-bottom: 30px;
        }
        
        .core-values-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        
        .core-value-item {
            padding: 24px 16px;
        }
        
        .core-value-item h4 {
            font-size: 0.95rem;
        }
    }
</style>

<div class="slider-container">
    <div class="slider-wrapper" id="mainSlider">
        <?php if (!empty($banners)): ?>
            <?php foreach ($banners as $index => $banner): ?>
                <div class="slide <?= $index === 0 ? 'active' : '' ?>">
                    <img src="<?= htmlspecialchars(fixImagePath($banner['image_url'])) ?>" alt="<?= htmlspecialchars($banner['title']) ?>">
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="slide active">
                <img src="https://event5.mauthemewp.com/wp-content/uploads/2018/05/SLIDER2.jpg" alt="Default Slider">
            </div>
        <?php endif; ?>
    </div>
    <button class="slider-prev"><i class="fas fa-chevron-left"></i></button>
    <button class="slider-next"><i class="fas fa-chevron-right"></i></button>
    <div class="slider-dots"></div>
</div>

<main>
    <div class="container">
        <div class="section-heading">
            <h2>Các Chương Trình Đã Thực Hiện</h2>
            <p>Những chương trình đẳng cấp, kết nối và bùng nổ</p>
        </div>
        <div class="two-columns">
            <div class="main-col">
                <div class="grid-3">
                    <?php foreach ($teamBuildingEvents as $event): ?>
                        <a href="event_detail.php?id=<?= $event['id'] ?>" class="event-card">
                            <div class="card-img">
                                <?php if (!empty($event['image']) && strpos($event['image'], 'data:image') !== false): ?>
                                    <div class="img-placeholder"><i class="fas fa-flag-checkered"></i></div>
                                <?php else: ?>
                                    <img src="<?= htmlspecialchars(fixImagePath($event['image'])) ?>" alt="<?= htmlspecialchars($event['title']) ?>">
                                <?php endif; ?>
                            </div>
                            <div class="card-content">
                                <h3 class="card-title"><?= htmlspecialchars($event['title']) ?></h3>
                                <div class="card-date"><i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($event['created_at'])) ?></div>
                                <p class="card-desc"><?= cleanText($event['description'] ?? '') ?></p>
                                <span class="read-more">Xem thêm →</span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="side-col">
                <div class="video-box">
                    <h3><i class="fas fa-video"></i> Video Chương Trình</h3>
                    <div class="video-wrapper">
                        <iframe src="https://www.youtube.com/embed/mrPm9eXV1Hk" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="video-box">
                    <h3><i class="fas fa-fire"></i> Người Truyền Lửa</h3>
                    <div class="video-wrapper">
                        <iframe src="https://www.youtube.com/embed/_ltAd7y-jRw" allowfullscreen></iframe>
                    </div>
                </div>
                <div style="text-align: center; margin-top: 24px;">
                    <a href="#" class="btn-gradient">
                        <i class="fas fa-download"></i>
                        <span>Tải Brochure</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- SECTION 2: EVENTS ORGANIZED -->
        <div class="section-heading" style="margin-top: 80px;">
            <h2>Những Sự Kiện Chúng Tôi Đã Tổ Chức</h2>
            <p>Hơn 500+ sự kiện lớn nhỏ cho các tập đoàn hàng đầu</p>
        </div>
        <div class="grid-4">
            <?php foreach ($latestEvents as $event): ?>
                <a href="event_detail.php?id=<?= $event['id'] ?>" class="event-card">
                    <div class="card-img">
                        <?php if (!empty($event['image']) && strpos($event['image'], 'data:image') !== false): ?>
                            <div class="img-placeholder"><i class="fas fa-calendar-check"></i></div>
                        <?php else: ?>
                            <img src="<?= htmlspecialchars(fixImagePath($event['image'])) ?>" alt="<?= htmlspecialchars($event['title']) ?>">
                        <?php endif; ?>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title"><?= htmlspecialchars($event['title']) ?></h3>
                        <div class="card-date"><i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($event['created_at'])) ?></div>
                        <p class="card-desc"><?= cleanText($event['description'] ?? '') ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- VISION, MISSION & CORE VALUES -->
        <div class="vision-mission-section">
            <div class="vmv-title">Tầm nhìn, Sứ mệnh & Giá trị cốt lõi</div>
            <div class="vmv-container">
                <div class="vmv-card">
                    <div class="vmv-icon"><i class="fas fa-eye"></i></div>
                    <h3>Tầm nhìn</h3>
                    <p>Trở thành doanh nghiệp dẫn đầu về dịch vụ sự kiện truyền thông và cung cấp nguồn nhân lực chất lượng cao. Chúng tôi tiên phong kiến tạo những trải nghiệm độc phát, định hình tiêu chuẩn mới cho ngành công nghiệp sự kiện tại Việt Nam và vương tầm khu vực.</p>
                </div>
                <div class="vmv-card">
                    <div class="vmv-icon"><i class="fas fa-bullseye"></i></div>
                    <h3>Sứ mệnh</h3>
                    <p>Trở thành nhà cung cấp dịch vụ tin cậy và thực thi trách nhiệm song hành cùng mục tiêu kinh doanh của khách hàng thông qua:</p>
                    <ul>
                        <li>Cung cấp giải pháp sự kiện toàn diện, tối ưu chi phí.</li>
                        <li>Kết nối và phát triển nguồn nhân lực chuyên nghiệp.</li>
                        <li>Tạo ra giá trị bền vững cho cộng đồng và đối tác.</li>
                    </ul>
                </div>
            </div>
            <div class="core-values-title">5 Giá trị cốt lõi</div>
            <div class="core-values-grid">
                <div class="core-value-item"><h4>Lợi ích khách hàng là then chốt</h4></div>
                <div class="core-value-item"><h4>Chất lượng dịch vụ vượt trội</h4></div>
                <div class="core-value-item"><h4>Sáng tạo và đổi mới</h4></div>
                <div class="core-value-item"><h4>Tận tâm và trách nhiệm</h4></div>
                <div class="core-value-item"><h4>Đồng hành cùng phát triển</h4></div>
            </div>
        </div>

        <!-- TESTIMONIALS -->
        <div class="section-heading" style="margin-top: 80px;">
            <h2>Đánh Giá Từ Khánh Hàng</h2>
            <p>Niềm tin từ các tập đoàn hàng đầu và đối tác chiến lược</p>
        </div>
        <div class="testimonials-grid">
            <?php foreach ($testimonials as $t): ?>
                <div class="testimonial-card">
                    <div class="testimonial-avatar">
                        <?php
                        $avatar = fixImagePath($t['avatar'] ?? '');
                        if (!$avatar) $avatar = 'https://via.placeholder.com/80x80?text=Avatar';
                        ?>
                        <img src="<?= htmlspecialchars($avatar) ?>" alt="<?= htmlspecialchars($t['name']) ?>">
                    </div>
                    <div class="testimonial-rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= $t['rating']): ?>
                                <i class="fas fa-star"></i>
                            <?php else: ?>
                                <i class="far fa-star"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <p class="testimonial-text">"<?= cleanText($t['content']) ?>"</p>
                    <div class="testimonial-author">
                        <strong><?= htmlspecialchars($t['name']) ?></strong>
                        <span><?= htmlspecialchars($t['position']) ?>, <?= htmlspecialchars($t['company']) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- LATEST ARTICLES -->
        <div class="section-heading" style="margin-top: 80px;">
            <h2>Tin Tức & Bài Viết</h2>
            <p>Cập nhật những xu hướng mới nhất trong tổ chức sự kiện</p>
        </div>
        <div class="grid-3 news-grid">
            <?php foreach ($latestArticles as $article): ?>
                <a href="article_detail.php?id=<?= $article['id'] ?>" class="event-card news-card">
                    <div class="card-img">
                        <?php if (!empty($article['image']) && strpos($article['image'], 'data:image') !== false): ?>
                            <div class="img-placeholder"><i class="fas fa-newspaper"></i></div>
                        <?php else: ?>
                            <img src="<?= htmlspecialchars(fixImagePath($article['image'])) ?>" alt="<?= htmlspecialchars($article['title']) ?>">
                        <?php endif; ?>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title"><?= htmlspecialchars($article['title']) ?></h3>
                        <div class="card-date"><i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($article['created_at'])) ?></div>
                        <p class="card-desc"><?= cleanText($article['description'] ?? '') ?></p>
                        <span class="btn-outline-light">Đọc tiếp</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php include 'includes/fe/footer.php'; ?>