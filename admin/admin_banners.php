<?php
require_once '../config.php';
requireAdmin();

if (isset($_GET['delete'])) {
    deleteBanner($_GET['delete']);
    header('Location: admin_banners.php');
    exit;
}
$banners = getAllBanners();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Banner - HueShow</title>
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
        .main-content { flex:1; padding:24px; }
        .top-bar { display:flex; justify-content:space-between; margin-bottom:20px; }
        .btn-add { background:#facc15; padding:8px 20px; border-radius:2rem; text-decoration:none; color:#0f172a; }
        table { width:100%; background:white; border-radius:1.5rem; }
        th, td { padding:12px; border-bottom:1px solid #e2e8f0; }
        img { width:80px; height:50px; object-fit:cover; border-radius:0.5rem; }
        .action-btn { padding:4px 12px; border-radius:1rem; text-decoration:none; }
        .edit { background:#eab308; color:white; }
        .delete { background:#ef4444; color:white; }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <?php include __DIR__ . '/../includes/be/sidebar.php'; ?>
    <div class="main-content">
        <div class="top-bar">
            <h2>Quản lý Banner</h2>
            <a href="feature/admin_banner_add.php" class="btn-add"><i class="fas fa-plus"></i> Thêm Banner</a>
        </div>
        <table style="width:100%;">
            <thead><tr><th>ID</th><th>Hình ảnh</th><th>Tiêu đề</th><th>Link</th><th>Thứ tự</th><th>Trạng thái</th><th>Hành động</th></tr></thead>
            <tbody><?php foreach($banners as $b): ?>
    <tr>
        <td><?= $b['id'] ?></td>
        <td><img src="../<?= htmlspecialchars($b['image_url']) ?>" width="80" height="50" style="object-fit:cover;"></td>
        <td><?= htmlspecialchars($b['title']) ?></td>
        <td><?= $b['link'] ?></td>
        <td><?= $b['order_index'] ?></td>
        <td><?= $b['status'] ?></td>
        <td>
            <a href="feature/admin_banner_edit.php?id=<?= $b['id'] ?>" class="action-btn edit">Sửa</a>
            <a href="admin_banners.php?delete=<?= $b['id'] ?>" onclick="return confirm('Xóa banner?')" class="action-btn delete">Xóa</a>
        </td>
    </tr>
<?php endforeach; ?></tbody>
        </table>
    </div>
</div>
</body>
</html>