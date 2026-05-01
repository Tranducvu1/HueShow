<?php
require_once '../config.php';
requireAdmin();

$userCount = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$articleCount = $conn->query("SELECT COUNT(*) as total FROM events")->fetch_assoc()['total'];
$bannerCount = $conn->query("SELECT COUNT(*) as total FROM banners")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - HueShow</title>
       <link rel="icon" type="image/jpeg" href="assets/logo.jpg">
    <link rel="shortcut icon" type="image/jpeg" href="assets/logo.jpg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Copy toàn bộ style bạn đã có (giữ nguyên) */
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
        .top-bar { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; }
        .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:20px; margin-bottom:32px; }
        .stat-card { background:white; border-radius:1.5rem; padding:20px; box-shadow:0 4px 12px rgba(0,0,0,0.05); display:flex; align-items:center; justify-content:space-between; }
        .stat-number { font-size:2rem; font-weight:800; color:#0f172a; }
        .icon-circle { width:48px; height:48px; background:#fef9e3; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#facc15; font-size:1.5rem; }
        .logout-btn { background:#ef4444; color:white; padding:8px 16px; border-radius:2rem; text-decoration:none; }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <?php include __DIR__ . '/../includes/be/sidebar.php'; ?>
    <div class="main-content">
        <div class="top-bar">
            <h2>Xin chào, <?= htmlspecialchars($_SESSION['fullname'] ?? $_SESSION['username']) ?></h2>
            <a href="admin_logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
        </div>
        <div class="stats-grid">
            <div class="stat-card"><div><div class="stat-number"><?= $userCount ?></div><div>Người dùng</div></div><div class="icon-circle"><i class="fas fa-users"></i></div></div>
            <div class="stat-card"><div><div class="stat-number"><?= $articleCount ?></div><div>Bài viết</div></div><div class="icon-circle"><i class="fas fa-newspaper"></i></div></div>
            <div class="stat-card"><div><div class="stat-number"><?= $bannerCount ?></div><div>Banner</div></div><div class="icon-circle"><i class="fas fa-images"></i></div></div>
        </div>
    </div>
</div>
</body>
</html>