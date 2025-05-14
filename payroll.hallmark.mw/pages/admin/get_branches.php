<?php
header('Content-Type: application/json');
require '../../db.php';

try {
    $stmt = $pdo->query("SELECT id, name, location FROM branch ORDER BY name ASC");
    $branches = $stmt->fetchAll();
    echo json_encode($branches);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch branches']);
}
