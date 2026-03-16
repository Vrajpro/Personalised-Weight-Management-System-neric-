<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';


function getMailer() {
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'nericproject@gmail.com';
        // Use app-specific password if 2-Step Verification is enabled
        $mail->Password   = 'afph mwep watk fmlz'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Sender information
        $mail->setFrom('nericproject@gmail.com', 'NERIC');

        // Return the PHPMailer object
        return $mail;
    } catch (Exception $e) {
        // Display error message if initialization fails
        echo "Failed to initialize PHPMailer: {$e->getMessage()}";
        exit;
    }
}
