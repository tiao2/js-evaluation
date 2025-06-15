<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

session_start();

// 如果用户已经登录，重定向到首页
if (isLoggedIn()) {
    header('Location: /public/');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // 验证输入
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = '所有字段都是必填的';
    } elseif ($password !== $confirmPassword) {
        $error = '两次输入的密码不一致';
    } else {
        $result = registerUser($username, $email, $password);
        if (isset($result['error'])) {
            $error = $result['error'];
        } else {
            // 注册成功，自动登录
            if (loginUser($username, $password)) {
                header('Location: /public/');
                exit;
            } else {
                $error = '注册成功，但自动登录失败，请手动登录';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注册 - JS评测系统</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/../css/style.css">
</head>
<body>
    <?php include_once 'templates/navbar.php'; ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">用户注册</h2>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">用户名</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">电子邮箱</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">密码</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">确认密码</label>
                                <input type="password" class="form-control" name="confirm_password" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-custom">
                                    <i class="fas fa-user-plus me-2"></i>注册
                                </button>
                            </div>
                            
                            <div class="text-center mt-3">
                                <p>已有账号? <a href="/public/login.php">立即登录</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
