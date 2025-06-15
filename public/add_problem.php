<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

session_start();

// 检查用户是否登录且为管理员
if (!isLoggedIn() || !$_SESSION['is_admin']) {
    header('Location: /public/problems.php');
    exit;
}

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $difficulty = intval($_POST['difficulty'] ?? 1);
    $example_input = $_POST['example_input'] ?? '';
    $example_output = $_POST['example_output'] ?? '';
    
    // 收集测试用例
    $test_cases = [];
    for ($i = 1; $i <= 5; $i++) {
        if (!empty($_POST["input_$i"]) && !empty($_POST["output_$i"])) {
            $test_cases[] = [
                'input' => $_POST["input_$i"],
                'output' => $_POST["output_$i"]
            ];
        }
    }
    
    if (count($test_cases) === 0) {
        $error = '至少需要添加一个测试用例';
    } else {
        require_once __DIR__ . '/../includes/problem.php';
        if (addProblem($title, $description, $difficulty, $example_input, $example_output, $test_cases, $_SESSION['user_id'])) {
            $success = '题目添加成功！';
        } else {
            $error = '添加题目失败，请重试';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>添加题目 - JS评测系统</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="c/ss/style.css">
</head>
<body>
    <?php include_once 'templates/navbar.php'; ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title mb-4"><i class="fas fa-plus-circle me-2"></i>添加新题目</h2>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?= $success ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">题目标题</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">题目描述</label>
                                <textarea class="form-control" name="description" rows="4" required></textarea>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">难度</label>
                                    <select class="form-select" name="difficulty" required>
                                        <option value="1">★ 简单 (1分)</option>
                                        <option value="2">★★ 中等 (2分)</option>
                                        <option value="3">★★★ 中等 (4分)</option>
                                        <option value="4">★★★★ 困难 (8分)</option>
                                        <option value="5">★★★★★ 非常困难 (16分)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">积分奖励</label>
                                    <div class="form-control" id="points-display">2分</div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">示例输入</label>
                                <textarea class="form-control" name="example_input" rows="2"></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">示例输出</label>
                                <textarea class="form-control" name="example_output" rows="2"></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <h4><i class="fas fa-vial me-2"></i>测试用例</h4>
                                <div id="test-cases-container">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <div class="test-case mb-3">
                                        <h5>测试用例 #<?= $i ?></h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="input_<?= $i ?>" placeholder="输入">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="output_<?= $i ?>" placeholder="输出">
                                            </div>
                                        </div>
                                    </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <a href="/problems.php" class="btn btn-secondary me-2">
                                    取消
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>保存题目
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // 更新积分显示
        document.querySelector('select[name="difficulty"]').addEventListener('change', function() {
            const points = Math.pow(2, this.value);
            document.getElementById('points-display').textContent = points + '分';
        });
    </script>
</body>
</html>
