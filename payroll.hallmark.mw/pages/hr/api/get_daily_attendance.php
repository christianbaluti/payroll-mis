<?php
session_start();
require '../../../db2.php';

try {
    $currentDate = date('Y-m-d');
    
    $sql = "
        SELECT 
            DATE_FORMAT(a.date_made, '%Y-%m-%d') as date,
            COUNT(DISTINCT CASE WHEN a.time_in IS NOT NULL THEN a.employee_id END) as present,
            COUNT(DISTINCT CASE WHEN a.time_in IS NULL THEN a.employee_id END) as absent
        FROM attendance a
        WHERE DATE(a.date_made) = CURRENT_DATE
        GROUP BY DATE_FORMAT(a.date_made, '%Y-%m-%d')
        ORDER BY a.date_made ASC
    ";
    
    $result = mysqli_query($conn, $sql);
    
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            'date' => $row['date'],
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