<?php
header('Content-Type: application/json');

// Include the DB connection file
require_once '../../db.php';

// Validate ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Branch ID is required.']);
    exit;
}

$branchId = $_GET['id'];

try {
    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT id, name, location FROM branch WHERE id = :id");
    $stmt->execute(['id' => $branchId]);
    $branch = $stmt->fetch();

    if ($branch) {
        echo json_encode(['status' => 'success', 'data' => $branch]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Branch not found.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
