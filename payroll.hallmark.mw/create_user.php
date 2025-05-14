<?php
require 'db.php'; // connect to your DB

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $token = bin2hex(random_bytes(16));

    if (!$email || !$phone || !$role || !$password) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "error", "message" => "Email already exists."]);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO users (email, phone, role, password, token) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$email, $phone, $role, $password, $token])) {
        echo json_encode(["status" => "success", "message" => "User created successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to create user."]);
    }
}
?>
