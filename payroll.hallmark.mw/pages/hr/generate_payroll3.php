<?php
header('Content-Type: application/json');
require '../../db.php'; // Assuming this file handles the $pdo connection

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data || !isset($data['month'], $data['year'], $data['employees'], $data['unique_id'])) {
        throw new Exception('Invalid request data');
    }

    // Start transaction
    $pdo->beginTransaction();

    // Insert into main payroll table (assuming this is still needed based on your provided code)
    // Although the user's primary request is for individual payslips,
    // this part was in the original backend code provided.
    // If the 'payroll' table and 'payslip_payroll' table are not required,
    // this section and the second INSERT statement can be removed.
    $stmt_payroll = $pdo->prepare(
        "INSERT INTO payroll
            (month, year, unique_id, status, total_gross_pay, total_net_pay, total_paye, total_pension, total_addition, total_deduction, total_welfare_fund, total_weekend_overtime_pay, total_weekday_overtime_pay, total_overtime_pay)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    // Use totals sent from the frontend for the payroll summary row
     $stmt_payroll->execute([
        $data['month'],
        $data['year'],
        $data['unique_id'],
        'drafted', // Status from frontend, though 'drafted' was hardcoded
        $data['totals']['totalGross'],
        $data['totals']['totalNet'],
        $data['totals']['totalPaye'],
        $data['totals']['totalPension'],
        $data['totals']['totalAdditions'],
        $data['totals']['totalDeductions'],
        $data['totals']['totalWelfareFund'],
        $data['totals']['totalWeekendOvertimePay'],
        $data['totals']['totalWeekdayOvertimePay'],
        $data['totals']['totalOvertimePay']
    ]);

    $payroll_id = $pdo->lastInsertId();


    // Prepare statement for inserting into the payslip table
    $stmt_payslip = $pdo->prepare(
        "INSERT INTO payslip
            (name, department, Gender, branch, gross_salary, paye, pension, additions, deductions, welfare_fund, hourly_rate, weekend_overtime_hours, weekday_overtime_hours, weekend_overtime_pay, weekday_overtime_pay, total_overtime_hours, total_overtime_pay, bank_name, bank_account_name, bank_account_number, bank_branch, month, datemade, status, email, year)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

     // Prepare statement for inserting into payslip_payroll (linking table)
    $stmt_payslip_payroll = $pdo->prepare(
        "INSERT INTO payslip_payroll (payroll_id, payslip_id, month, year, datemade) VALUES (?, ?, ?, ?, ?)"
    );


    $current_date = date('Y-m-d H:i:s');
    foreach ($data['employees'] as $emp) {
        // Insert into payslip table
        // NOTE: The 'branch' column in the payslip table schema exists,
        // but 'branch' data is not included in the employee data sent from the frontend (payrun2.php).
        // You will need to modify payrun2.php to send the branch information
        // for each employee or fetch it in this backend script based on the employee ID.
        // For now, I will insert a placeholder or an empty string for 'branch'.
        $branch_name = ''; // Placeholder - Needs to be fetched or sent from frontend

        // Calculate total overtime hours for the payslip table
        $total_overtime_hours = $emp['weekend_overtime_hours'] + $emp['weekday_overtime_hours'];

        $stmt_payslip->execute([
            $emp['name'],
            $emp['department'],
            $emp['gender'],
            $branch_name, // Use the branch name variable
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
            $total_overtime_hours, // total_overtime_hours
            $emp['weekend_overtime_pay'] + $emp['weekday_overtime_pay'], // total_overtime_pay
            $emp['bank_name'],
            $emp['bank_account_name'],
            $emp['bank_account_number'],
            $emp['bank_branch'],
            $data['month'],
            $current_date,
            $data['status'], // Status from frontend, though 'drafted' was hardcoded
            $emp['email'],
            $data['year']
        ]);

        $payslip_id = $pdo->lastInsertId();

        // Insert into payslip_payroll (linking table)
        // Assuming this table is used to link individual payslips to a payroll run
        $stmt_payslip_payroll->execute([
            $payroll_id,
            $payslip_id, // Use the newly generated payslip_id
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