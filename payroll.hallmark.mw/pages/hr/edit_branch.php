<?php
require 'db.php'; // Include the PDO DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $branch_name = isset($_POST['branch_name']) ? trim($_POST['branch_name']) : '';
    $branch_location = isset($_POST['branch_location']) ? trim($_POST['branch_location']) : '';

    if ($id > 0 && $branch_name !== '' && $branch_location !== '') {
        try {
            $sql = "UPDATE branches SET name = :branch_name, location = :branch_location WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':branch_name' => $branch_name,
                ':branch_location' => $branch_location,
                ':id' => $id
            ]);

            // Redirect or output success message
            header("Location: branches.php?status=updated");
            exit;

        } catch (PDOException $e) {
            echo "Error updating branch: " . $e->getMessage();
        }
    } else {
        echo "Invalid input. Please fill all fields.";
    }
} else {
    echo "Invalid request method.";
}
?>
