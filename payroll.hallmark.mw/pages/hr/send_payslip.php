<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include Composer autoloader for PHPMailer and TCPDF
require '../../vendor/autoload.php';

// Include TCPDF manually if it's not inside vendor
require_once '../../vendor/tecnickcom/tcpdf/tcpdf.php'; 

// Get JSON data
$data = json_decode(file_get_contents('php://input'), true);

// Validate data
if (!$data || empty($data['email'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid data']);
    exit;
}

try {
    // === Generate Payslip PDF === //
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Payroll MIS');
    $pdf->SetTitle('Payslip - ' . $data['name']);
    $pdf->SetMargins(20, 20, 20);
    $pdf->AddPage();

    $html = '
        <h2 style="text-align:center;">Payslip - ' . date('F Y') . '</h2>
        <hr>
        <table cellpadding="5">
            <tr><td><strong>Name:</strong></td><td>' . htmlspecialchars($data['name']) . '</td></tr>
            <tr><td><strong>Department:</strong></td><td>' . htmlspecialchars($data['department']) . '</td></tr>
            <tr><td><strong>Gender:</strong></td><td>' . htmlspecialchars($data['gender']) . '</td></tr>
            <tr><td><strong>Gross Salary:</strong></td><td>MWK ' . number_format(floatval($data['salary'])) . '</td></tr>
            <tr><td><strong>PAYE:</strong></td><td>MWK ' . number_format(floatval($data['paye'])) . '</td></tr>
            <tr><td><strong>Net Salary:</strong></td><td>MWK ' . number_format(floatval($data['netSalary'])) . '</td></tr>
            <tr><td><strong>Pension:</strong></td><td>MWK ' . number_format(floatval($data['pension'])) . '</td></tr>
            <tr><td><strong>Additions:</strong></td><td>MWK ' . number_format(floatval($data['additions'])) . '</td></tr>
            <tr><td><strong>Deductions:</strong></td><td>MWK ' . number_format(floatval($data['deductions'])) . '</td></tr>
            <tr><td><strong>Welfare Fund:</strong></td><td>MWK ' . number_format(floatval($data['welfareFund'])) . '</td></tr>
            <tr><td><strong>Bank Name:</strong></td><td>' . htmlspecialchars($data['bankName']) . '</td></tr>
            <tr><td><strong>Account Name:</strong></td><td>' . htmlspecialchars($data['bankAccountName']) . '</td></tr>
            <tr><td><strong>Account Number:</strong></td><td>' . htmlspecialchars($data['bankAccountNumber']) . '</td></tr>
            <tr><td><strong>Branch:</strong></td><td>' . htmlspecialchars($data['bankBranch']) . '</td></tr>
        </table>
    ';

    $pdf->writeHTML($html);
    $tempFile = tempnam(sys_get_temp_dir(), 'payslip_') . '.pdf';
    $pdf->Output($tempFile, 'F');

    // === Send Email === //
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'mail.hallmark.mw';
    $mail->SMTPAuth = true;
    $mail->Username = 'noreply@hallmark.mw';
    $mail->Password = 'Manners1722*';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    $mail->setFrom('noreply@hallmark.mw', 'Payroll MIS');
    $mail->addAddress($data['email'], $data['name']);

    $mail->isHTML(true);
    $mail->Subject = 'Your Payslip - ' . date('F Y');
    $mail->Body = "Dear " . htmlspecialchars($data['name']) . ",<br><br>
        Please find attached your payslip for " . date('F Y') . ".<br><br>
        Regards,<br>Payroll Team.";

    $mail->addAttachment($tempFile, 'Payslip_' . preg_replace('/\s+/', '_', $data['name']) . '_' . date('FY') . '.pdf');

    $mail->send();
    unlink($tempFile); // Clean up

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
