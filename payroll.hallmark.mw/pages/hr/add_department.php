<?php
header('Content-Type: application/json');
require '../../db.php';

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $head_of_department = sanitize($_POST['head_of_department']);
    $branch = sanitize($_POST['branch']);

    // Validate
    if (
        empty($name) || empty($head_of_department) || empty($branch)
    ) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO department 
            (name, head_of_department, branch_id) 
            VALUES (:name, :head_of_department, :branch_id)");

        $stmt->execute([
            ':name' => $name,
            ':head_of_department' => $head_of_department,
            ':branch_id' => $branch
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Department added successfully.']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add Department.']);
    }
}
