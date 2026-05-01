<?php
// articles.php - Chi tiết bài viết (tin tức)
require_once 'config.php';

// Định nghĩa hàm fixImagePath nếu chưa có (trong config.php có thể thiếu)
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
    header('Location: index.php');
    exit;
}

// Lấy bài viết từ bảng articles
$article = getArticleById($id);
if (!$article) {
    // Chuyển hướng về trang danh sách bài viết hoặc trang chủ
    header('Location: index.php?error=notfound');
    exit;
}

$page_title = htmlspecialchars($article['title']);

// Lấy bài viết liên quan (không bao gồm bài hiện tại)
$allArticles = getLatestArticles(5);
$relatedArticles = array_filter($allArticles, function($a) use ($id) {
    return $a['id'] != $id;
});
$relatedArticles = array_slice($relatedArticles, 0, 3);

include 'includes/fe/header.php';
?>

<style>
    /* ========== ARTICLE DETAIL STYLES ========== */
    .blog-single {
        padding: 40px 0 60px;
    }
    .row-large {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 40px;
        margin-bottom: 60px;
    }
    .post-content {
        background: white;
        border-radius: 28px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .entry-category {
        display: inline-block;
        background: #facc15;
        color: #0f172a;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        margin-bottom: 16px;
    }
    .entry-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 16px;
        line-height: 1.3;
    }
    .entry-meta {
        display: flex;
        gap: 20px;
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e2e8f0;
    }
    .entry-meta i {
        margin-right: 6px;
        color: #facc15;
    }
    .entry-image {
        margin-bottom: 30px;
        border-radius: 20px;
        overflow: hidden;
    }
    .entry-image img {
        width: 100%;
        max-height: 500px;
        object-fit: cover;
    }
    .single-content {
        font-size: 1.05rem;
        line-height: 1.8;
        color: #334155;
    }
    .single-content p {
        margin-bottom: 1.2rem;
    }
    .social-icons {
        display: flex;
        gap: 16px;
    }
    .social-icons a {
        background: #1e293b;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #facc15;
        transition: 0.3s;
    }
    .social-icons a:hover {
        background: #facc15;
        color: #0f172a;
        transform: translateY(-3px);
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
        display: block;
    }
    .recent-posts, .categories {
        list-style: none;
    }
    .recent-posts li, .categories li {
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e2e8f0;
    }
    .recent-posts a, .categories a {
        text-decoration: none;
        color: #1e293b;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: 0.3s;
    }
    .recent-posts a:hover, .categories a:hover {
        color: #facc15;
    }
    .video-widget iframe {
        width: 100%;
        border-radius: 16px;
    }
    .related-title {
        font-size: 1.8rem;
        font-weight: 700;
        text-align: center;
        margin: 40px 0 30px;
        position: relative;
        padding-bottom: 15px;
    }
    .related-title::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, #facc15, #eab308);
        border-radius: 2px;
    }
    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
        margin-bottom: 60px;
    }
    .related-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        transition: 0.3s;
        text-decoration: none;
        color: inherit;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
    }
    .related-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    .related-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    .related-card h4 {
        font-size: 1.1rem;
        font-weight: 600;
        padding: 16px 16px 8px;
        margin: 0;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .related-card p {
        padding: 0 16px 16px;
        font-size: 0.8rem;
        color: #6b7280;
        margin: 0;
    }
    .comments-area {
        background: white;
        border-radius: 28px;
        padding: 40px;
        margin-top: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .comments-area h3 {
        font-size: 1.5rem;
        margin-bottom: 24px;
        border-left: 5px solid #facc15;
        padding-left: 16px;
    }
    .comment-form {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .comment-form textarea {
        grid-column: span 2;
    }
    .comment-form input, .comment-form textarea {
        padding: 14px 18px;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        font-family: inherit;
        transition: 0.2s;
    }
    .comment-form input:focus, .comment-form textarea:focus {
        outline: none;
        border-color: #facc15;
        box-shadow: 0 0 0 3px rgba(250,204,21,0.2);
    }
    .comment-form button {
        background: linear-gradient(95deg, #facc15, #eab308);
        color: #0f172a;
        font-weight: bold;
        padding: 12px 28px;
        border: none;
        border-radius: 40px;
        cursor: pointer;
        transition: 0.2s;
        width: fit-content;
    }
    .comment-form button:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 12px rgba(250,204,21,0.3);
    }
    @media (max-width: 992px) {
        .row-large { grid-template-columns: 1fr; }
        .entry-title { font-size: 1.8rem; }
    }
    @media (max-width: 768px) {
        .post-content { padding: 24px; }
        .comment-form { grid-template-columns: 1fr; }
        .comment-form textarea { grid-column: span 1; }
        .related-grid { gap: 20px; }
    }
    @media (max-width: 576px) {
        .related-grid { grid-template-columns: 1fr; }
        .entry-meta { flex-direction: column; gap: 8px; }
    }
</style>

<div class="blog-single">
    <div class="container">
        <div class="row-large">
            <!-- MAIN CONTENT -->
            <div class="post-content">
                <div class="entry-category">
                    <?= htmlspecialchars($article['category_name'] ?? 'Tin tức') ?>
                </div>
                <h1 class="entry-title"><?= htmlspecialchars($article['title']) ?></h1>
                <div class="entry-meta">
                    <span><i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($article['created_at'])) ?></span>
                    <span><i class="far fa-user"></i> Admin</span>
                </div>
                <?php if (!empty($article['image'])): ?>
                <div class="entry-image">
                    <img src="<?= htmlspecialchars(fixImagePath($article['image'])) ?>" alt="<?= htmlspecialchars($article['title']) ?>">
                </div>
                <?php endif; ?>
                <div class="single-content">
                      <?= cleanText($article['description']) ?>
                </div>
                <div class="blog-share" style="margin-top: 40px; text-align: center;">
                    <div class="social-icons" style="justify-content: center;">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR -->
            <div class="sidebar">
                <div class="widget">
                    <span class="widget-title">📌 Về chúng tôi</span>
                    <p>HueShow chuyên tổ chức team building, sự kiện doanh nghiệp chuyên nghiệp sáng tạo.</p>
                </div>
                <div class="widget">
                    <span class="widget-title">📰 Bài viết mới</span>
                    <ul class="recent-posts">
                        <?php 
                        $latestArticles = getLatestArticles(5);
                        foreach ($latestArticles as $post): 
                        ?>
                            <li><a href="articles.php?id=<?= $post['id'] ?>"><i class="fas fa-angle-right" style="color:#facc15;"></i> <?= htmlspecialchars($post['title']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="widget video-widget">
                    <span class="widget-title">🎥 Video giới thiệu</span>
                    <iframe src="https://www.youtube.com/embed/_ltAd7y-jRw" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="widget">
                    <span class="widget-title">📂 Chuyên mục</span>
                    <ul class="categories">
                        <li><a href="#"><i class="fas fa-tag"></i> Team Building</a></li>
                        <li><a href="#"><i class="fas fa-tag"></i> Tổ chức sự kiện</a></li>
                        <li><a href="#"><i class="fas fa-tag"></i> Hội nghị khách hàng</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- RELATED ARTICLES -->
        <?php if (!empty($relatedArticles)): ?>
        <div class="related-title">📖 Bài viết liên quan</div>
        <div class="related-grid">
            <?php foreach ($relatedArticles as $rel): ?>
                <a href="articles.php?id=<?= $rel['id'] ?>" class="related-card">
                    <?php if (!empty($rel['image'])): ?>
                        <img src="<?= htmlspecialchars(fixImagePath($rel['image'])) ?>" alt="<?= htmlspecialchars($rel['title']) ?>">
                    <?php else: ?>
                        <div style="height:200px; background:#e2e8f0; display:flex; align-items:center; justify-content:center;">📸</div>
                    <?php endif; ?>
                    <h4><?= htmlspecialchars($rel['title']) ?></h4>
                    <p><?= date('d/m/Y', strtotime($rel['created_at'])) ?></p>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- COMMENTS SECTION -->
        <div class="comments-area">
            <h3>💬 Bình luận</h3>
            <form action="#" method="post" class="comment-form">
                <input type="text" placeholder="Họ tên" required>
                <input type="email" placeholder="Email" required>
                <textarea rows="4" placeholder="Nội dung bình luận..."></textarea>
                <button type="submit" class="submit">Gửi bình luận</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/fe/footer.php'; ?>