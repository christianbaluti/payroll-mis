<?php
include '../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'];

  $stmt = $conn->prepare("DELETE FROM branch WHERE id=?");
  $stmt->bind_param("i", $id);

  if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Branch deleted successfully.']);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete branch.']);
  }
}
