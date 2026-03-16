<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);

if (!$email) {
    die("Invalid email address");
}

// Store the email in session
$_SESSION['email'] = $email;

$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

$mysqli = require __DIR__ . "/database.php";

if (!($mysqli instanceof mysqli)) {
    die("Failed to connect to the database.");
}

$sql = "UPDATE signup
        SET reset_token_hash = ?,
            reset_token_expires_at = ?
        WHERE email_db = ?";

$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($mysqli->error));
}

$stmt->bind_param("sss", $token_hash, $expiry, $email);
$stmt->execute();

if ($stmt->affected_rows) {
    require __DIR__ . "/mailer.php";

    $mail = getMailer();

    if (!($mail instanceof PHPMailer)) {
        echo "Failed to load mailer.php or it did not return a PHPMailer object.";
        exit;
    }

    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $mail->isHTML(true); // Ensure HTML is enabled for the email body
    $mail->Body = <<<END
    <div style='font-family: Arial, sans-serif; color: #333; background-color: #f4f4f4; padding: 40px 0;'>
        <div style='max-width: 600px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'>
            
            <!-- Header Section -->
            <div style='background-color: #0073e6; color: white; padding: 30px 20px; border-top-left-radius: 8px; border-top-right-radius: 8px; text-align: center;'>
                <h1 style='font-size: 26px; margin: 0;'>Password Reset Request</h1>
                <p style='font-size: 16px; margin: 5px 0;'>We received a request to reset your password.</p>
            </div>
    
            <!-- Body Section -->
            <div style='padding: 20px; text-align: center;'>
                <p style='font-size: 18px; color: #333;'>
                    Hello,
                </p>
                <p style='font-size: 16px; color: #555;'>
                    To reset your password, please click the link below:
                </p>
                <a href="http://localhost/sem5_latest_2.0/sem5_latest_2.0/reset-password.php?token={$token}" style='display: inline-block; padding: 10px 20px; background-color: #0073e6; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px;'>Reset Password</a>
                <p style='font-size: 16px; color: #555; margin-top: 20px;'>
                    If you did not request a password reset, please ignore this email.
                </p>
            </div>
    
            <!-- Footer Section -->
            <div style='background-color: #f4f4f4; padding: 20px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; text-align: center;'>
                <p style='font-size: 14px; color: #777;'>
                    Need help? <a href='#' style='color: #0073e6; text-decoration: none;'>Contact our support team</a> at any time.
                </p>
                <p style='font-size: 14px; color: #777;'>
                    Stay healthy, stay fit!<br><strong>Fitness and Nutrition Analysis Team</strong>
                </p>
                <footer style='font-size: 12px; color: #aaa; margin-top: 10px;'>
                    &copy; 2024 Fitness and Nutrition Analysis. All rights reserved.
                </footer>
            </div>
        </div>
    </div>
    END;

    try {
        if ($mail->send()) {
            $message = "success: Message has been sent, check your mailbox ";
        } else {
            $message = "error: Failed to send message";
        }
    } catch (Exception $e) {
        $message = "error: Message could not be sent. Mailer error: {$mail->ErrorInfo}";
    }


    // Set the flag to indicate that the form has been submitted
    $_SESSION['form_submitted'] = true;

    // Redirect to forgot-password.php with the appropriate message
    header("Location: forgot-password.php?message=" . urlencode($message));
    exit;
}
