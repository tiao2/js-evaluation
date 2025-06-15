<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

$currentUser = isLoggedIn() ? $_SESSION : null;
$stmt = $pdo->query("SELECT id, title, description, difficulty FROM problems");
$problems = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JS评测系统 - 题目列表</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel='stylesheet' href='/css/main.css'/>
</head>
<body>
    <div class="container">
        <div class="main-content">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title"><i class="fas fa-list"></i> 题目列表</h2>
                    <div class="filters" hidden>
                        <button class="filter-btn active">全部</button>
                        <button class="filter-btn">未解决</button>
                        <button class="filter-btn">已解决</button>
                        <select class="filter-btn">
                            <option>难度: 全部</option>
                            <option>难度: 1星</option>
                            <option>难度: 2星</option>
                            <option>难度: 3星</option>
                            <option>难度: 4星</option>
                            <option>难度: 5星</option>
                        </select>
                    </div>
                </div>
                
                <div class="problems-grid">
                    <?php foreach ($problems as $problem): ?>
                    <div class="problem-card unsolved" onclick='location.href=`/public/problem.php?id=<?= $problem["id"] ?>`;'>
                        <div class="difficulty difficulty-<?= $problem['difficulty'] ?>"><?= $problem['difficulty'] ?>星</div>
                        <h3 class="problem-title"><i class="fas fa-check-circle"></i> <?= $problem['title'] ?></h3>
                        <p><?= $problem['description'] ?></p>
                        <div class="problem-stats">
                            <span class="problem-points">+<?= pow(2, $problem['difficulty']) ?>分</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <footer>
            <p>JS评测系统 &copy; 2025 | 题目列表 | 每通过一题得2<sup>m</sup>分，m为难度</p>
            <p>用户等级规则：积分 ≥ 2<sup>n</sup> 则为n级 (0 ≤ n ≤ 30)</p>
        </footer>
    </div>
</body>
</html>
