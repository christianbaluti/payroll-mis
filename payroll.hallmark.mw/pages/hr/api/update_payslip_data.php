<?php
include './../../../db2.php';

header('Content-Type: application/json');

// Get data from the request body
$data = json_decode(file_get_contents('php://input'), true);

$payslip_id = $data['id'] ?? null;
$additions = $data['additions'] ?? null;
$deductions = $data['deductions'] ?? null;
$weekend_overtime_hours = $data['weekend_overtime_hours'] ?? null;
$weekday_overtime_hours = $data['weekday_overtime_hours'] ?? null;

if (!$payslip_id) {
    echo json_encode(['success' => false, 'message' => 'Payslip ID is required']);
    exit();
}

// Fetch current payslip data to recalculate
$sql_fetch = $conn->prepare("SELECT * FROM payslip WHERE id = ? LIMIT 1");
$sql_fetch->bind_param("i", $payslip_id);
$sql_fetch->execute();
$result_fetch = $sql_fetch->get_result();
$payslip = $result_fetch->fetch_assoc();
$sql_fetch->close();

if (!$payslip) {
    echo json_encode(['success' => false, 'message' => 'Payslip not found']);
    $conn->close();
    exit();
}

// Update payslip data with received values, using original if not provided
$payslip['additions'] = $additions ?? $payslip['additions'];
$payslip['deductions'] = $deductions ?? $payslip['deductions'];
$payslip['weekend_overtime_hours'] = $weekend_overtime_hours ?? $payslip['weekend_overtime_hours'];
$payslip['weekday_overtime_hours'] = $weekday_overtime_hours ?? $payslip['weekday_overtime_hours'];

// Recalculate dependent fields
$grossSalary = (float)($payslip['gross_salary'] ?? 0);
$hourlyRate = (float)($payslip['hourly_rate'] ?? 0);
$welfareFund = (float)($payslip['welfare_fund'] ?? 0);

$weekendHours = (float)($payslip['weekend_overtime_hours'] ?? 0);
$weekdayHours = (float)($payslip['weekday_overtime_hours'] ?? 0);
$weekendOTPay = $weekendHours * $hourlyRate * 2;
$weekdayOTPay = $weekdayHours * $hourlyRate * 1.5;
$totalOTPay = $weekendOTPay + $weekdayOTPay;
$totalOvertimeHours = $weekendHours + $weekdayHours;

$taxableIncome = $grossSalary + (float)($payslip['additions'] ?? 0) - (float)($payslip['deductions'] ?? 0);
$paye = calculatePAYE($taxableIncome);
$netSalaryBeforePension = $grossSalary - $paye;
$pension = $netSalaryBeforePension * 0.05; // Recalculate pension
$baseNet = $netSalaryBeforePension - $pension - $welfareFund;
$currentNet = $baseNet + $totalOTPay + (float)($payslip['additions'] ?? 0) - (float)($payslip['deductions'] ?? 0);


// Update the payslip record in the database
$sql_update_payslip = $conn->prepare("
    UPDATE payslip
    SET additions = ?, deductions = ?, weekend_overtime_hours = ?, weekday_overtime_hours = ?,
        weekend_overtime_pay = ?, weekday_overtime_pay = ?, total_overtime_hours = ?, total_overtime_pay = ?,
        paye = ?, pension = ?
    WHERE id = ?
");
$sql_update_payslip->bind_param("ddddddddddi",
    $payslip['additions'], $payslip['deductions'], $payslip['weekend_overtime_hours'], $payslip['weekday_overtime_hours'],
    $weekendOTPay, $weekdayOTPay, $totalOvertimeHours, $totalOTPay,
    $paye, $pension,
    $payslip_id
);

if ($sql_update_payslip->execute()) {
    // Payslip updated successfully, now update payroll totals

    // Get the payroll_id associated with this payslip
    $sql_get_payroll_id = $conn->prepare("SELECT payroll_id FROM payslip_payroll WHERE payslip_id = ? LIMIT 1");
    $sql_get_payroll_id->bind_param("i", $payslip_id);
    $sql_get_payroll_id->execute();
    $result_payroll_id = $sql_get_payroll_id->get_result();
    $payroll_link = $result_payroll_id->fetch_assoc();
    $sql_get_payroll_id->close();

    $payroll_id = $payroll_link['payroll_id'] ?? null;

    if ($payroll_id) {
        // Recalculate and update payroll totals
        $sql_update_payroll_totals = $conn->prepare("
            UPDATE payroll pr
            JOIN (
                SELECT pp.payroll_id,
                       SUM(p.gross_salary) as total_gross_pay,
                       SUM(p.paye) as total_paye,
                       SUM(p.pension) as total_pension,
                       SUM(p.additions) as total_addition,
                       SUM(p.deductions) as total_deduction,
                       SUM(p.welfare_fund) as total_welfare_fund,
                       SUM(p.weekend_overtime_pay) as total_weekend_overtime_pay,
                       SUM(p.weekday_overtime_pay) as total_weekday_overtime_pay,
                       SUM(p.total_overtime_pay) as total_overtime_pay
                FROM payslip p
                JOIN payslip_payroll pp ON p.id = pp.payslip_id
                WHERE pp.payroll_id = ?
                GROUP BY pp.payroll_id
            ) AS subquery ON pr.id = subquery.payroll_id
            SET pr.total_gross_pay = subquery.total_gross_pay,
                pr.total_paye = subquery.total_paye,
                pr.total_pension = subquery.total_pension,
                pr.total_addition = subquery.total_addition,
                pr.total_deduction = subquery.total_deduction,
                pr.total_welfare_fund = subquery.total_welfare_fund,
                pr.total_weekend_overtime_pay = subquery.total_weekend_overtime_pay,
                pr.total_weekday_overtime_pay = subquery.total_weekday_overtime_pay,
                pr.total_overtime_pay = subquery.total_overtime_pay,
                pr.total_net_pay = subquery.total_gross_pay - subquery.total_paye - subquery.total_pension - subquery.total_welfare_fund + subquery.total_addition - subquery.total_deduction + subquery.total_overtime_pay
            WHERE pr.id = ?
        ");
         $sql_update_payroll_totals->bind_param("ii", $payroll_id, $payroll_id);
         $sql_update_payroll_totals->execute();
         $sql_update_payroll_totals->close();

         // Fetch the updated payroll totals to return
         $sql_fetch_totals = $conn->prepare("SELECT * FROM payroll WHERE id = ? LIMIT 1");
         $sql_fetch_totals->bind_param("i", $payroll_id);
         $sql_fetch_totals->execute();
         $updated_totals = $sql_fetch_totals->get_result()->fetch_assoc();
         $sql_fetch_totals->close();

         // Fetch the updated payslip data to return
         $sql_fetch_updated_payslip = $conn->prepare("SELECT * FROM payslip WHERE id = ? LIMIT 1");
         $sql_fetch_updated_payslip->bind_param("i", $payslip_id);
         $sql_fetch_updated_payslip->execute();
         $updated_payslip = $sql_fetch_updated_payslip->get_result()->fetch_assoc();
         $sql_fetch_updated_payslip->close();


        echo json_encode(['success' => true, 'message' => 'Payslip and Payroll totals updated successfully', 'updated_payslip' => $updated_payslip, 'updated_totals' => $updated_totals]);

    } else {
         // This case should ideally not happen if payslip_payroll is correctly populated
        echo json_encode(['success' => true, 'message' => 'Payslip updated, but could not find linked payroll to update totals.']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update payslip: ' . $sql_update_payslip->error]);
}

$sql_update_payslip->close();
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
?>
