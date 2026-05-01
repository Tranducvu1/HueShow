<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once dirname(__DIR__, 2) . '/config.php';
requireAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: ../admin_services.php');
    exit;
}
$service = getServiceById($id);
if (!$service) {
    header('Location: ../admin_services.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $event_date = !empty($_POST['event_date']) ? $_POST['event_date'] : null;
    $status = $_POST['status'] ?? 'draft';
    $featured = isset($_POST['featured']) ? 1 : 0;
    $image = $service['image'];

    // Xử lý upload ảnh mới
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../uploads/services/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($ext, $allowed)) {
            $error = 'Chỉ chấp nhận file ảnh (jpg, jpeg, png, gif, webp).';
        } else {
            $filename = time() . '_' . uniqid() . '.' . $ext;
            $dest = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                // Xóa ảnh cũ nếu có và là file local (không phải URL)
                if (!empty($service['image']) && strpos($service['image'], 'uploads/') === 0) {
                    $oldPath = '../../' . $service['image'];
                    if (file_exists($oldPath) && is_file($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $image = 'uploads/services/' . $filename;
            } else {
                $error = 'Upload ảnh thất bại. Kiểm tra quyền thư mục uploads/services/';
            }
        }
    } elseif (isset($_POST['image_url']) && !empty($_POST['image_url'])) {
        $image = trim($_POST['image_url']);
    }

    if (empty($title) || empty($description)) {
        $error = 'Vui lòng điền đầy đủ các trường bắt buộc.';
    } elseif (empty($error)) {
        if (updateService($id, $title, $description, $image, $event_date, $status, $featured)) {
            $success = 'Cập nhật dịch vụ thành công!';
            $service = getServiceById($id);
        } else {
            $error = 'Có lỗi xảy ra khi cập nhật database: ' . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa dịch vụ - Hueshow Admin</title>
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
    <?php include dirname(__DIR__, 2) . '/includes/be/sidebar.php'; ?>
    <div class="main-content">
        <div class="form-container">
            <h2 style="margin-bottom:20px;">✏️ Sửa dịch vụ</h2>
            <?php if($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <?php if($success): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Tiêu đề *</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($service['title']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Mô tả chi tiết *</label>
                   <textarea name="description" rows="6" required><?= htmlspecialchars(stripslashes($event['description'])) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Ngày thực hiện</label>
                    <input type="date" name="event_date" value="<?= htmlspecialchars($service['event_date'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Ảnh hiện tại</label>
                    <div class="current-image">
                        <?php if(!empty($service['image'])): ?>
                            <img src="<?= dirname(__DIR__, 2) . '/' . htmlspecialchars($service['image']) ?>" alt=""><br>
                            <small><?= htmlspecialchars($service['image']) ?></small>
                        <?php else: ?><em>Chưa có ảnh</em><?php endif; ?>
                    </div>
                    <label>Chọn ảnh mới (hoặc nhập URL)</label>
                    <input type="file" name="image" accept="image/*">
                    <input type="text" name="image_url" placeholder="Hoặc nhập URL ảnh" style="margin-top:8px;">
                </div>
                <div class="form-group">
                    <label>Trạng thái</label>
                    <select name="status">
                        <option value="draft" <?= $service['status'] === 'draft' ? 'selected' : '' ?>>Nháp</option>
                        <option value="published" <?= $service['status'] === 'published' ? 'selected' : '' ?>>Xuất bản</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><input type="checkbox" name="featured" value="1" <?= $service['featured'] ? 'checked' : '' ?>> Dịch vụ nổi bật</label>
                </div>
                <div class="form-group">
                    <button type="submit"><i class="fas fa-save"></i> Lưu thay đổi</button>
                    <a href="../admin_services.php" style="margin-left:10px; text-decoration:none;">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>