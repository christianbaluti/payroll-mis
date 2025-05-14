<?php
require '../../db.php';
header('Content-Type: application/json');

// Departments
$departments = $pdo->query("SELECT name FROM departments ORDER BY name ASC")->fetchAll();

// Branches
$branches = $pdo->query("SELECT name FROM branches ORDER BY name ASC")->fetchAll();

echo json_encode([
    "departments" => $departments,
    "branches" => $branches
]);
