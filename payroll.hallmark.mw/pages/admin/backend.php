<?php
session_start();
require '../../db2.php';

// Check if HR is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.html");
    exit;
}

$email = $_SESSION['email'];
$role = $_SESSION['role'];
$phone = $_SESSION['phone'];
// Sample stats (replace with actual queries)
$totalEmployees = $conn->query("SELECT COUNT(*) as count FROM employees")->fetch_assoc()['count'];
$totalDepartments = $conn->query("SELECT COUNT(*) as count FROM department")->fetch_assoc()['count'];
$totalBranches = $conn->query("SELECT COUNT(*) as count FROM branch")->fetch_assoc()['count'];
$totalEmployees = $conn->query("SELECT COUNT(*) as count FROM employees")->fetch_assoc()['count'];
$totalUsers = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];

// Get number of admins
$adminCount = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'admin'")->fetch_assoc()['count'];

// Get number of HRs
$hrCount = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'hr'")->fetch_assoc()['count'];

// Get number of managers
$managerCount = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'manager'")->fetch_assoc()['count'];

?>