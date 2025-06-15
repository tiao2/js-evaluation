// 全局变量
let currentUser = null;
let currentProblemId = null;

// DOM 元素
const mainContent = document.getElementById('main-content');
const userLevel = document.getElementById('user-level');
const usernameDisplay = document.getElementById('username-display');
const authSection = document.getElementById('auth-section');
const userDropdown = document.getElementById('user-dropdown');

// 页面初始化
document.addEventListener('DOMContentLoaded', () => {
    checkAuthStatus();
    
    // 添加事件监听器
    document.getElementById('logout-link')?.addEventListener('click', logout);
});

// 检查认证状态
function checkAuthStatus() {
    fetch('/api/auth_status.php')
        .then(response => response.json())
        .then(data => {
            if (data.loggedIn) {
                currentUser = data.user;
                updateUserUI();
            } else {
                currentUser = null;
                resetUserUI();
            }
        });
}

// 更新用户界面
function updateUserUI() {
    usernameDisplay.textContent = currentUser.username;
    userLevel.textContent = currentUser.level;
    userLevel.style.background = getLevelColor(currentUser.level);
}

// 重置用户界面
function resetUserUI() {
    usernameDisplay.textContent = '未登录';
    userLevel.textContent = '0';
    userLevel.style.background = 'linear-gradient(135deg, #3498db, #2c3e50)';
}

// 根据等级获取颜色
function getLevelColor(level) {
    if (level < 5) return 'linear-gradient(135deg, #3498db, #2c3e50)';
    if (level < 10) return 'linear-gradient(135deg, #2ecc71, #16a085)';
    if (level < 15) return 'linear-gradient(135deg, #9b59b6, #8e44ad)';
    if (level < 20) return 'linear-gradient(135deg, #f39c12, #d35400)';
    return 'linear-gradient(135deg, #e74c3c, #c0392b)';
}

// 退出功能
function logout() {
    fetch('/api/logout.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        });
}

// 显示状态消息
function showStatus(message, type) {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed top-0 end-0 m-3`;
    alert.style.zIndex = 1000;
    alert.innerHTML = `
        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
        ${message}
    `;
    document.body.appendChild(alert);
    
    setTimeout(() => {
        alert.remove();
    }, 3000);
}
