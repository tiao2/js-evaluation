<?php
require_once 'db.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function loginUser($username, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['points'] = $user['points'];
        $_SESSION['level'] = $user['level'];
        $_SESSION['is_admin'] = $user['is_admin'];
        return true;
    }
    return false;
}

function logout() {
    session_unset();
    session_destroy();
    return ['success' => true];
}

function registerUser($username, $email, $password) {
    global $pdo;
    
    // 检查用户名是否已存在
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        return ['error' => '用户名已存在'];
    }
    
    // 检查邮箱是否已存在
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return ['error' => '邮箱已注册'];
    }
    
    // 创建用户
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    
    if ($stmt->execute([$username, $email, $hashed_password])) {
        return ['success' => true, 'user_id' => $pdo->lastInsertId()];
    }
    
    return ['error' => '注册失败'];
}

function getLevelColor($level) {
    if ($level < 5) return 'linear-gradient(135deg, #3498db, #2c3e50)';
    if ($level < 10) return 'linear-gradient(135deg, #2ecc71, #16a085)';
    if ($level < 15) return 'linear-gradient(135deg, #9b59b6, #8e44ad)';
    if ($level < 20) return 'linear-gradient(135deg, #f39c12, #d35400)';
    return 'linear-gradient(135deg, #e74c3c, #c0392b)';
}
