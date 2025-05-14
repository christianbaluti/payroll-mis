<?php
header('Content-Type: application/json');
require '../../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data || !isset($data['month'], $data['year'], $data['employees'])) {
        throw new Exception('Invalid request data');
    }

    $pdo->beginTransaction();

    // Totals
    $total_gross = $total_net = $total_paye = $total_pension = $total_additions = $total_deductions = $total_welfare_fund = $total_weekend_overtime = $total_weekday_overtime = $total_overtime = 0;

    foreach ($data['employees'] as $emp) {
        $total_gross += $emp['gross_salary'];
        $total_net += $emp['gross_salary'] - $emp['paye'];
        $total_paye += $emp['paye'];
        $total_pension += $emp['pension'];
        $total_additions += $emp['additions'];
        $total_deductions += $emp['deductions'];
        $total_welfare_fund += $emp['welfare_fund'];
        $total_weekend_overtime += $emp['weekend_overtime_pay'];
        $total_weekday_overtime += $emp['weekday_overtime_pay'];
        $total_overtime += $emp['weekend_overtime_pay'] + $emp['weekday_overtime_pay'];
    }

    // Insert into payroll
    $stmt = $pdo->prepare("
        INSERT INTO payroll 
        (month, year, unique_id, status, total_gross_pay, total_net_pay, total_paye, total_pension, total_addition, total_deduction, total_welfare_fund, total_weekend_overtime_pay, total_weekday_overtime_pay, total_overtime_pay) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $data['month'],
        $data['year'],
        $data['unique_id'],
        'drafted',
        $total_gross,
        $total_net,
        $total_paye,
        $total_pension,
        $total_additions,
        $total_deductions,
        $total_welfare_fund,
        $total_weekend_overtime,
        $total_weekday_overtime,
        $total_overtime
    ]);

    $payroll_id = $pdo->lastInsertId();
    $current_date = date('Y-m-d H:i:s');

    // Prepare statements
    $stmtPayslip = $pdo->prepare("
        INSERT INTO payslip (
            name, department, Gender, branch, gross_salary, paye, pension, additions, deductions, welfare_fund,
            hourly_rate, weekend_overtime_hours, weekday_overtime_hours, weekend_overtime_pay, weekday_overtime_pay,
            total_overtime_hours, total_overtime_pay, bank_name, bank_account_name, bank_account_number,
            bank_branch, month, datemade, status, email, year
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmtPayslipPayroll = $pdo->prepare("
        INSERT INTO payslip_payroll (payroll_id, payslip_id, month, year, datemade) 
        VALUES (?, ?, ?, ?, ?)
    ");

    // Loop through employees and save payslip + payslip_payroll link
    foreach ($data['employees'] as $emp) {
        // Insert into payslip
        $stmtPayslip->execute([
            $emp['name'],
            $emp['department'],
            $emp['gender'],
            $emp['branch'],
            $emp['gross_salary'],
            $emp['paye'],
            $emp['pension'],
            $emp['additions'],
            $emp['deductions'],
            $emp['welfare_fund'],
            $emp['hourly_rate'],
            $emp['weekend_overtime_hours'],
            $emp['weekday_overtime_hours'],
            $emp['weekend_overtime_pay'],
            $emp['weekday_overtime_pay'],
            $emp['weekend_overtime_hours'] + $emp['weekday_overtime_hours'], // total_overtime_hours
            $emp['weekend_overtime_pay'] + $emp['weekday_overtime_pay'],     // total_overtime_pay
            $emp['bank_name'],
            $emp['bank_account_name'],
            $emp['bank_account_number'],
            $emp['bank_branch'],
            $data['month'],
            $current_date,
            'drafted',
            $emp['email'],
            $data['year']
        ]);

        $payslip_id = $pdo->lastInsertId();

        // Insert into payslip_payroll
        $stmtPayslipPayroll->execute([
            $payroll_id,
            $payslip_id,
            $data['month'],
            $data['year'],
            $current_date
        ]);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'payroll_id' => $payroll_id]);

} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
