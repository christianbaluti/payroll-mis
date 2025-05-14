<?php
// send_payslip.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php'; // Adjust if necessary

// Read JSON input
$data = json_decode(file_get_contents('php://input'), true);

// Validate
if (!$data || empty($data['email'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid data']);
    exit;
}

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'mail.hallmark.mw';
    $mail->SMTPAuth = true;
    $mail->Username = 'noreply@hallmark.mw';
    $mail->Password = 'Manners1722*'; // use env variable if possible
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Recipients
    $mail->setFrom('noreply@hallmark.mw', 'Payroll MIS');
    $mail->addAddress($data['email'], $data['name']);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Your Payslip - ' . date('F Y');
    
    // Email body
    $mail->Body = "
        <h3>Payslip for " . htmlspecialchars($data['name']) . "</h3>
        <p><strong>Department:</strong> " . htmlspecialchars($data['department']) . "</p>
        <p><strong>Gross Salary:</strong> MWK " . number_format($data['salary']) . "</p>
        <p><strong>PAYE:</strong> MWK " . number_format($data['paye']) . "</p>
        <p><strong>Net Salary:</strong> MWK " . number_format($data['netSalary']) . "</p>
        <p><strong>Pension:</strong> MWK " . number_format($data['pension']) . "</p>
        <p><strong>Additions:</strong> MWK " . number_format($data['additions']) . "</p>
        <p><strong>Deductions:</strong> MWK " . number_format($data['deductions']) . "</p>
        <p><strong>Welfare Fund:</strong> MWK " . number_format($data['welfareFund']) . "</p>
        <br>
        <p><strong>Bank Name:</strong> " . htmlspecialchars($data['bankName']) . "</p>
        <p><strong>Account Name:</strong> " . htmlspecialchars($data['bankAccountName']) . "</p>
        <p><strong>Account Number:</strong> " . htmlspecialchars($data['bankAccountNumber']) . "</p>
        <p><strong>Branch:</strong> " . htmlspecialchars($data['bankBranch']) . "</p>
    ";

    $mail->send();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $mail->ErrorInfo]);
}
?>
