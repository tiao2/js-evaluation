<?php
require_once 'db.php';

function getLeaderboard() {
    global $pdo;
    $stmt = $pdo->query("SELECT username, points, level FROM users ORDER BY points DESC LIMIT 10");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProblems() {
    global $pdo;
    $stmt = $pdo->query("SELECT id, title, description, difficulty FROM problems");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProblem($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM problems WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addProblem($title, $description, $difficulty, $example_input, $example_output, $test_cases, $created_by) {
    global $pdo;
    
    $test_cases_json = json_encode($test_cases);
    
    $stmt = $pdo->prepare("INSERT INTO problems (title, description, difficulty, example_input, example_output, test_cases, created_by) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    return $stmt->execute([$title, $description, $difficulty, $example_input, $example_output, $test_cases_json, $created_by]);
}
