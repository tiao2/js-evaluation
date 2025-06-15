<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';
$confirmPassword = $data['confirm_password'] ?? '';

// 验证输入
if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
    http_response_code(400);
    echo json_encode(['error' => '所有字段都是必填的']);
    exit;
}

if ($password !== $confirmPassword) {  // 修复：移除多余的括号
    http_response_code(400);
    echo json_encode(['error' => '两次输入的密码不一致']);
    exit;
}

// 注册用户
$result = registerUser($username, $email, $password);

if (isset($result['error'])) {
    http_response_code(400);
    echo json_encode(['error' => $result['error']]);
} else {
    http_response_code(201);
    echo json_encode(['success' => true, 'user_id' => $result['user_id']]);
}
