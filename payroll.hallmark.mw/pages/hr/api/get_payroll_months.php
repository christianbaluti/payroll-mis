<?php
include '../../../db2.php'; // Include your database connection file

header('Content-Type: application/json');

// Fetch distinct months and years from the payroll table
$sql = "SELECT DISTINCT month, year FROM payroll ORDER BY year DESC, month DESC";
$result = $conn->query($sql);

$months = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $months[] = $row;
    }
    echo json_encode(['success' => true, 'months' => $months]);
} else {
    echo json_encode(['success' => true, 'months' => []]); // Return empty array if no data
}

$conn->close();
?>
