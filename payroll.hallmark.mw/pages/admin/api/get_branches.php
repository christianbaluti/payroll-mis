<?php
header('Content-Type: application/json');
require_once '../../../db.php';

try {
    $stmt = $pdo->query("SELECT id, name, location FROM branch ORDER BY name ASC");
    $branches = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'branches' => $branches
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch branches: ' . $e->getMessage()
    ]);
}
?>
