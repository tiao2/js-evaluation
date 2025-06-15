<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => '请先登录']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$problem_id = intval($data['problem_id']);
$results = $data['results']; // 客户端评测结果
$score = intval($data['score']);
$passed_all = $data['passed_all'] ?? false;

// 获取题目信息
$problem = getProblem($problem_id);
if (!$problem) {
    echo json_encode(['error' => '题目不存在']);
    exit;
}

// 记录提交
$user_id = $_SESSION['user_id'];
$code = $data['code'] ?? ''; // 可选保存代码

$stmt = $pdo->prepare("INSERT INTO submissions (user_id, problem_id, code, result, score, passed) 
                      VALUES (?, ?, ?, ?, ?, ?)");

$result_json = json_encode($results, JSON_UNESCAPED_UNICODE);
$stmt->execute([$user_id, $problem_id, $code, $result_json, $score, $passed_all]);

// 如果全部通过，更新用户积分
$points_to_add = 0;
if ($passed_all) {
    $points_to_add = pow(2, $problem['difficulty']);
    $stmt = $pdo->prepare("UPDATE users SET points = points + ? WHERE id = ?");
    $stmt->execute([$points_to_add, $user_id]);
    
    // 重新计算用户等级
    $stmt = $pdo->prepare("SELECT points FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $level = 0;
    for ($i = 0; $i <= 32; $i++) {
        if ($user['points'] >= pow(2, $i)) {
            $level = $i;
        } else {
            break;
        }
    }
    
    $stmt = $pdo->prepare("UPDATE users SET level = ? WHERE id = ?");
    $stmt->execute([$level, $user_id]);
}

// 返回结果
echo json_encode([
    'success' => true,
    'points_added' => $points_to_add
]);

function getProblem($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM problems WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
