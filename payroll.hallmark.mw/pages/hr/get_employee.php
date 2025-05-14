<?php
header('Content-Type: application/json');

// Include the DB connection file
require_once '../../db.php';

// Validate ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Employee ID is required.']);
    exit;
}

$employeeId = $_GET['id'];

try {
    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT 
                                id, name, email, position, department_id, gender, salary, 
                                bank_name, bank_branch, bank_account_name, branch_code, 
                                hours_per_week, status, remarks, hourly_rate, hours_per_weekday, hours_per_weekend 
                            FROM employees 
                            WHERE id = :id");
    $stmt->execute(['id' => $employeeId]);
    $employee = $stmt->fetch();

    if ($employee) {
        echo json_encode(['status' => 'success', 'data' => $employee]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Employee not found.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
