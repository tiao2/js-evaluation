<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['error' => '题目ID未指定']);
    exit;
}

$id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM problems WHERE id = ?");
$stmt->execute([$id]);
$problem = $stmt->fetch(PDO::FETCH_ASSOC);

if ($problem) {
    echo json_encode($problem);
} else {
    echo json_encode(['error' => '题目不存在']);
}
