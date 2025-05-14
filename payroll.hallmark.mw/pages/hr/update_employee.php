<?php
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Include DB connection
require_once '../../db.php';

// Validate required fields
$requiredFields = ['id', 'name', 'email', 'gender', 'position', 'department_id', 'salary'];
foreach ($requiredFields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        echo json_encode(['status' => 'error', 'message' => "Missing or empty field: $field"]);
        exit;
    }
}

// Assign POST values
$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$gender = $_POST['gender'];
$position = $_POST['position'];
$department_id = $_POST['department_id'];
$salary = $_POST['salary'];
$bank_name = $_POST['bank_name'] ?? null;
$bank_branch = $_POST['bank_branch'] ?? null;
$bank_account_name = $_POST['bank_account_name'] ?? null;
$branch_code = $_POST['branch_code'] ?? null;
$hours_per_week = $_POST['hours_per_week'] ?? null;
$status = $_POST['status'] ?? 'active';
$remarks = $_POST['remarks'] ?? null;
$hourly_rate = $_POST['hourly_rate'] ?? null;
$hours_per_weekday = $_POST['hours_per_weekday'] ?? null;
$hours_per_weekend = $_POST['hours_per_weekend'] ?? null;

try {
    // Prepare the update query
    $sql = "UPDATE employees 
            SET name = :name, 
                email = :email,
                gender = :gender, 
                position = :position, 
                department_id = :department_id, 
                salary = :salary,
                bank_name = :bank_name,
                bank_branch = :bank_branch,
                bank_account_name = :bank_account_name,
                branch_code = :branch_code,
                hours_per_week = :hours_per_week,
                status = :status,
                hourly_rate = :hourly_rate,
                hours_per_weekday = :hours_per_weekday,
                hours_per_weekend = :hours_per_weekend,
                remarks = :remarks 
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    // Execute with bound parameters
    $stmt->execute([
        'name' => $name,
        'email' => $email,
        'gender' => $gender,
        'position' => $position,
        'department_id' => $department_id,
        'salary' => $salary,
        'bank_name' => $bank_name,
        'bank_branch' => $bank_branch,
        'bank_account_name' => $bank_account_name,
        'branch_code' => $branch_code,
        'hours_per_week' => $hours_per_week,
        'status' => $status,
        'hourly_rate' => $hourly_rate,
        'hours_per_weekday' => $hours_per_weekday,
        'hours_per_weekend' => $hours_per_weekend,
        'remarks' => $remarks,
        'id' => $id
    ]);

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Update failed: ' . $e->getMessage()
    ]);
}