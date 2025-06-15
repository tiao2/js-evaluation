<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

session_start();
$currentUser = isLoggedIn() ? $_SESSION : null;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JS评测系统 - 首页</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <!-- 导航栏 -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/public/">
                <i class="fas fa-laptop-code me-2"></i>
                JS评测系统
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/public/">首页</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/public/problems.php">题目列表</a>
                    </li>
                    <?php if ($currentUser && $currentUser['is_admin']): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/public/add_problem.php">出题</a>
                    </li>
                    <?php endif; ?>
                </ul>
                <div class="d-flex align-items-center" id="auth-section">
                    <?php if ($currentUser): ?>
                    <div class="level-badge me-3" id="user-level" style="background: <?= getLevelColor($currentUser['level']) ?>">
                        <?= $currentUser['level'] ?>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-light dropdown-toggle" id="user-dropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-2"></i>
                            <?= $currentUser['username'] ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" id="logout-link">退出</a></li>
                        </ul>
                    </div>
                    <?php else: ?>
                    <div class="btn-group">
                        <a href="/public/login.php" class="btn btn-outline-light">登录</a>
                        <a href="/public/register.php" class="btn btn-primary">注册</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <h1 class="card-title mb-4">欢迎来到JS评测系统</h1>
                        <p class="card-text mb-4">提升您的JavaScript编程技能，解决有趣的问题，并在排行榜上与其他开发者竞争！</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="/public/problems.php" class="btn btn-primary btn-custom">
                                <i class="fas fa-code me-2"></i>开始编程
                            </a>
                            <a href="/public/problems.php" class="btn btn-success btn-custom">
                                <i class="fas fa-list me-2"></i>查看题目
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title"><i class="fas fa-crown me-2"></i>排行榜</h3>
                                <div class="list-group" id="leaderboard-list">
                                    <?php
                                    $stmt = $pdo->query("SELECT username, points, level FROM users ORDER BY points DESC LIMIT 10");
                                    $leaderboard = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($leaderboard as $index => $user): 
                                        $rankClass = $index === 0 ? 'list-group-item-primary' : 
                                                    ($index === 1 ? 'list-group-item-secondary' : 
                                                    ($index === 2 ? 'list-group-item-warning' : ''));
                                    ?>
                                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= $rankClass ?>">
                                        <div>
                                            <span class="badge bg-primary me-2"><?= $index + 1 ?></span>
                                            <?= htmlspecialchars($user['username']) ?>
                                        </div>
                                        <div>
                                            <span class="badge bg-success me-2">Lv. <?= $user['level'] ?></span>
                                            <span class="badge bg-warning"><?= $user['points'] ?>分</span>
                                        </div>
                                    </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title"><i class="fas fa-lightbulb me-2"></i>系统功能</h3>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item bg-transparent text-light">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        在线编写和测试JavaScript代码
                                    </li>
                                    <li class="list-group-item bg-transparent text-light">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        多种难度级别的编程题目
                                    </li>
                                    <li class="list-group-item bg-transparent text-light">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        积分和等级系统
                                    </li>
                                    <li class="list-group-item bg-transparent text-light">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        提交历史记录查看
                                    </li>
                                    <li class="list-group-item bg-transparent text-light">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        管理员出题功能
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/script.js"></script>
    <script>
        // 退出功能
        document.getElementById('logout-link')?.addEventListener('click', function(e) {
            e.preventDefault();
            fetch('/api/logout.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                });
        });
    </script>
</body>
</html>
