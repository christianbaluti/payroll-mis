<?php
header('Content-Type: application/json');
require '../../db.php';

try {
    $stmt = $pdo->query("SELECT id, name FROM department ORDER BY name ASC");
    $departments = $stmt->fetchAll();
    echo json_encode($departments);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch departments']);
}
