<?php
require_once dirname(__DIR__) . '/config.php';
requireAdmin();

$search = $_GET['search'] ?? '';
$articles = getAllArticles($search);

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if (deleteArticle($id)) {
        header('Location: admin_articles.php?deleted=1');
        exit;
    } else {
        $error = "Xóa thất bại, vui lòng thử lại.";
    }
}
if (isset($_GET['deleted'])) {
    $success = "Xóa bài viết thành công!";
}

function adminImagePath($path) {
    if (empty($path)) return '';
    if (preg_match('#^(https?:)?//#i', $path)) return $path;
    if (strpos($path, 'uploads/') === 0) return '../' . $path;
    return $path;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý bài viết - Hueshow</title>
      <link rel="icon" type="image/jpeg" href="../assets/logo.jpg">
    <link rel="shortcut icon" type="image/jpeg" href="../assets/logo.jpg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:#f1f5f9; }
        .admin-wrapper { display:flex; min-height:100vh; }
        .sidebar { width:280px; background:linear-gradient(135deg,#0f172a,#1e293b); color:#cbd5e1; }
        .sidebar-header { padding:24px 20px; border-bottom:1px solid #334155; }
        .sidebar-header h3 { color:#facc15; }
        .sidebar-menu { list-style:none; padding:20px 0; }
        .sidebar-menu li a { display:flex; align-items:center; gap:12px; padding:12px 20px; color:#e2e8f0; text-decoration:none; transition:.2s; }
        .sidebar-menu li a:hover, .sidebar-menu li.active a { background:#334155; color:#facc15; }
        .main-content { flex:1; padding:28px 32px; min-width:0; }
        .top-bar { display:flex; justify-content:space-between; align-items:center; margin-bottom:28px; flex-wrap:wrap; gap:12px; }
        .top-bar h2 { font-size:1.8rem; font-weight:600; color:#0f172a; }
        .btn-add { background:#facc15; color:#0f172a; padding:10px 24px; border-radius:40px; text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:8px; transition:.2s; box-shadow:0 2px 4px rgba(0,0,0,0.05); }
        .btn-add:hover { background:#eab308; transform:translateY(-2px); box-shadow:0 6px 12px rgba(0,0,0,0.1); }
        .search-form { display:flex; gap:12px; margin-bottom:28px; }
        .search-form input { flex:1; padding:12px 20px; border-radius:40px; border:1px solid #cbd5e1; font-family:inherit; font-size:.9rem; background:white; }
        .search-form input:focus { outline:none; border-color:#facc15; box-shadow:0 0 0 3px rgba(250,204,21,0.2); }
        .search-form button { background:#334155; color:white; border:none; padding:0 24px; border-radius:40px; cursor:pointer; font-weight:500; }
        .search-form button:hover { background:#1e293b; }
        .table-wrapper { background:white; border-radius:24px; box-shadow:0 8px 20px rgba(0,0,0,0.05); overflow-x:auto; }
        table { width:100%; border-collapse:collapse; min-width:700px; }
        th { text-align:left; padding:16px 20px; background:#f8fafc; font-weight:600; color:#1e293b; border-bottom:1px solid #e2e8f0; }
        td { padding:16px 20px; border-bottom:1px solid #eef2f6; vertical-align:middle; }
        tr:hover td { background:#fefce8; }
        .img-thumb { width:60px; height:60px; object-fit:cover; border-radius:12px; background:#f1f5f9; box-shadow:0 2px 6px rgba(0,0,0,0.05); }
        .no-image { color:#94a3b8; font-size:13px; display:inline-flex; align-items:center; gap:6px; }
        /* === SỬA: trạng thái và nổi bật nằm gọn 1 dòng === */
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 40px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .featured-star {
            color: #facc15;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }
        .status-published { background:#dcfce7; color:#166534; }
        .status-draft { background:#fef3c7; color:#92400e; }
        /* Đảm bảo các ô có đủ không gian và không xuống dòng */
        td:nth-child(5), td:nth-child(6) {
            white-space: nowrap;
        }
        .action-group { display:flex; gap:10px; flex-wrap:wrap; }
        .action-btn { display:inline-flex; align-items:center; gap:6px; padding:6px 14px; border-radius:40px; text-decoration:none; font-weight:500; font-size:.8rem; transition:.2s; border:none; cursor:pointer; }
        .edit { background:#eab308; color:#0f172a; }
        .edit:hover { background:#ca8a04; transform:translateY(-2px); }
        .delete { background:#ef4444; color:white; }
        .delete:hover { background:#dc2626; transform:translateY(-2px); }
        @media (max-width:1024px) { .main-content { padding:20px; } th,td { padding:12px 16px; } }
        @media (max-width:768px) {
            .sidebar { width:80px; }
            .sidebar-header h3, .sidebar-menu li a span { display:none; }
            .sidebar-menu li a { justify-content:center; padding:12px 0; }
            .top-bar h2 { font-size:1.4rem; }
            th:nth-child(4), td:nth-child(4) { display:none; }
            .img-thumb { width:45px; height:45px; }
            /* Trên mobile vẫn giữ nowrap cho 2 cột trạng thái & nổi bật */
            td:nth-child(5), td:nth-child(6) {
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <?php include __DIR__ . '/../includes/be/sidebar.php'; ?>
    <div class="main-content">
        <?php if (isset($success)): ?>
            <div style="background:#dcfce7; color:#166534; padding:12px 20px; border-radius:40px; margin-bottom:20px;">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
            </div>
        <?php elseif (isset($error)): ?>
            <div style="background:#fee2e2; color:#991b1b; padding:12px 20px; border-radius:40px; margin-bottom:20px;">
                <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="top-bar">
            <h2>📄 Quản lý bài viết</h2>
            <a href="feature/admin_articles_add.php" class="btn-add">
                <i class="fas fa-plus"></i> Thêm bài viết
            </a>
        </div>

        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="🔍 Tìm kiếm bài viết theo tiêu đề..." 
                   value="<?= htmlspecialchars($search) ?>">
            <button type="submit"><i class="fas fa-search"></i> Tìm kiếm</button>
        </form>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width:60px;">ID</th>
                        <th style="width:80px;">Ảnh</th>
                        <th>Tiêu đề</th>
                        <th>Slug</th>
                        <th>Trạng thái</th>
                        <th>Nổi bật</th>
                        <th>Ngày tạo</th>
                        <th style="width:160px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($articles)): ?>
                        <tr>
                            <td colspan="8" style="text-align:center; padding:40px;">📭 Không có bài viết nào.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($articles as $art): ?>
                            <tr>
                                <td><?= (int)$art['id'] ?></td>
                                <td>
                                    <?php if (!empty($art['image'])): ?>
                                        <img src="<?= htmlspecialchars(adminImagePath($art['image'])) ?>" class="img-thumb" alt="Article image">
                                    <?php else: ?>
                                        <span class="no-image"><i class="far fa-image"></i> Chưa có</span>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?= htmlspecialchars($art['title']) ?></strong></td>
                                <td><?= htmlspecialchars($art['slug']) ?></td>
                                <td>
                                    <span class="status-badge <?= $art['status'] == 'published' ? 'status-published' : 'status-draft' ?>">
                                        <?= $art['status'] == 'published' ? 'Công khai' : 'Nháp' ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($art['featured']): ?>
                                        <span class="featured-star"><i class="fas fa-star"></i> Có</span>
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d/m/Y', strtotime($art['created_at'])) ?></td>
                                <td>
                                    <div class="action-group">
                                        <a href="feature/admin_articles_edit.php?id=<?= $art['id'] ?>" class="action-btn edit">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>
                                        <a href="admin_articles.php?delete=<?= $art['id'] ?>" 
                                           onclick="return confirm('❓ Bạn có chắc chắn muốn xóa bài viết này?')" 
                                           class="action-btn delete">
                                            <i class="fas fa-trash-alt"></i> Xóa
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>