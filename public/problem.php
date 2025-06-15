<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/problem.php';

session_start();
$currentUser = isLoggedIn() ? $_SESSION : null;

if (!isset($_GET['id'])) {
    header('Location: /public/');
    exit;
}

$problemId = intval($_GET['id']);
$problem = getProblem($problemId);

if (!$problem) {
    header('Location: /public/');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM submissions WHERE problem_id = :id ORDER BY score DESC");
$stmt->bindParam(':id', $problemId, PDO::PARAM_INT);
$stmt->execute();
$submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($problem['title']) ?> - JS评测系统</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/../css/problem.css">
</head>
<body>
    <!-- 导航栏（与index.php相同） -->
    
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h1 class="card-title"><?= htmlspecialchars($problem['title']) ?></h1>
                            <span class="difficulty-badge diff-<?= $problem['difficulty'] ?>">
                                难度: <?= str_repeat('★', $problem['difficulty']) ?>
                            </span>
                        </div>
                        <div class="mb-4">
                            <h4><i class="fas fa-book me-2"></i>题目描述</h4>
                            <p class="card-text"><?= nl2br(htmlspecialchars($problem['description'])) ?></p>
                        </div>
                        
                        <div class="mb-4">
                            <h4><i class="fas fa-lightbulb me-2"></i>示例</h4>
                            <div class="bg-light p-3 rounded">
                                <div class="mb-2">
                                    <strong>输入:</strong>
                                    <pre class="mb-0"><?= htmlspecialchars($problem['example_input']) ?></pre>
                                </div>
                                <div>
                                    <strong>输出:</strong>
                                    <pre class="mb-0"><?= htmlspecialchars($problem['example_output']) ?></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h4><i class="fas fa-coins me-2"></i>积分奖励</h4>
                        <p>成功通过此题可获得 <span class="badge bg-warning"><?= pow(2, $problem['difficulty']) ?>分</span></p>
                        
                        <h4 class="mt-4"><i class="fas fa-trophy me-2"></i>提交记录</h4>
                        <?php foreach ($submissions as $index => $submission): ?>
                        <div class="text-center my-3">
<?php
    $sql = "SELECT username FROM users WHERE id = :user_id";
    $stmtf = $pdo->prepare($sql);
    $stmtf->bindParam(':user_id', $submission['user_id'], PDO::PARAM_INT);
    $stmtf->execute();
    $user = $stmtf->fetch(PDO::FETCH_ASSOC);
?>
                            <div class="level-badge mx-auto mb-2" style="background:<?= getLevelColor($index * 5 + 1) ?>"><?= $index + 1 ?></div>
                            <p class="mb-0"><?= htmlspecialchars($user['username']) ?></p>
                            <small class="text-muted">分数: <?= $submission['score'] ?></small>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title mb-4"><i class="fas fa-code me-2"></i>代码编辑器</h2>
                        
                        <div class="file-import-area" id="drop-area">
                            <i class="fas fa-file-import"></i>
                            <h4>导入JavaScript文件</h4>
                            <p>拖放.js文件到此处，或点击下方按钮选择文件</p>
                            <label for="file-input" class="btn btn-primary btn-custom">
                                <i class="fas fa-folder-open me-2"></i> 选择文件
                            </label>
                            <input type="file" id="file-input" accept=".js">
                        </div>
                        
                        <div class="editor-container">
                            <textarea id="code-editor">function solution(input) {
    // 在这里编写您的代码
    
}</textarea>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-3 mb-4">
                            <button class="btn btn-secondary btn-custom" id="reset-code-btn">
                                <i class="fas fa-redo me-2"></i>重置代码
                            </button>
                            <button class="btn btn-primary btn-custom" id="run-code-btn">
                                <i class="fas fa-play me-2"></i>运行测试
                            </button>
                            <button class="btn btn-success btn-custom" id="submit-code-btn">
                                <i class="fas fa-paper-plane me-2"></i>提交评测
                            </button>
                        </div>
                        
                        <div id="test-results">
                            <h4><i class="fas fa-vial me-2"></i>测试结果</h4>
                            <div class="text-center py-4" id="results-placeholder">
                                点击"运行测试"按钮查看测试结果
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
        const problemId = <?= $problemId ?>;
        
        // 文件导入功能
        document.getElementById('file-input').addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('code-editor').value = e.target.result;
                    showStatus('文件导入成功', 'success');
                };
                reader.readAsText(this.files[0]);
            }
        });
        
        // 重置代码
        document.getElementById('reset-code-btn').addEventListener('click', () => {
            document.getElementById('code-editor').value = `function solution(input) {
    // 在这里编写您的代码
    
}`;
        });
        
        // 运行测试
        document.getElementById('run-code-btn').addEventListener('click', () => {
            runTests(problemId, false);
        });
        
        // 提交评测
        document.getElementById('submit-code-btn').addEventListener('click', () => {
            <?php if ($currentUser): ?>
                runTests(problemId, true);
            <?php else: ?>
                showStatus('请先登录后再提交', 'error');
                window.location.href = '/public/login.php';
            <?php endif; ?>
        });

// 创建更安全的沙箱环境
function createSafeSandbox() {
    const iframe = document.createElement('iframe');
    iframe.sandbox = 'allow-scripts'; // 只允许脚本执行
    iframe.style.display = 'none';
    document.body.appendChild(iframe);
    
    // 创建安全的上下文
    const safeWindow = iframe.contentWindow;
    const safeDocument = safeWindow.document;
    
    // 禁用危险功能
    safeWindow.eval = null;
    safeWindow.Function = null;
    safeWindow.setTimeout = null;
    safeWindow.setInterval = null;
    safeWindow.alert = null;
    safeWindow.confirm = null;
    safeWindow.prompt = null;
    safeWindow.open = null;
    safeWindow.XMLHttpRequest = null;
    safeWindow.fetch = null;
    safeWindow.WebSocket = null;
    safeWindow.importScripts = null;
    
    // 清空文档内容
    safeDocument.write('<html><body></body></html>');
    safeDocument.close();
    
    return safeWindow;
}

// 在沙箱中运行用户代码
function runInSandbox(code, input) {
    const sandbox = createSafeSandbox();
    
    try {
        // 定义解决方案函数
        sandbox.solution = null;
        
        // 执行用户代码
        sandbox.eval(`
            ${code}
            if (typeof solution !== 'function') {
                throw new Error('未定义solution函数');
            }
        `);
        
        // 执行解决方案
        const result = sandbox.solution(input);
        
        // 清理沙箱
        document.body.removeChild(sandbox.frameElement);
        
        return {
            success: true,
            result: result
        };
    } catch (error) {
        // 清理沙箱
        if (sandbox.frameElement) {
            document.body.removeChild(sandbox.frameElement);
        }
        return {
            success: false,
            error: error.message
        };
    }
}

// 运行所有测试用例
async function runTests(problemId, isSubmission) {
    const code = document.getElementById('code-editor').value;
    const resultsPlaceholder = document.getElementById('results-placeholder');
    
    resultsPlaceholder.innerHTML = `
        <div class="d-flex justify-content-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">评测中...</span>
            </div>
            <span class="ms-3">正在评测您的代码，请稍候...</span>
        </div>
    `;
    
    try {
        // 获取题目信息
        const response = await fetch(`/api/get_problem.php?id=${problemId}`);
        const problem = await response.json();
        
        if (!problem) {
            throw new Error('题目不存在');
        }
        
        // 解析测试用例
        const testCases = JSON.parse(problem.test_cases);
        const results = [];
        let passed = 0;
        
        // 运行每个测试用例
        for (const [index, testCase] of testCases.entries()) {
            // 解析输入值（支持JSON格式）
            let inputValue;
            try {
                inputValue = JSON.parse(testCase.input);
            } catch {
                inputValue = testCase.input;
            }
            
            // 在沙箱中运行代码
            const executionResult = runInSandbox(code, inputValue);
            
            let actual, passedCase;
            if (executionResult.success) {
                // 处理函数返回结果
                actual = typeof executionResult.result === 'object' 
                    ? JSON.stringify(executionResult.result) 
                    : String(executionResult.result);
                
                passedCase = actual === testCase.output;
            } else {
                actual = executionResult.error;
                passedCase = false;
            }
            
            if (passedCase) passed++;
            
            results.push({
                input: testCase.input,
                expected: testCase.output,
                actual: actual,
                passed: passedCase
            });
        }
        
        // 计算得分
        const score = Math.round((passed / testCases.length) * 100);
        const passedAll = passed === testCases.length;
        
        // 显示结果
        displayResults(results, score, passed, testCases.length);
        
        // 如果是提交，保存结果到服务器
        if (isSubmission) {
            saveSubmission(problemId, code, results, score, passedAll);
        }
    } catch (error) {
        resultsPlaceholder.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>评测过程中发生错误: ${escapeHtml(error.message)}
            </div>
        `;
    }
}

// 显示评测结果
function displayResults(results, score, passed, total) {
    let resultsHTML = '';
    
    results.forEach((result, index) => {
        resultsHTML += `
            <div class="result-item ${result.passed ? 'status-pass' : 'status-fail'}">
                <div class="d-flex justify-content-between align-items-center">
                    <h5>测试用例 #${index + 1}</h5>
                    <span class="badge ${result.passed ? 'bg-success' : 'bg-danger'}">
                        ${result.passed ? '通过' : '失败'}
                    </span>
                </div>
                <div class="mt-2">
                    <div><strong>输入:</strong> ${escapeHtml(result.input)}</div>
                    <div><strong>预期输出:</strong> ${escapeHtml(result.expected)}</div>
                    <div><strong>实际输出:</strong> ${escapeHtml(result.actual)}</div>
                </div>
            </div>
        `;
    });
    
    resultsHTML += `
        <div class="alert ${score === 100 ? 'alert-success' : 'alert-warning'} mt-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas ${score === 100 ? 'fa-check-circle' : 'fa-exclamation-triangle'} me-2"></i>
                    得分: ${score}% (通过 ${passed}/${total} 个测试用例)
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('results-placeholder').innerHTML = resultsHTML;
}

// 保存提交记录
function saveSubmission(problemId, code, results, score, passedAll) {
    fetch('/api/submit.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            problem_id: problemId,
            code: code,
            results: results,
            score: score,
            passed_all: passedAll
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showStatus('提交成功!', 'success');
            
            // 如果获得积分，显示提示
            if (data.points_added > 0) {
                const pointsElement = document.createElement('div');
                pointsElement.className = 'mt-2 text-center';
                pointsElement.innerHTML = `
                    <span class="badge bg-warning">
                        <i class="fas fa-coins me-1"></i> +${data.points_added}积分
                    </span>
                `;
                document.querySelector('.alert-success').appendChild(pointsElement);
            }
        } else {
            showStatus('提交保存失败: ' + (data.error || '未知错误'), 'error');
        }
    })
    .catch(error => {
        showStatus('提交保存失败: ' + error.message, 'error');
    });
}

// 添加HTML转义函数
function escapeHtml(str) {
    return String(str).replace(/[&<>"'`]/g, function(match) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
            '`': '&#x60;'
        }[match];
    });
}
    </script>
</body>
</html>
