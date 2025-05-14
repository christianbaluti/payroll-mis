<?php
require './db2.php';

$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if (!$token) {
    echo json_encode(['status' => 'error', 'message' => 'Missing token.']);
    exit;
}

// Check if token is valid
$stmt = $conn->prepare("SELECT email FROM users WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid or expired token.']);
    exit;
}

$row = $result->fetch_assoc();
$email = $row['email'];

if ($password !== $confirm_password) {
    echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Update password and clear token
$stmt = $conn->prepare("UPDATE users SET password = ?, token = NULL WHERE email = ?");
$stmt->bind_param("ss", $hashed_password, $email);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Password set successfully!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to set password.']);
}
?>
