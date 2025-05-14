<?php
require_once '../../db.php'; // Ensure this connects and provides $pdo

// Set JSON response type
header('Content-Type: application/json');

// Get input filters (if POST)
$rawData = file_get_contents('php://input');
$filters = json_decode($rawData, true);

// Request method
$method = $_SERVER['REQUEST_METHOD'];

// Validate input if POST
if ($method === 'POST' && !is_array($filters)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON input.']);
    exit;
}

// Clean and validate input filters
$filters = validateBranchFilters($filters ?? []);

// GET → Initial load (no filters)
if ($method === 'GET') {
    http_response_code(200);
    echo json_encode([
        'branches' => getFilteredBranches([]),
    ]);
    exit;
}

// POST → With filters
if ($method === 'POST') {
    http_response_code(200);
    echo json_encode([
        'branches' => getFilteredBranches($filters),
    ]);
    exit;
}

// === FUNCTIONS ===

function validateBranchFilters($filters) {
    $allowedKeys = ['branch_name', 'location'];
    $cleaned = array_intersect_key($filters, array_flip($allowedKeys));
    return array_map('trim', $cleaned);
}

function getFilteredBranches($filters) {
    global $pdo;

    $sql = "
        SELECT 
            b.id,
            b.name AS branch_name,
            b.location,
            COUNT(DISTINCT d.id) AS department_count,
            COUNT(e.id) AS employee_count
        FROM branch b
        LEFT JOIN department d ON b.id = d.branch_id
        LEFT JOIN employees e ON d.id = e.department_id
        WHERE 1=1
    ";

    $params = [];

    if (!empty($filters['branch_name'])) {
        $sql .= " AND b.name LIKE :branch_name";
        $params[':branch_name'] = '%' . $filters['branch_name'] . '%';
    }

    if (!empty($filters['location'])) {
        $sql .= " AND b.location LIKE :location";
        $params[':location'] = '%' . $filters['location'] . '%';
    }

    $sql .= "
        GROUP BY b.id, b.name, b.location
        ORDER BY b.name ASC
    ";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch branches.']);
        exit;
    }
}
