<?php
header('Content-Type: application/json');
require '../../db.php';

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $location = sanitize($_POST['location']);

    // Validate
    if (
        empty($name) || empty($location)
    ) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO branch 
            (name, location) 
            VALUES (:name, :location)");

        $stmt->execute([
            ':name' => $name,
            ':location' => $location
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Branch added successfully.']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add Branch.']);
    }
}
