<?php
require_once '../../db.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="filtered_employees.csv"');

// Capture filters from URL
$filters = [
    'name' => $_GET['name'] ?? '',
    'gender' => $_GET['gender'] ?? '',
    'department' => $_GET['department'] ?? '',
    'branch' => $_GET['branch'] ?? ''
];

// Function to fetch employees based on filters
function getFilteredEmployees($filters = []) {
    global $pdo;

    $sql = "SELECT 
                e.name,
                e.gender,
                e.date_of_birth,
                e.email,
                e.position,
                d.name AS department,
                b.name AS branch,
                e.date_of_joining,
                e.salary,
                e.bank_name,
                e.bank_branch,
                e.bank_account_name,
                e.branch_code,
                e.hours_per_week,
                e.status,
                e.hourly_rate,
                e.hours_per_weekday,
                e.hours_per_weekend,
                e.remarks
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

        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Add employment_period for each employee
        foreach ($employees as &$employee) {
            if (!empty($employee['date_of_joining'])) {
                $dateOfJoining = new DateTime($employee['date_of_joining']);
                $currentDate = new DateTime();
                $interval = $currentDate->diff($dateOfJoining);

                $years = $interval->y;
                $months = $interval->m;
                $days = $interval->d;

                $period = '';
                if ($years > 0) {
                    $period .= $years . ' year' . ($years > 1 ? 's' : '');
                }
                if ($months > 0) {
                    if ($period != '') $period .= ', ';
                    $period .= $months . ' month' . ($months > 1 ? 's' : '');
                }
                if ($years == 0 && $months == 0) {
                    $period = $days . ' day' . ($days > 1 ? 's' : '');
                }

                $employee['employment_period'] = $period;
            } else {
                $employee['employment_period'] = 'N/A';
            }
        }

        return $employees;
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
    }
}

// Fetch filtered employees
$employees = getFilteredEmployees($filters);

// Output CSV
$output = fopen("php://output", "w");

// Write CSV headers
fputcsv($output, [
    'Name', 
    'Gender', 
    'Date of Birth', 
    'Email', 
    'Position', 
    'Department', 
    'Branch', 
    'Date of Joining', 
    'Salary', 
    'Bank Name', 
    'Bank Branch', 
    'Bank Account Name', 
    'Bank Account Number', 
    'Hours Per Week', 
    'Hourly Rate',
    'Hours Per Weekday',
    'Hours Per Weekend',
    'Employment Period',
    'Status', 
    'Remarks'
]);

// Write data rows
foreach ($employees as $emp) {
    fputcsv($output, [
        $emp['name'] ?? '',
        $emp['gender'] ?? '',
        $emp['date_of_birth'] ?? '',
        $emp['email'] ?? '',
        $emp['position'] ?? '',
        $emp['department'] ?? '',
        $emp['branch'] ?? '',
        $emp['date_of_joining'] ?? '',
        $emp['salary'] ?? '',
        $emp['bank_name'] ?? '',
        $emp['bank_branch'] ?? '',
        $emp['bank_account_name'] ?? '',
        $emp['branch_code'] ?? '',
        $emp['hours_per_week'] ?? '',
        $emp['hourly_rate'] ?? '',
        $emp['hours_per_weekday'] ?? '',
        $emp['hours_per_weekend'] ?? '',
        $emp['employment_period'] ?? '',
        $emp['status'] ?? '',
        $emp['remarks'] ?? ''
    ]);
}

fclose($output);
exit;
?>
