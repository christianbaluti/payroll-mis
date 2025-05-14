<?php
require 'db.php'; // your PDO DB connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    if (empty($email) || empty($role)) {
        echo json_encode(["status" => "error", "message" => "Email and role are required."]);
        exit;
    }

    // Check if email already exists
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        echo json_encode(["status" => "error", "message" => "Email already invited or registered."]);
        exit;
    }

    $token = bin2hex(random_bytes(16));
    $insert = $pdo->prepare("INSERT INTO users (email, phone, role, password, token) VALUES (?, '', ?, NULL, ?)");
    $saved = $insert->execute([$email, $role, $token]);

    if (!$saved) {
        echo json_encode(["status" => "error", "message" => "Failed to insert user."]);
        exit;
    }

    // Send email
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'mail.hallmark.mw';
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply@hallmark.mw';
        $mail->Password = 'Manners1722*'; // Replace securely
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('noreply@hallmark.mw', 'Hallmark Payroll');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Create Your Hallmark Payroll Account';

        $link = "https://payroll.hallmark.mw/account_setup.php?token=$token";

        $mail->Body = "
        <div style='font-family: Poppins, sans-serif; padding: 20px; background: #f9fafb;'>
            <h2 style='color: #2563eb;'>Welcome to Hallmark Payroll 👋</h2>
            <p>You’ve been invited to create your account as <strong>$role</strong>.</p>
            <p>Click the button below to complete your account setup:</p>
            <a href='$link' style='background: #2563eb; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Create My Account</a>
            <p style='margin-top: 20px; font-size: 12px; color: #6b7280;'>If you didn’t expect this email, you can ignore it.</p>
        </div>
        ";

        // Send email synchronously
        $mailResult = $mail->send();
        
        if ($mailResult) {
            echo json_encode([
                "status" => "success",
                "message" => "Invitation email sent successfully to $email."
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Failed to send invitation email: {$mail->ErrorInfo}"
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "An error occurred while processing your request: {$e->getMessage()}"
        ]);
    }
}
?>