<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $pass = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['phone'] = $user['phone'];

        if ($user['role'] === 'admin') {
            echo json_encode(['status' => 'success', 'redirect' => 'pages/admin/index.php']);
        } elseif ($user['role'] === 'hr') {
            echo json_encode(['status' => 'success', 'redirect' => 'pages/hr/index.php']);
        } elseif ($user['role'] === 'manager') {
            echo json_encode(['status' => 'success', 'redirect' => 'pages/manager/index.php']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid role.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
    }
}
?>
