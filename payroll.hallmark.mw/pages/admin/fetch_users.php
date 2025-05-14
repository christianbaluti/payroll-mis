<?php
require_once '../../db2.php';

header('Content-Type: application/json');

$query = "SELECT email, role, phone FROM users ORDER BY id DESC";
$result = mysqli_query($conn, $query);

$users = [];

while ($row = mysqli_fetch_assoc($result)) {
    $users[] = [
        'email' => $row['email'],
        'role' => $row['role'],
        'phone' => $row['phone'] ?? ''
    ];
}

echo json_encode([
    'employees' => $users,
]);
