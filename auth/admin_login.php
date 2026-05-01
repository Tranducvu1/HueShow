<?php
require_once '../config.php';
if (isLoggedIn()) {
    header('Location: ../admin/admin_dashboard.php');
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    if (empty($username) || empty($password)) {
        $error = 'Vui lòng nhập đầy đủ thông tin';
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['fullname'] = $user['fullname'];
            header('Location: ../admin/admin_dashboard.php');
            exit;
        } else {
            $error = 'Tên đăng nhập hoặc mật khẩu không đúng';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin - HueShow</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:linear-gradient(135deg,#0f172a,#1e293b); min-height:100vh; display:flex; align-items:center; justify-content:center; }
        .login-card { background:white; border-radius:2rem; padding:2rem; width:100%; max-width:400px; box-shadow:0 20px 35px -12px rgba(0,0,0,0.3); }
        .login-card h2 { text-align:center; margin-bottom:1.5rem; color:#0f172a; }
        .form-group { margin-bottom:1.2rem; }
        input { width:100%; padding:12px 16px; border:1px solid #e2e8f0; border-radius:1rem; font-family:inherit; transition:0.2s; }
        input:focus { outline:none; border-color:#facc15; box-shadow:0 0 0 3px rgba(250,204,21,0.2); }
        button { background:linear-gradient(95deg,#facc15,#eab308); border:none; width:100%; padding:12px; border-radius:2rem; font-weight:700; cursor:pointer; transition:0.2s; }
        button:hover { transform:translateY(-2px); }
        .error { background:#fee2e2; color:#991b1b; padding:10px; border-radius:1rem; margin-bottom:1rem; text-align:center; }
        .register-link { text-align:center; margin-top:1rem; }
    </style>
</head>
<body>
<div class="login-card">
    <h2><i class="fas fa-lock"></i> Đăng nhập Admin</h2>
    <?php if($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group"><input type="text" name="username" placeholder="Tên đăng nhập" required></div>
        <div class="form-group"><input type="password" name="password" placeholder="Mật khẩu" required></div>
        <button type="submit">Đăng nhập</button>
    </form>
    <div class="register-link"><a href="admin_register.php">Chưa có tài khoản? Đăng ký</a></div>
</div>
</body>
</html>