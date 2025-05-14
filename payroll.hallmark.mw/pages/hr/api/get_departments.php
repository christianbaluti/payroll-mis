<?php
header('Content-Type: application/json');
require_once '../../../db.php';

$branchId = $_GET['branch_id'] ?? null;

if (!$branchId) {
    echo json_encode([
        'success' => false,
        'message' => 'Branch ID not provided'
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, name FROM department WHERE branch_id = ? ORDER BY name ASC");
    $stmt->execute([$branchId]);
    $departments = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'departments' => $departments
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch departments: ' . $e->getMessage()
    ]);
}
?>
