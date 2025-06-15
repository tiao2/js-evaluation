<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode(['error' => '鐢ㄦ埛鍚嶅拰瀵嗙爜涓嶈兘涓虹┖']);
    exit;
}

if (loginUser($username, $password)) {
    echo json_encode(['success' => true, 'user' => $_SESSION]);
} else {
    echo json_encode(['error' => '鐢ㄦ埛鍚嶆垨瀵嗙爜閿欒']);
}
