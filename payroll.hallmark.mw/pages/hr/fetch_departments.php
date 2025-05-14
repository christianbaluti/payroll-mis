<?php
require_once '../../db.php'; // This should define $pdo

// Set response content type
header('Content-Type: application/json');

// Get raw JSON input
$rawData = file_get_contents('php://input');
$filters = json_decode($rawData, true);

// Determine request method
$method = $_SERVER['REQUEST_METHOD'];

// Validate JSON input on POST
if ($method === 'POST' && !is_array($filters)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON input.']);
    exit;
}

// Sanitize and validate filters
$filters = validateFilters($filters ?? []);

// Handle GET request (initial load)
if ($method === 'GET') {
    http_response_code(200);
    echo json_encode([
        'departments' => getFilteredDepartments([]),
        'branches' => getBranches()
    ]);
    exit;
}

// Handle POST request (filtered load)
if ($method === 'POST') {
    http_response_code(200);
    echo json_encode([
        'departments' => getFilteredDepartments($filters)
    ]);
    exit;
}

// === FUNCTIONS ===

function getBranches() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT id, name, location FROM branch ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch branches.']);
        exit;
    }
}

function validateFilters($filters) {
    $allowedKeys = ['department_name', 'head_of_department', 'branch_id'];
    $cleaned = array_intersect_key($filters, array_flip($allowedKeys));
    return array_map('trim', $cleaned);
}

function getFilteredDepartments($filters) {
    global $pdo;

    $sql = "SELECT 
                d.id,
                d.name AS department_name,
                d.head_of_department,
                b.name AS branch_name,
                b.location AS branch_location,
                COUNT(e.id) AS employee_count
            FROM department d
            LEFT JOIN branch b ON d.branch_id = b.id
            LEFT JOIN employees e ON d.id = e.department_id
            WHERE 1=1";

    $params = [];

    if (!empty($filters['department_name'])) {
        $sql .= " AND d.name LIKE :department_name";
        $params[':department_name'] = '%' . $filters['department_name'] . '%';
    }

    if (!empty($filters['head_of_department'])) {
        $sql .= " AND d.head_of_department LIKE :head_of_department";
        $params[':head_of_department'] = '%' . $filters['head_of_department'] . '%';
    }

    if (!empty($filters['branch_id'])) {
        $sql .= " AND b.id = :branch_id";
        $params[':branch_id'] = $filters['branch_id'];
    }

    $sql .= " GROUP BY d.id, d.name, d.head_of_department, b.name, b.location
              ORDER BY d.name ASC";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch departments.']);
        exit;
    }
}
?>
