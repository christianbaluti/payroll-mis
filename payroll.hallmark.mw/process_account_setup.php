<?php
require_once 'db.php'; // your DB connection
header('Content-Type: application/json');

// Get form data
$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';

// Validate input
if (!$token || !$password) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid token or password']);
    exit;
}

try {
    // Validate token
    $stmt = $conn->prepare("SELECT id FROM users WHERE token = ? AND token IS NOT NULL");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid or expired token.']);
        exit;
    }
    
    $user = $result->fetch_assoc();
    
    // Hash password with stronger options
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT, [
        'cost' => 12
    ]);
    
    // Update user password and clear token
    $update = $conn->prepare("UPDATE users SET password = ?, token = NULL WHERE id = ?");
    $update->bind_param("si", $hashedPassword, $user['id']);
    
    if ($update->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Account setup successful! You can now login.']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to set password. Try again.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'An error occurred. Please try again later.']);
    error_log($e->getMessage());
}
?>