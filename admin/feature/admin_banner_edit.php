<?php
require_once '../../config.php'; 
requireAdmin();

$id = (int)($_GET['id'] ?? 0);
$banner = getBannerById($id);
if (!$banner) {
    header('Location: ../admin_banners.php');
    exit;
}

$error = '';
$success = '';

$uploadDir = '../../uploads/banners/';
if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $link = $_POST['link'] ?? '';
    $order_index = (int)($_POST['order_index'] ?? 0);
    $status = $_POST['status'] ?? 'active';
    $currentImage = $banner['image_url'];
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg','jpeg','png','gif','webp'];
        $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($fileExt, $allowed)) {
            $newFileName = time() . '_' . uniqid() . '.' . $fileExt;
            $destination = $uploadDir . $newFileName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                // Xóa ảnh cũ
                $oldPath = '../../' . $currentImage;
                if (file_exists($oldPath) && is_file($oldPath)) {
                    unlink($oldPath);
                }
                $currentImage = 'uploads/banners/' . $newFileName;
            } else $error = 'Không thể upload ảnh mới.';
        } else $error = 'Chỉ chấp nhận file ảnh.';
    }
    
    if (empty($error) && !empty($title)) {
        if (updateBanner($id, $title, $currentImage, $link, $order_index, $status)) {
            $success = 'Cập nhật banner thành công!';
            $banner = getBannerById($id);
        } else $error = 'Có lỗi xảy ra khi cập nhật.';
    } elseif (empty($error)) $error = 'Vui lòng nhập tiêu đề.';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Banner - Admin</title>
    <link rel="icon" type="image/jpeg" href="../assets/logo.jpg">
    <link rel="shortcut icon" type="image/jpeg" href="../assets/logo.jpg">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* ------- GIỐNG STYLE CỦA FILE THÊM (đã làm đẹp) ------- */
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:#f8fafc; }
        .admin-wrapper { display:flex; min-height:100vh; }
        .sidebar { width:280px; background:linear-gradient(135deg,#0f172a,#1e293b); color:#cbd5e1; }
        .sidebar-header { padding:24px 20px; border-bottom:1px solid #334155; }
        .sidebar-header h3 { color:#facc15; }
        .sidebar-menu li a { display:flex; align-items:center; gap:12px; padding:12px 20px; color:#e2e8f0; text-decoration:none; transition:0.2s; }
        .sidebar-menu li a:hover, .sidebar-menu li.active a { background:#334155; color:#facc15; }
        .main-content { flex:1; padding:24px; display:flex; justify-content:center; align-items:flex-start; }
        .card { background:white; border-radius:1.5rem; padding:2rem; max-width:650px; width:100%; box-shadow:0 10px 25px -5px rgba(0,0,0,0.05),0 8px 10px -6px rgba(0,0,0,0.02); transition:0.2s; }
        .card h2 { font-size:1.75rem; font-weight:600; margin-bottom:1.5rem; color:#0f172a; letter-spacing:-0.01em; }
        .form-group { margin-bottom:1.5rem; }
        label { display:block; font-weight:500; margin-bottom:0.5rem; color:#1e293b; font-size:0.9rem; }
        input, select { width:100%; padding:0.75rem 1rem; border:1px solid #e2e8f0; border-radius:0.75rem; font-family:inherit; font-size:0.95rem; transition:all 0.2s; background:#fff; }
        input:focus, select:focus { outline:none; border-color:#facc15; box-shadow:0 0 0 3px rgba(250,204,21,0.2); }
        small { font-size:0.75rem; color:#64748b; display:block; margin-top:0.25rem; }
        /* Nhóm nút cuối cùng - dùng flex */
        .form-group:last-child { display:flex; gap:1rem; align-items:center; margin-top:0.5rem; }
        /* Nút Quay lại */
        .btn-back {
            display:inline-flex;
            align-items:center;
            gap:0.5rem;
            background:#ffffff;
            border:1px solid #cbd5e1;
            color:#334155;
            padding:0.65rem 1.5rem;
            border-radius:2.5rem;
            font-weight:500;
            font-size:0.9rem;
            text-decoration:none;
            transition:all 0.2s ease;
            cursor:pointer;
        }
        .btn-back i { font-size:0.9rem; transition:transform 0.2s; }
        .btn-back:hover {
            background:#f8fafc;
            border-color:#94a3b8;
            transform:translateY(-2px);
            box-shadow:0 4px 8px -2px rgba(0,0,0,0.08);
        }
        .btn-back:hover i { transform:translateX(-3px); }
        /* Nút Cập nhật */
        .btn-submit {
            display:inline-flex;
            align-items:center;
            gap:0.5rem;
            background:#facc15;
            border:none;
            color:#0f172a;
            padding:0.65rem 1.8rem;
            border-radius:2.5rem;
            font-weight:600;
            font-size:0.9rem;
            cursor:pointer;
            transition:all 0.2s ease;
            box-shadow:0 1px 2px rgba(0,0,0,0.05);
        }
        .btn-submit i { font-size:0.9rem; }
        .btn-submit:hover {
            background:#eab308;
            transform:translateY(-2px);
            box-shadow:0 8px 15px -6px rgba(250,204,21,0.4);
        }
        .btn-submit:active, .btn-back:active { transform:translateY(0); }
        /* Alert */
        .alert { padding:0.85rem 1rem; border-radius:0.75rem; margin-bottom:1.5rem; font-size:0.9rem; }
        .alert-success { background:#dcfce7; color:#166534; border-left:4px solid #22c55e; }
        .alert-error { background:#fee2e2; color:#991b1b; border-left:4px solid #ef4444; }
        /* Upload ảnh */
        .file-label {
            background:#f1f5f9;
            border:1px dashed #cbd5e1;
            padding:0.7rem 1rem;
            border-radius:0.75rem;
            cursor:pointer;
            display:inline-flex;
            align-items:center;
            gap:0.5rem;
            font-weight:500;
            transition:0.2s;
        }
        .file-label:hover { background:#e2e8f0; border-color:#94a3b8; }
        .file-input { display:none; }
        .preview-img {
            max-width:100%;
            max-height:200px;
            margin-top:1rem;
            border-radius:0.75rem;
            display:none;
            border:1px solid #e2e8f0;
            object-fit:cover;
            box-shadow:0 1px 3px rgba(0,0,0,0.05);
        }
        .current-img img {
            max-width:200px;
            border-radius:0.75rem;
            margin-bottom:0.75rem;
            border:1px solid #e2e8f0;
            box-shadow:0 1px 3px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <?php include __DIR__ . '../../../includes/be/sidebar.php'; ?>
    <div class="main-content">
        <div class="card">
            <h2>✏️ Sửa Banner</h2>
            
            <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Tiêu đề *</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($banner['title']) ?>" placeholder="VD: Khuyến mãi Tết" required>
                </div>
                <div class="form-group">
                    <label>Hình ảnh hiện tại</label>
                    <div class="current-img">
                        <?php if ($banner['image_url']): ?>
                            <img src="../../<?= htmlspecialchars($banner['image_url']) ?>" alt="Banner hiện tại">
                        <?php endif; ?>
                    </div>
                    <label class="file-label">
                        <i class="fas fa-cloud-upload-alt"></i> Chọn ảnh mới (để trống nếu không đổi)
                        <input type="file" name="image" id="imageInput" class="file-input" accept="image/*">
                    </label>
                    <img id="preview" class="preview-img" alt="Xem trước ảnh mới">
                </div>
                <div class="form-group">
                    <label>Link (URL đích)</label>
                    <input type="url" name="link" value="<?= htmlspecialchars($banner['link']) ?>" placeholder="https://example.com/khuyen-mai">
                    <small>Để trống nếu banner chỉ trang trí</small>
                </div>
                <div class="form-group">
                    <label>Thứ tự hiển thị</label>
                    <input type="number" name="order_index" value="<?= $banner['order_index'] ?>" step="1">
                </div>
                <div class="form-group">
                    <label>Trạng thái</label>
                    <select name="status">
                        <option value="active" <?= $banner['status'] == 'active' ? 'selected' : '' ?>>Kích hoạt</option>
                        <option value="inactive" <?= $banner['status'] == 'inactive' ? 'selected' : '' ?>>Ẩn</option>
                    </select>
                </div>
                <div class="form-group">
                    <a href="../admin_banners.php" class="btn-back"><i class="fas fa-arrow-left"></i> Quay lại</a>
                    <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById('imageInput').addEventListener('change', function(e) {
        const preview = document.getElementById('preview');
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => { preview.src = event.target.result; preview.style.display = 'block'; };
            reader.readAsDataURL(file);
        } else preview.style.display = 'none';
    });
</script>
</body>
</html>