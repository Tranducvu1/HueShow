<?php
require_once __DIR__ . '/../config.php';
if (isLoggedIn()) { header('Location: ../admin/admin_dashboard.php'); exit; }
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $fullname = $_POST['fullname'] ?? '';
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Vui lòng điền đầy đủ thông tin bắt buộc';
    } elseif ($password !== $confirm) {
        $error = 'Mật khẩu xác nhận không khớp';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ';
    } else {
        // Check existing username/email
        $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $check->bind_param("ss", $username, $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $error = 'Tên đăng nhập hoặc email đã tồn tại';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $role = 'admin'; // default role for admin registration
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, fullname, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $email, $hashed, $fullname, $role);
            if ($stmt->execute()) {
                $success = 'Đăng ký thành công! Vui lòng đăng nhập.';
            } else {
                $error = 'Lỗi đăng ký, thử lại sau';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký Admin - HueShow</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:linear-gradient(135deg,#0f172a,#1e293b); min-height:100vh; display:flex; align-items:center; justify-content:center; }
        .register-card { background:white; border-radius:2rem; padding:2rem; width:100%; max-width:450px; box-shadow:0 20px 35px -12px rgba(0,0,0,0.3); }
        h2 { text-align:center; margin-bottom:1.5rem; color:#0f172a; }
        .form-group { margin-bottom:1rem; }
        input { width:100%; padding:12px 16px; border:1px solid #e2e8f0; border-radius:1rem; font-family:inherit; }
        input:focus { outline:none; border-color:#facc15; }
        button { background:linear-gradient(95deg,#facc15,#eab308); width:100%; padding:12px; border-radius:2rem; font-weight:700; border:none; cursor:pointer; transition:0.2s; }
        button:hover { transform:translateY(-2px); }
        .error { background:#fee2e2; color:#991b1b; padding:10px; border-radius:1rem; margin-bottom:1rem; text-align:center; }
        .success { background:#dcfce7; color:#166534; padding:10px; border-radius:1rem; margin-bottom:1rem; text-align:center; }
        .login-link { text-align:center; margin-top:1rem; }
    </style>
</head>
<body>
<div class="register-card">
    <h2><i class="fas fa-user-plus"></i> Đăng ký tài khoản</h2>
    <?php if($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if($success): ?><div class="success"><?= htmlspecialchars($success) ?> <a href="admin_login.php">Đăng nhập ngay</a></div><?php endif; ?>
    <form method="POST">
        <div class="form-group"><input type="text" name="username" placeholder="Tên đăng nhập *" required></div>
        <div class="form-group"><input type="email" name="email" placeholder="Email *" required></div>
        <div class="form-group"><input type="text" name="fullname" placeholder="Họ và tên"></div>
        <div class="form-group"><input type="password" name="password" placeholder="Mật khẩu *" required></div>
        <div class="form-group"><input type="password" name="confirm_password" placeholder="Xác nhận mật khẩu *" required></div>
        <button type="submit">Đăng ký</button>
    </form>
    <div class="login-link">Đã có tài khoản? <a href="admin_login.php">Đăng nhập</a></div>
</div>
</body>
</html>