<?php
require_once '../../db.php';

function getFilteredEmployees($filters = []) {
    global $pdo;
    
    $sql = "SELECT 
                e.id,
                e.name,
                e.gender,
                e.email,
                e.salary,
                e.position,
                e.date_of_joining,
                e.bank_name,
                e.bank_branch,
                e.bank_account_name,
                e.branch_code,
                e.hours_per_week,
                e.status,
                e.remarks,
                e.hourly_rate,
                e.hours_per_weekday,
                e.hours_per_weekend,
                e.date_of_birth,
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

function getDepartments() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT id, name FROM department");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
    }
}

function getBranches() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT id, name FROM branch");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
    }
}

// POST: handle filters
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filters = $_POST;
    $employees = getFilteredEmployees($filters);
    echo json_encode([
        'success' => true,
        'data' => $employees
    ]);
    exit;
}

// GET: load filters + all employees
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $departments = getDepartments();
    $branches = getBranches();
    $employees = getFilteredEmployees(); // No filters = all employees

    header('Content-Type: application/json');
    echo json_encode([
        'departments' => $departments,
        'branches' => $branches,
        'employees' => $employees
    ]);
    exit;
}
