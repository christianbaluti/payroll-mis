<?php
session_start();
require '../../../db2.php';

try {
    $currentMonth = date('Y-m');
    
    $sql = "
        SELECT 
            DATE_FORMAT(a.date_made, '%Y-%m') as month,
            COUNT(DISTINCT CASE WHEN a.time_in IS NOT NULL THEN a.employee_id END) as present,
            COUNT(DISTINCT CASE WHEN a.time_in IS NULL THEN a.employee_id END) as absent
        FROM attendance a
        WHERE YEAR(a.date_made) = YEAR(CURRENT_DATE)
        AND MONTH(a.date_made) = MONTH(CURRENT_DATE)
        GROUP BY DATE_FORMAT(a.date_made, '%Y-%m')
        ORDER BY a.date_made ASC
    ";
    
    $result = mysqli_query($conn, $sql);
    
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            'month' => $row['month'],
            'present' => $row['present'] ?? 0,
            'absent' => $row['absent'] ?? 0
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