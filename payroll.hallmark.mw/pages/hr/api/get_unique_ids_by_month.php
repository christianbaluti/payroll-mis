<?php
include '../../../db2.php'; // Include your database connection file

header('Content-Type: application/json');

// Get month and year from request
$month = $_GET['month'] ?? null;
$year = $_GET['year'] ?? null;

if (!$month || !$year) {
    echo json_encode(['success' => false, 'message' => 'Month and year are required']);
    exit();
}

// Fetch unique IDs for the selected month and year
$sql = $conn->prepare("SELECT unique_id FROM payroll WHERE month = ? AND year = ? ORDER BY datemade DESC");
$sql->bind_param("si", $month, $year); // Assuming month is text/string and year is integer
$sql->execute();
$result = $sql->get_result();

$unique_ids = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $unique_ids[] = $row;
    }
    echo json_encode(['success' => true, 'unique_ids' => $unique_ids]);
} else {
    echo json_encode(['success' => true, 'unique_ids' => []]); // Return empty array if no data
}

$sql->close();
$conn->close();
?>
