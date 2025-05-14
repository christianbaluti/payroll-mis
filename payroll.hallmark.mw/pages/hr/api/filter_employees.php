<?php
header('Content-Type: application/json');
require_once '../../../db.php'; // Ensure $pdo is initialized properly here

// Get JSON POST data
$data = json_decode(file_get_contents("php://input"), true);

// Extract filters from POST
$branch_id = $data['branch_id'] ?? null;
$department_id = $data['department_id'] ?? null;
$gender = $data['gender'] ?? null;
$min_salary = isset($data['min_salary']) ? floatval($data['min_salary']) : null;
$max_salary = isset($data['max_salary']) ? floatval($data['max_salary']) : null;
$payroll_month = $data['payroll_month'] ?? null;

try {
    // Base SQL with optional payroll join
    $sql = "
        SELECT 
            e.id, 
            e.name, 
            e.gender, 
            COALESCE(p.basic_salary, e.salary) AS salary,
            p.pay_month AS payroll_month,
            d.name AS department, 
            b.name AS branch
        FROM employees e
        LEFT JOIN department d ON e.department_id = d.id
        LEFT JOIN branch b ON d.branch_id = b.id
        LEFT JOIN payroll p ON e.id = p.employee_id
            AND (:payroll_month IS NULL OR DATE_FORMAT(p.pay_month, '%Y-%m') = :payroll_month)
        WHERE 1=1
    ";

    // Dynamic parameters array
    $params = [
        ':payroll_month' => $payroll_month
    ];

    // Apply filters dynamically
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
        $sql .= " AND (p.basic_salary >= :min_salary OR (p.basic_salary IS NULL AND e.salary >= :min_salary))";
        $params[':min_salary'] = $min_salary;
    }

    if (is_numeric($max_salary)) {
        $sql .= " AND (p.basic_salary <= :max_salary OR (p.basic_salary IS NULL AND e.salary <= :max_salary))";
        $params[':max_salary'] = $max_salary;
    }

    // Execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Success response
    echo json_encode([
        'success' => true,
        'employees' => $employees
    ]);

} catch (Exception $e) {
    // Error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch employees: ' . $e->getMessage()
    ]);
}
?>
