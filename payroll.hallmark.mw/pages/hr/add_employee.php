<?php
header('Content-Type: application/json');
require '../../db.php';

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $position = sanitize($_POST['position']);
    $department_id = intval($_POST['department_id']);
    $date_of_joining = sanitize($_POST['date_of_joining']);
    $date_of_birth = sanitize($_POST['date_of_birth']);
    $gender = sanitize($_POST['gender']);
    $salary = floatval($_POST['salary']);
    $bank_name = sanitize($_POST['bank_name']);
    $bank_branch = sanitize($_POST['bank_branch']);
    $bank_account_name = sanitize($_POST['bank_account_name']);
    $branch_code = sanitize($_POST['branch_code']);
    $hours_per_week = intval($_POST['hours_per_week']);
    $status = sanitize($_POST['status']);
    $remarks = sanitize($_POST['remarks']);
    $hours_per_weekend = sanitize($_POST['hours_per_weekend']);
    $hours_per_weekday = sanitize($_POST['hours_per_weekday']);
    $hourly_rate = sanitize($_POST['hourly_rate']);

    // Validate required fields
    if (
        empty($name) || empty($email) || empty($position) || empty($department_id) ||
        empty($date_of_joining) || empty($date_of_birth) || empty($gender) || 
        empty($salary) || empty($status) || empty($hours_per_weekend) || 
        empty($hours_per_weekday) || empty($hourly_rate)
    ) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill all required fields.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO employees 
            (name, email, position, department_id, date_of_joining, date_of_birth, gender, salary,
             bank_name, bank_branch, bank_account_name, branch_code, hours_per_week, status, remarks, hours_per_weekend, hours_per_weekday, hourly_rate) 
            VALUES 
            (:name, :email, :position, :department_id, :date_of_joining, :date_of_birth, :gender, :salary,
             :bank_name, :bank_branch, :bank_account_name, :branch_code, :hours_per_week, :status, :remarks,
              :hours_per_weekend, :hours_per_weekday, :hourly_rate  )");

        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':position' => $position,
            ':department_id' => $department_id,
            ':date_of_joining' => $date_of_joining,
            ':date_of_birth' => $date_of_birth,
            ':gender' => $gender,
            ':salary' => $salary,
            ':bank_name' => $bank_name,
            ':bank_branch' => $bank_branch,
            ':bank_account_name' => $bank_account_name,
            ':branch_code' => $branch_code,
            ':hours_per_week' => $hours_per_week,
            ':status' => $status,
            ':remarks' => $remarks,
            ':hours_per_weekend' => $hours_per_weekend,
            ':hours_per_weekday' => $hours_per_weekday,
            ':hourly_rate' => $hourly_rate
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Employee added successfully. Reloading...']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add employee.']);
    }
}
?>
