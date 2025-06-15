<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json');

$stmt = $pdo->query("SELECT id, title, description, difficulty FROM problems");
$problems = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($problems);
