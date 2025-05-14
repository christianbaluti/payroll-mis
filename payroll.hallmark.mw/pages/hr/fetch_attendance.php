<?php
// fetch_attendance.php

require '../../db2.php'; // include your DB connection

$employeeId = $_GET['employee_id'];
$year = $_GET['year'];
$month = $_GET['month'];

// Validate inputs here if you want

$stmt = $conn->prepare("
  SELECT date_made, time_in, time_out
  FROM attendance
  WHERE employee_id = ? 
    AND YEAR(date_made) = ? 
    AND MONTH(date_made) = ?
");
$stmt->bind_param("iii", $employeeId, $year, $month);
$stmt->execute();
$result = $stmt->get_result();

$attendances = [];
while ($row = $result->fetch_assoc()) {
  $attendances[] = $row;
}

echo json_encode($attendances);
