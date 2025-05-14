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
$requiredFields = ['id', 'name', 'location'];
foreach ($requiredFields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        echo json_encode(['status' => 'error', 'message' => "Missing or empty field: $field"]);
        exit;
    }
}

// Assign POST values
$id = $_POST['id'];
$name = $_POST['name'];
$location = $_POST['location'];

try {
    // Prepare the update query
    $sql = "UPDATE branch 
            SET name = :name, 
                location = :location
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    // Execute with bound parameters
    $stmt->execute([
        'name' => $name,
        'location' => $location,
        'id' => $id
    ]);

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Update failed: ' . $e->getMessage()
    ]);
}
