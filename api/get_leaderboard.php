<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json');

// 获取排行榜前10名用户
$stmt = $pdo->query("SELECT username, points, level FROM users ORDER BY points DESC LIMIT 10");
$leaderboard = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($leaderboard);
