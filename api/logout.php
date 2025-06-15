<?php
require_once __DIR__ . '/../includes/auth.php';

session_start();
$result = logout();

echo json_encode($result);
