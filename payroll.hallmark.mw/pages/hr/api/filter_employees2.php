<?php
header('Content-Type: application/json');
require_once '../../../db.php'; // Ensure $pdo is initialized correctly

// Get JSON POST data
$data = json_decode(file_get_contents("php://input"), true);

// Extract filters from POST
$branch_id = $data['branch_id'] ?? null;
$department_id = $data['department_id'] ?? null;
$gender = $data['gender'] ?? null;
$min_salary = isset($data['min_salary']) ? floatval($data['min_salary']) : null;
$max_salary = isset($data['max_salary']) ? floatval($data['max_salary']) : null;

try {
    // Base SQL query
    $sql = "
        SELECT 
            e.id, 
            e.name, 
            e.gender,
            e.position,
            e.email,
            e.bank_name,
            e.bank_branch,
            e.bank_account_name,
            e.branch_code,
            e.hours_per_week,
            e.salary,
            e.hours_per_weekday,
            e.hours_per_weekend,
            e.hourly_rate,
            d.name AS department, 
            b.name AS branch
        FROM employees e
        LEFT JOIN department d ON e.department_id = d.id
        LEFT JOIN branch b ON d.branch_id = b.id
        WHERE 1=1
    ";

    // Parameters array
    $params = [];

    // Apply filters if present
    if (!empty($branch_id)) {
        $sql .= " AND b.id = :branch_id";
        $params[':branch_id'] = $branch_id;
    }

    if (!empty($department_id)) {
        $sql .= " AND d.id = :department_id";
        $params[':department_id'] = $department_id;
    }

    if (!empty($gender)) {
        $sql .= " AND e.gender = :gender";
        $params[':gender'] = $gender;
    }

    if (is_numeric($min_salary)) {
        $sql .= " AND (e.salary >= :min_salary)";
        $params[':min_salary'] = $min_salary;
    }

    if (is_numeric($max_salary)) {
        $sql .= " AND (e.salary <= :max_salary)";
        $params[':max_salary'] = $max_salary;
    }

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'employees' => $employees
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch employees: ' . $e->getMessage()
    ]);
}
?>