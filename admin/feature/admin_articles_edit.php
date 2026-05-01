<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../../config.php';
requireAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: ../admin_articles.php');
    exit;
}

$article = getArticleById($id);
if (!$article) {
    header('Location: ../admin_articles.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'draft';
    $featured = isset($_POST['featured']) ? 1 : 0;
    
    $image = $article['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../uploads/articles/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $ext;
        $dest = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
            $oldPath = '../../' . $article['image'];
            if (!empty($article['image']) && file_exists($oldPath) && is_file($oldPath)) {
                unlink($oldPath);
            }
            $image = 'uploads/articles/' . $filename;
        } else {
            $error = 'Upload ảnh thất bại.';
        }
    } elseif (isset($_POST['image_url']) && !empty($_POST['image_url'])) {
        $image = trim($_POST['image_url']);
    }
    
    if (empty($title) || empty($slug) || empty($description)) {
        $error = 'Vui lòng điền đầy đủ các trường bắt buộc.';
    } elseif (empty($error)) {
        $updateResult = updateArticle($id, $title, $slug, $description, $image, $status, $featured);
        if ($updateResult === true) {
            $success = 'Cập nhật bài viết thành công!';
            $article = getArticleById($id);
        } else {
            $error = 'Có lỗi xảy ra: ' . $updateResult;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa bài viết - Hueshow Admin</title>
         <link rel="icon" type="image/jpeg" href="../assets/logo.jpg">
    <link rel="shortcut icon" type="image/jpeg" href="../assets/logo.jpg">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:#f8fafc; }
        .admin-wrapper { display:flex; min-height:100vh; }
        .sidebar { width:280px; background:linear-gradient(135deg,#0f172a,#1e293b); color:#cbd5e1; }
        .sidebar-header { padding:24px 20px; border-bottom:1px solid #334155; }
        .sidebar-header h3 { color:#facc15; }
        .sidebar-menu { list-style:none; padding:20px 0; }
        .sidebar-menu li a { display:flex; align-items:center; gap:12px; padding:12px 20px; color:#e2e8f0; text-decoration:none; }
        .sidebar-menu li a:hover, .sidebar-menu li.active a { background:#334155; color:#facc15; }
        .main-content { flex:1; padding:24px; display:flex; justify-content:center; align-items:flex-start; }
        .form-container { background:white; border-radius:1.5rem; padding:24px; box-shadow:0 4px 12px rgba(0,0,0,0.05); max-width:800px; width:100%; }
        .form-group { margin-bottom:1rem; }
        label { display:block; font-weight:600; margin-bottom:6px; }
        input, textarea, select { width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:1rem; font-family:inherit; }
        input:focus, textarea:focus, select:focus { outline:none; border-color:#facc15; }
        button { background:linear-gradient(95deg,#facc15,#eab308); border:none; padding:10px 24px; border-radius:2rem; font-weight:600; cursor:pointer; }
        .error { background:#fee2e2; color:#991b1b; padding:10px; border-radius:1rem; margin-bottom:1rem; }
        .success { background:#dcfce7; color:#166534; padding:10px; border-radius:1rem; margin-bottom:1rem; }
        .current-image { margin:10px 0; }
        .current-image img { max-width:200px; border-radius:0.5rem; }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <?php include '../../includes/be/sidebar.php'; ?>
    <div class="main-content">
        <div class="form-container">
            <h2 style="margin-bottom:20px;">✏️ Sửa bài viết</h2>
            <?php if($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if($success): ?>
                <div class="success">
                    <?= htmlspecialchars($success) ?> 
                    <a href="../admin_articles.php" style="color:#166534; text-decoration:underline; margin-left:15px;">← Quay lại danh sách bài viết</a>
                </div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Tiêu đề *</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($article['title']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Slug * (đường dẫn thân thiện)</label>
                    <input type="text" name="slug" value="<?= htmlspecialchars($article['slug']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Mô tả *</label>
                    <textarea name="description" rows="6" required><?= htmlspecialchars($article['description']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Ảnh hiện tại</label>
                    <div class="current-image">
                        <?php if(!empty($article['image'])): ?>
                            <img src="../../<?= htmlspecialchars($article['image']) ?>" alt=""><br>
                            <small><?= htmlspecialchars($article['image']) ?></small>
                        <?php else: ?><em>Chưa có ảnh</em><?php endif; ?>
                    </div>
                    <label>Chọn ảnh mới (hoặc nhập URL)</label>
                    <input type="file" name="image" accept="image/*">
                    <input type="text" name="image_url" placeholder="Hoặc nhập URL ảnh" style="margin-top:8px;">
                </div>
                <div class="form-group">
                    <label>Trạng thái</label>
                    <select name="status">
                        <option value="draft" <?= $article['status'] === 'draft' ? 'selected' : '' ?>>Nháp</option>
                        <option value="published" <?= $article['status'] === 'published' ? 'selected' : '' ?>>Xuất bản</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><input type="checkbox" name="featured" value="1" <?= $article['featured'] ? 'checked' : '' ?>> Bài viết nổi bật</label>
                </div>
                <div class="form-group">
                    <button type="submit"><i class="fas fa-save"></i> Lưu thay đổi</button>
                    <a href="../admin_articles.php" style="margin-left:10px;">Hủy bỏ</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>