<?php
include '../../../db2.php';

header('Content-Type: application/json');

// Get unique_id from request
$unique_id = $_GET['unique_id'] ?? null;

if (!$unique_id) {
    echo json_encode(['success' => false, 'message' => 'Unique ID is required']);
    exit();
}

// Fetch payroll totals
$sql_payroll = $conn->prepare("SELECT * FROM payroll WHERE unique_id = ? LIMIT 1");
$sql_payroll->bind_param("s", $unique_id);
$sql_payroll->execute();
$result_payroll = $sql_payroll->get_result();
$payroll_data = $result_payroll->fetch_assoc();
$sql_payroll->close();

if (!$payroll_data) {
    echo json_encode(['success' => false, 'message' => 'Payroll not found']);
    $conn->close();
    exit();
}

// Fetch payslip data linked to this payroll
// Need to join payslip_payroll and payslip tables
$sql_payslips = $conn->prepare("
    SELECT p.*
    FROM payslip p
    JOIN payslip_payroll pp ON p.id = pp.payslip_id
    JOIN payroll pr ON pp.payroll_id = pr.id
    WHERE pr.unique_id = ?
");
$sql_payslips->bind_param("s", $unique_id);
$sql_payslips->execute();
$result_payslips = $sql_payslips->get_result();

$payslips_data = [];
if ($result_payslips->num_rows > 0) {
    while($row = $result_payslips->fetch_assoc()) {
        // Ensure numeric values are treated as numbers
        foreach ($row as $key => $value) {
            if (is_numeric($value)) {
                $row[$key] = (float) $value;
            }
        }
        $payslips_data[] = $row;
    }
}
$sql_payslips->close();

echo json_encode(['success' => true, 'payroll' => $payroll_data, 'payslips' => $payslips_data]);

$conn->close();

// Function to calculate PAYE (should match frontend logic)
function calculatePAYE($taxableIncome) {
    $totalTax = 0;
    $remaining = $taxableIncome;

    if ($remaining <= 100000) {
        return 0;
    }
    $remaining -= 100000;

    if ($remaining >= 400000) {
        $totalTax += 400000 * 0.25;
        $remaining -= 400000;
    } else {
        $totalTax += $remaining * 0.25;
        return $totalTax;
    }

    if ($remaining >= 2500000) {
        $totalTax += 2500000 * 0.30;
        $remaining -= 2500000;
    } else {
        $totalTax += $remaining * 0.30;
        return $totalTax;
    }

    $totalTax += $remaining * 0.35;
    return $totalTax;
}

// Function to calculate Net Salary (should match frontend logic)
function calculateNetSalary($payslip) {
    $grossSalary = (float)($payslip['gross_salary'] ?? 0);
    $additions = (float)($payslip['additions'] ?? 0);
    $deductions = (float)($payslip['deductions'] ?? 0);
    $pension = (float)($payslip['pension'] ?? 0);
    $welfareFund = (float)($payslip['welfare_fund'] ?? 0);
    $weekendOTPay = (float)($payslip['weekend_overtime_pay'] ?? 0);
    $weekdayOTPay = (float)($payslip['weekday_overtime_pay'] ?? 0);
    $paye = (float)($payslip['paye'] ?? 0); // Use the calculated PAYE

    $netSalary = $grossSalary - $paye - $pension - $welfareFund + $additions - $deductions + $weekendOTPay + $weekdayOTPay;
    return $netSalary;
}
?>
