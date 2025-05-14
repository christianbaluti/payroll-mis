<?php
include "../../db.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
  exit();
}


$id = $data['id'];
$name = $data['name'];
$location = $data['location'];

if (empty($id) || empty($name) || empty($location)) {
  echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
  exit();
}


$stmt = $conn->prepare("UPDATE branch SET name = ?, location = ? WHERE id = ?");
$stmt->bind_param("ssi", $name, $location, $id);

if ($stmt->execute()) {
  echo json_encode(['status' => 'success', 'message' => 'Branch updated successfully']);
} else {
  echo json_encode(['status' => 'error', 'message' => 'Failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>