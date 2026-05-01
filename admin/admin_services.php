<?php
require_once '../config.php';
requireAdmin();

$search = $_GET['search'] ?? '';
$services = getAllServicesAdmin($search);
if (isset($_GET['delete'])) {
    deleteService($_GET['delete']);
    header('Location: admin_services.php');
    exit;
}

function adminServiceImage($path) {
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
    <title>Quản lý dịch vụ - HueShow Admin</title>
    <link rel="icon" type="image/jpeg" href="../assets/logo.jpg">
    <link rel="shortcut icon" type="image/jpeg" href="../assets/logo.jpg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Style giống admin_events */
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:#f1f5f9; }
        .admin-wrapper { display:flex; min-height:100vh; }
        .sidebar { width:280px; background:linear-gradient(135deg,#0f172a,#1e293b); color:#cbd5e1; }
        .sidebar-header { padding:24px 20px; border-bottom:1px solid #334155; }
        .sidebar-header h3 { color:#facc15; }
        .sidebar-menu { list-style:none; padding:20px 0; }
        .sidebar-menu li a { display:flex; align-items:center; gap:12px; padding:12px 20px; color:#e2e8f0; text-decoration:none; transition:0.2s; }
        .sidebar-menu li a:hover, .sidebar-menu li.active a { background:#334155; color:#facc15; }
        .main-content { flex:1; padding:28px 32px; }
        .top-bar { display:flex; justify-content:space-between; align-items:center; margin-bottom:28px; flex-wrap:wrap; gap:12px; }
        .top-bar h2 { font-size:1.8rem; font-weight:600; color:#0f172a; }
        .top-bar p { color:#475569; margin-top:4px; font-size:0.85rem; }
        .btn-add { background:#facc15; padding:10px 24px; border-radius:40px; text-decoration:none; color:#0f172a; font-weight:600; transition:0.2s; display:inline-flex; align-items:center; gap:8px; }
        .btn-add:hover { background:#eab308; transform:translateY(-2px); }
        .card { background:white; border-radius:24px; padding:24px; box-shadow:0 8px 20px rgba(0,0,0,0.05); margin-bottom:24px; }
        .search-form { display:flex; gap:12px; }
        .search-form input { flex:1; padding:12px 20px; border-radius:40px; border:1px solid #cbd5e1; }
        .search-form button { background:#334155; color:white; border:none; padding:0 24px; border-radius:40px; cursor:pointer; }
        .table-wrapper { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; min-width:700px; }
        th, td { padding:16px 20px; text-align:left; border-bottom:1px solid #eef2f6; vertical-align:middle; }
        th { background:#f8fafc; font-weight:600; }
        .img-thumb { width:50px; height:50px; object-fit:cover; border-radius:12px; }
        .status-badge { display:inline-block; padding:5px 12px; border-radius:40px; font-size:0.75rem; font-weight:600; }
        .status-published { background:#dcfce7; color:#166534; }
        .status-draft { background:#fef3c7; color:#92400e; }
        .featured-yes { color:#facc15; }
        .action-btn { padding:6px 14px; border-radius:40px; text-decoration:none; font-weight:500; font-size:0.8rem; display:inline-flex; align-items:center; gap:6px; }
        .edit { background:#eab308; color:#0f172a; }
        .delete { background:#ef4444; color:white; }
        .empty { text-align:center; padding:40px; color:#94a3b8; }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <?php include __DIR__ . '/../includes/be/sidebar.php'; ?>
    <div class="main-content">
        <div class="top-bar">
            <div>
                <h2>📦 Quản lý dịch vụ</h2>
                <p><?= count($services) ?> dịch vụ</p>
            </div>
            <a href="feature/admin_services_add.php" class="btn-add"><i class="fas fa-plus"></i> Thêm dịch vụ</a>
        </div>

        <div class="card">
            <form method="GET" class="search-form">
                <input type="text" name="search" placeholder="🔍 Tìm kiếm dịch vụ..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit"><i class="fas fa-search"></i> Tìm</button>
            </form>
        </div>

        <div class="card">
            <div class="table-wrapper">
                <?php if (empty($services)): ?>
                    <div class="empty">
                        <i class="fas fa-box-open" style="font-size:48px; margin-bottom:16px; display:block;"></i>
                        <p>Chưa có dịch vụ nào. <a href="feature/admin_services_add.php">Thêm ngay</a></p>
                    </div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr><th>ID</th><th>Ảnh</th><th>Tiêu đề</th><th>Ngày thực hiện</th><th>Trạng thái</th><th>Nổi bật</th><th>Hành động</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach($services as $sv): ?>
                            <tr>
                                <td><?= $sv['id'] ?></td>
                                <td><?php if(!empty($sv['image'])): ?><img src="<?= htmlspecialchars(adminServiceImage($sv['image'])) ?>" class="img-thumb"><?php else: ?><i class="far fa-image"></i><?php endif; ?></td>
                                <td><strong><?= htmlspecialchars($sv['title']) ?></strong></td>
                                <td><?= !empty($sv['event_date']) ? date('d/m/Y', strtotime($sv['event_date'])) : '—' ?></td>
                                <td><span class="status-badge status-<?= $sv['status'] ?>"><?= $sv['status'] === 'published' ? 'Công khai' : 'Nháp' ?></span></td>
                                <td><?php if($sv['featured']): ?><span class="featured-yes"><i class="fas fa-star"></i> Có</span><?php else: ?>—<?php endif; ?></td>
                                <td>
                                    <div class="action-group" style="display:flex; gap:8px;">
                                        <a href="feature/admin_services_edit.php?id=<?= $sv['id'] ?>" class="action-btn edit"><i class="fas fa-edit"></i> Sửa</a>
                                        <a href="admin_services.php?delete=<?= $sv['id'] ?>" onclick="return confirm('Xóa dịch vụ này?')" class="action-btn delete"><i class="fas fa-trash-alt"></i> Xóa</a>
                                    </div>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>