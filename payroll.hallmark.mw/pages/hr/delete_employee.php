
<?php
session_start();
require_once '../../db.php';

$id = $_POST['id'];
if (!$id) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit();
}

try {
    $stmt = $pdo->prepare("DELETE FROM employees WHERE id = ?");
    $stmt->execute([$id]);
    $stmt = $pdo->prepare("DELETE FROM attendance WHERE employee_id = ?");
    $stmt->execute([$id]);
    echo json_encode(['status' => 'success', 'message' => 'Employee deleted successfully.']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete employee.']);
}