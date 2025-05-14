<?php
require_once '../../db.php';

function getFilteredEmployees($filters = []) {
    global $pdo;
    
    $sql = "SELECT 
                e.id,
                e.name,
                e.email
            FROM employees e
            WHERE 1=1";
            
    $params = [];
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
    }
}



// GET: load filters + all employees
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $employees = getFilteredEmployees(); // No filters = all employees

    header('Content-Type: application/json');
    echo json_encode([
        'employees' => $employees
    ]);
    exit;
}
