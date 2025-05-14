<?php
$host = 'localhost';
$db   = 'hallm3pc_payroll';
$user = 'hallm3pc_payroll';
$pass = 'Payroll@hallmark';
$charset = 'utf8mb4';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    echo 'error tu chief';
}
?>
