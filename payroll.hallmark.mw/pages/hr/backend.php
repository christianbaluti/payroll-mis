<?php
session_start();
require './../../db2.php';

// Check if HR is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hr') {
    header("Location: ../../index.html");
    exit;
}

$email = $_SESSION['email'];
$role = $_SESSION['role'];
$phone = $_SESSION['phone'];

// --- Your Original Stats ---

$totalEmployees = $conn->query("SELECT COUNT(*) as count FROM employees")->fetch_assoc()['count'];
$totalDepartments = $conn->query("SELECT COUNT(*) as count FROM department")->fetch_assoc()['count'];
$totalBranches = $conn->query("SELECT COUNT(*) as count FROM branch")->fetch_assoc()['count'];
$totalUsers = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];

?>
