<?php
session_start();
require '../../../db2.php';

try {
    $sql = "
        SELECT 
            d.name AS department_name,
            COUNT(e.id) AS employee_count
        FROM department d
        LEFT JOIN employees e ON d.id = e.department_id
        GROUP BY d.id, d.name
        ORDER BY employee_count DESC
    ";
    
    $result = mysqli_query($conn, $sql);
    
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            'department' => $row['department_name'],
            'count' => $row['employee_count']
        ];
    }
    
    echo json_encode([
        'status' => true,
        'message' => 'Data retrieved successfully',
        'data' => $data
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => false,
        'message' => 'Error fetching data: ' . $e->getMessage(),
        'data' => []
    ]);
}

mysqli_close($conn);