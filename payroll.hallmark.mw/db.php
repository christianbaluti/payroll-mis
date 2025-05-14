<?php
$host = 'localhost';
$db   = 'hallm3pc_payroll';
$user = 'hallm3pc_payroll';
$pass = 'Payroll@hallmark';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    //echo 'working';
} catch (\PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}
?>
