<?php
session_start();
include "../../db2.php"; // Your DB connection

$userId = $_SESSION['user_id']; // Get user ID from session

$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$password = trim($_POST['password']);

// Validate
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
  exit;
}

// Build query
$query = "UPDATE users SET email = ?, phone = ?";
$params = [$email, $phone];

if (!empty($password)) {
  $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
  $query .= ", password = ?";
  $params[] = $hashedPassword;
}

$query .= " WHERE id = ?";
$params[] = $userId;

$stmt = $conn->prepare($query);
$success = $stmt->execute($params);

if ($success) {
  echo json_encode(['success' => true, 'message' => 'Settings updated successfully.']);
  $_SESSION['email']=$email;
  $_SESSION['phone']=$phone;
} else {
  echo json_encode(['success' => false, 'message' => 'Update failed.']);
}
