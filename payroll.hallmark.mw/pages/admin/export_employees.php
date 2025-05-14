<?php
require_once '../../db.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="filtered_employees.csv"');

$filters = [
    'name' => $_GET['name'] ?? '',
    'gender' => $_GET['gender'] ?? '',
    'department' => $_GET['department'] ?? '',
    'branch' => $_GET['branch'] ?? ''
];

function getFilteredEmployees($filters = []) {
    global $pdo;

    $sql = "SELECT 
                e.name,
                e.gender,
                e.email,
                e.salary,
                e.position,
                d.name as department,
                b.name as branch
            FROM employees e
            LEFT JOIN department d ON e.department_id = d.id
            LEFT JOIN branch b ON d.branch_id = b.id
            WHERE 1=1";

    $params = [];

    if (!empty($filters['name'])) {
        $sql .= " AND e.name LIKE :name";
        $params[':name'] = '%' . $filters['name'] . '%';
    }
    if (!empty($filters['gender'])) {
        $sql .= " AND e.gender = :gender";
        $params[':gender'] = $filters['gender'];
    }
    if (!empty($filters['department'])) {
        $sql .= " AND d.id = :department";
        $params[':department'] = $filters['department'];
    }
    if (!empty($filters['branch'])) {
        $sql .= " AND b.id = :branch";
        $params[':branch'] = $filters['branch'];
    }

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Export error: " . $e->getMessage());
        return [];
    }
}

$employees = getFilteredEmployees($filters);

// Output CSV headers
$output = fopen("php://output", "w");
fputcsv($output, ['Name', 'Gender', 'Email', 'Position', 'Department', 'Branch', 'Salary']);

foreach ($employees as $emp) {
    fputcsv($output, [
        $emp['name'],
        $emp['gender'],
        $emp['email'],
        $emp['position'],
        $emp['department'],
        $emp['branch'],
        $emp['salary']
    ]);
}

fclose($output);
exit;
