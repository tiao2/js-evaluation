<?php
require_once __DIR__ . '/../includes/auth.php';

session_start();
header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    echo json_encode([
        'loggedIn' => true,
        'user' => [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'email' => $_SESSION['email'],
            'points' => $_SESSION['points'],
            'level' => $_SESSION['level'],
            'is_admin' => $_SESSION['is_admin']
        ]
    ]);
} else {
    echo json_encode(['loggedIn' => false]);
}
