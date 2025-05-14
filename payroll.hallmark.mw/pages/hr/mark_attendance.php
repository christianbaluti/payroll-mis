<?php
require '../../db2.php';
$current_date = date('Y-m-d'); // e.g., 2025-04-30
$current_time = date('H:i:s'); // e.g., 14:23:45

$data = json_decode(file_get_contents("php://input"), true);
$employee_id = intval($data['id']); // Retrieve correct employee ID from input
$type = $data['type']; // Retrieve the type (in/out) from input
$response = ['status' => 'error', 'message' => 'Invalid operation'];

if ($type === 'in') {
    // Insert time-in data
    $stmt = $conn->prepare("INSERT INTO attendance (date_made, time_in, employee_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $current_date, $current_time, $employee_id);
    if ($stmt->execute()) {
        $response = ['status' => 'success', 'message' => 'Time In recorded'];
    }
} elseif ($type === 'out') {
    // Insert time-out data (only if time_in exists)
    $stmt = $conn->prepare("UPDATE attendance SET time_out = ? WHERE employee_id = ? AND DATE(date_made) = ?");
    $stmt->bind_param("sis", $current_time, $employee_id, $current_date);
    if ($stmt->execute()) {
        $response = ['status' => 'success', 'message' => 'Time Out recorded'];
    }
}

echo json_encode($response);
?>
