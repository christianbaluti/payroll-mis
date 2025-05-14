<?php
session_start();
require '../../../db2.php';

try {
    // Authentication
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hr') {
        header("HTTP/1.1 401 Unauthorized");
        echo json_encode(["error" => "Unauthorized"]);
        exit;
    }

    // Initialize arrays with default values
    $employeeByGender = ['male' => 0, 'female' => 0];
    $salaryRanges = [
        '0-100K' => 0,
        '100K-300K' => 0,
        '300K-500K' => 0,
        '500K+' => 0
    ];

    // Query database safely
    $result = mysqli_query($conn, "SELECT gender, COUNT(*) as count FROM employees GROUP BY gender");
    if (!$result) {
        throw new Exception(mysqli_error($conn));
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $gender = strtolower($row['gender']);
        if (isset($employeeByGender[$gender])) {
            $employeeByGender[$gender] = (int)$row['count'];
        }
    }

    // Salary ranges
    $salaryResult = mysqli_query($conn, "SELECT salary FROM employees");
    if (!$salaryResult) {
        throw new Exception(mysqli_error($conn));
    }

    while ($salaryRow = mysqli_fetch_assoc($salaryResult)) {
        $sal = (float)$salaryRow['salary'];
        if ($sal <= 100000) {
            $salaryRanges['0-100K']++;
        } elseif ($sal <= 300000) {
            $salaryRanges['100K-300K']++;
        } elseif ($sal <= 500000) {
            $salaryRanges['300K-500K']++;
        } else {
            $salaryRanges['500K+']++;
        }
    }

    // Prepare response
    $response = [
        'totalEmployees' => count($employeeByGender),
        'totalDepartments' => 0,
        'totalBranches' => 0,
        'totalUsers' => 0,
        'employeeByGender' => $employeeByGender,
        'salaryRanges' => $salaryRanges
    ];

    header('Content-Type: application/json');
    echo json_encode($response);

} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["error" => $e->getMessage()]);
}
?>