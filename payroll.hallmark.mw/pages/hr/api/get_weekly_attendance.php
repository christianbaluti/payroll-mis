<?php
session_start();
require '../../../db2.php';

try {
    $currentWeek = date('Y-W');
    
    $sql = "
        SELECT 
            CONCAT(YEARWEEK(a.date_made, 1), '- Week ', WEEK(a.date_made, 1)) as week_period,
            COUNT(DISTINCT CASE WHEN a.time_in IS NOT NULL THEN a.employee_id END) as present,
            COUNT(DISTINCT CASE WHEN a.time_in IS NULL THEN a.employee_id END) as absent
        FROM attendance a
        WHERE YEARWEEK(a.date_made, 1) = YEARWEEK(CURRENT_DATE, 1)
        GROUP BY YEARWEEK(a.date_made, 1)
        ORDER BY a.date_made ASC
    ";
    
    $result = mysqli_query($conn, $sql);
    
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            'week' => $row['week_period'],
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