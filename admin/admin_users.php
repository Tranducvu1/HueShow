<?php
require_once '../config.php';
requireAdmin();

$search = $_GET['search'] ?? '';
$users = getAllUsers($search);
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    deleteUser($_GET['delete']);
    header('Location: admin_users.php?msg=deleted');
    exit;
}
$message = $_GET['msg'] ?? '';
if ($message === 'deleted') $message = '🗑️ Xóa người dùng thành công';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý người dùng - HueShow Admin</title>
    <link rel="icon" type="image/jpeg" href="../assets/logo.jpg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:#f1f5f9; }
        .admin-wrapper { display:flex; min-height:100vh; }
        /* Sidebar giữ nguyên style từ dashboard */
        .sidebar { width:280px; background:linear-gradient(135deg,#0f172a,#1e293b); color:#cbd5e1; flex-shrink:0; }
        .sidebar-header { padding:24px 20px; border-bottom:1px solid #334155; }
        .sidebar-header h3 { color:#facc15; font-weight:700; }
        .sidebar-menu { list-style:none; padding:20px 0; }
        .sidebar-menu li a { display:flex; align-items:center; gap:12px; padding:12px 24px; color:#e2e8f0; text-decoration:none; transition:0.2s; }
        .sidebar-menu li a i { width:24px; }
        .sidebar-menu li a:hover, .sidebar-menu li.active a { background:#334155; color:#facc15; }
        
        /* Main content */
        .main-content { flex:1; padding:28px 32px; }
        .top-bar { display:flex; justify-content:space-between; align-items:center; margin-bottom:28px; flex-wrap:wrap; gap:16px; }
        .top-bar h2 { font-size:1.8rem; font-weight:800; color:#0f172a; letter-spacing:-0.02em; }
        .btn-add { background:linear-gradient(105deg,#facc15,#f59e0b); padding:10px 24px; border-radius:40px; text-decoration:none; color:#0f172a; font-weight:700; display:inline-flex; align-items:center; gap:10px; transition:0.2s; box-shadow:0 4px 8px rgba(0,0,0,0.05); }
        .btn-add:hover { transform:translateY(-2px); box-shadow:0 10px 20px -5px rgba(250,204,21,0.4); }
        
        /* Search bar */
        .search-wrapper { margin-bottom:28px; }
        .search-form { display:flex; max-width:380px; background:white; border-radius:60px; box-shadow:0 2px 6px rgba(0,0,0,0.05); border:1px solid #e2e8f0; overflow:hidden; transition:0.2s; }
        .search-form:focus-within { border-color:#facc15; box-shadow:0 0 0 3px rgba(250,204,21,0.2); }
        .search-form input { flex:1; padding:12px 20px; border:none; outline:none; font-size:0.95rem; }
        .search-form button { background:white; border:none; padding:0 20px; color:#64748b; cursor:pointer; font-size:1.1rem; transition:0.2s; }
        .search-form button:hover { color:#facc15; }
        
        /* Message toast */
        .message { background:#dcfce7; border-left:5px solid #22c55e; padding:14px 20px; border-radius:16px; margin-bottom:24px; font-weight:500; color:#166534; display:flex; align-items:center; gap:10px; }
        
        /* Bảng dữ liệu */
        .table-container { background:white; border-radius:28px; box-shadow:0 8px 20px rgba(0,0,0,0.05); overflow-x:auto; }
        table { width:100%; border-collapse:collapse; min-width:680px; }
        th { text-align:left; padding:18px 20px; background:#f8fafc; font-weight:700; color:#1e293b; border-bottom:2px solid #e2e8f0; }
        td { padding:16px 20px; border-bottom:1px solid #f1f5f9; color:#334155; vertical-align:middle; }
        tr:hover td { background:#fefce8; }
        
        /* Role badge */
        .role-badge { display:inline-block; padding:4px 12px; border-radius:30px; font-size:0.75rem; font-weight:700; text-transform:uppercase; }
        .role-admin { background:#fef3c7; color:#b45309; }
        .role-user { background:#e0f2fe; color:#0369a1; }
        
        /* Action buttons */
        .action-group { display:flex; gap:10px; }
        .action-btn { display:inline-flex; align-items:center; gap:6px; padding:6px 14px; border-radius:40px; text-decoration:none; font-weight:600; font-size:0.8rem; transition:0.2s; }
        .action-btn.edit { background:#fef9e3; color:#ca8a04; border:1px solid #fde047; }
        .action-btn.edit:hover { background:#facc15; color:#0f172a; border-color:#facc15; }
        .action-btn.delete { background:#fef2f2; color:#dc2626; border:1px solid #fecaca; }
        .action-btn.delete:hover { background:#ef4444; color:white; border-color:#ef4444; }
        
        /* Empty state */
        .empty-row td { text-align:center; padding:48px; color:#94a3b8; }
        
        /* Responsive */
        @media (max-width:768px) {
            .main-content { padding:20px; }
            .top-bar { flex-direction:column; align-items:flex-start; }
            .search-form { max-width:100%; }
            th, td { padding:12px 16px; }
        }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <?php include __DIR__ . '/../includes/be/sidebar.php'; ?>
    <div class="main-content">
     
        <div class="search-wrapper">
            <form method="GET" class="search-form">
                <input type="text" name="search" placeholder="🔍 Tìm kiếm theo tên, email, username..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
        
        <?php if($message): ?>
            <div class="message">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Tên đăng nhập</th><th>Email</th><th>Họ tên</th><th>Ngày tạo</th><th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($users) > 0): ?>
                        <?php foreach($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><strong><?= htmlspecialchars($user['username']) ?></strong></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['fullname']) ?></td>
                    
                                <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                <td class="action-group">
                                    <a href="admin_user_edit.php?id=<?= $user['id'] ?>" class="action-btn edit"><i class="fas fa-pencil-alt"></i> Sửa</a>
                                    <a href="admin_users.php?delete=<?= $user['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')" class="action-btn delete"><i class="fas fa-trash-alt"></i> Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr class="empty-row"><td colspan="7">📭 Không tìm thấy người dùng nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>