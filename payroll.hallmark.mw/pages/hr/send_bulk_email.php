<?php
require_once '../../db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

header('Content-Type: application/json');

// Read POSTed JSON
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['emails']) || !isset($data['message'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    exit;
}

$emails = $data['emails'];
$messageContent = $data['message'];

if (empty($emails) || empty($messageContent)) {
    echo json_encode(['status' => 'error', 'message' => 'Emails or message content is empty.']);
    exit;
}

try {
    foreach ($emails as $email) {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'mail.hallmark.mw'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply@hallmark.mw'; // Your SMTP email
        $mail->Password = 'Manners1722*'; // Your SMTP password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('noreply@hallmark.mw', 'Hallmark HR');
        $mail->addAddress($email);
        $mail->isHTML(true);

        $mail->Subject = "Important Message from HR Department";
        $mail->Body = $messageContent;

        $mail->send();
    }

    echo json_encode(['status' => 'success', 'message' => 'Emails sent successfully!']);
} catch (Exception $e) {
    error_log('Mail error: ' . $mail->ErrorInfo);
    echo json_encode(['status' => 'error', 'message' => 'Failed to send some or all emails.']);
}
?>
