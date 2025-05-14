<?php
session_start();
require '../../db2.php';

$current_date = date('Y-m-d');
date_default_timezone_set('Etc/GMT-2'); // GMT+2
$current_time = date('H:i:s');


// Check if HR is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hr') {
    header("Location: ../../index.html");
    exit;
}

$email = $_SESSION['email'];
$role = $_SESSION['role'];
$phone = $_SESSION['phone'];

// Fetch all employees with department name and attendance data for today
$stmt = $conn->prepare("
    SELECT 
        e.id, 
        e.name, 
        e.position, 
        d.name AS department,
        a.time_in AS time_in,
        a.time_out AS time_out
    FROM employees e
    LEFT JOIN department d ON e.department_id = d.id
    LEFT JOIN attendance a ON a.employee_id = e.id AND DATE(a.date_made) = ?
    ORDER BY e.id
");
$stmt->bind_param("s", $current_date);
$stmt->execute();
$employees = $stmt->get_result();

$stmt = $conn->prepare("SELECT COUNT(DISTINCT employee_id) as count FROM attendance WHERE DATE(date_made) = ? AND time_in IS NOT NULL");
$stmt->bind_param("s", $current_date);  // Bind the date as a string
$stmt->execute();
$result = $stmt->get_result();
$totalEmployees = $result->fetch_assoc()['count'];
?>
