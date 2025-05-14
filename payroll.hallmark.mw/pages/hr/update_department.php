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
$requiredFields = ['id', 'name', 'head_of_department', 'branch_id'];
foreach ($requiredFields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        echo json_encode(['status' => 'error', 'message' => "Missing or empty field: $field"]);
        exit;
    }
}

// Assign POST values
$id = $_POST['id'];
$name = $_POST['name'];
$head_of_department = $_POST['head_of_department'];
$branch_id = $_POST['branch_id'];

try {
    // Prepare the update query
    $sql = "UPDATE department 
            SET name = :name, 
                head_of_department = :head_of_department,
                branch_id = :branch_id
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    // Execute with bound parameters
    $stmt->execute([
        'name' => $name,
        'head_of_department' => $head_of_department,
        'branch_id' => $branch_id,
        'id' => $id
    ]);

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Update failed: ' . $e->getMessage()
    ]);
}
?>