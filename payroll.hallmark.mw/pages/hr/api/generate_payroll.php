<?php
header('Content-Type: application/json');
require_once '../../db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['employees']) || !is_array($data['employees']) || !isset($data['month'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$employeeIds = $data['employees'];
$month = $data['month']; // Format: YYYY-MM

try {
    $pdo->beginTransaction();

    $inserted = 0;

    foreach ($employeeIds as $empId) {
        // Check if payroll already exists for this employee and month
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM payrolls WHERE employee_id = ? AND month = ?");
        $stmt->execute([$empId, $month]);
        $exists = $stmt->fetchColumn();

        if ($exists) {
            continue; // Skip if already generated
        }

        // Get employee details
        $stmt = $pdo->prepare("SELECT salary FROM employees WHERE id = ?");
        $stmt->execute([$empId]);
        $employee = $stmt->fetch();

        if (!$employee) {
            continue; // Skip if employee not found
        }

        $basicSalary = $employee['salary'];
        $deductions = 0.00; // Set default or calculate as needed
        $bonuses = 0.00;    // Set default or calculate as needed
        $netSalary = ($basicSalary + $bonuses) - $deductions;

        // Insert payroll record
        $stmt = $pdo->prepare("INSERT INTO payrolls (employee_id, month, salary, deductions, bonuses, net_salary) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$empId, $month, $basicSalary, $deductions, $bonuses, $netSalary]);
        $inserted++;
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => "$inserted payroll(s) generated."
    ]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode([
        'success' => false,
        'message' => 'Error generating payroll: ' . $e->getMessage()
    ]);
}
?>
