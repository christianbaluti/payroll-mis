<?php
header('Content-Type: application/json');

// Include the DB connection file
require_once '../../db.php';

// Validate ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Department ID is required.']);
    exit;
}

$departmentId = $_GET['id'];

try {
    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT * FROM department WHERE id = :id");
    $stmt->execute(['id' => $departmentId]);
    $department = $stmt->fetch();

    if ($department) {
        echo json_encode(['status' => 'success', 'data' => $department]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Department not found.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
