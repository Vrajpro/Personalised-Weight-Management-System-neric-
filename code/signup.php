<?php
session_start();

require 'vendor/autoload.php'; // Include PHPMailer autoload file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sanitize_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function getMailer()
{
  $mail = new PHPMailer(true);

  try {
    // SMTP configuration
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'nericproject@gmail.com';
    // Use app-specific password if 2-Step Verification is enabled
    $mail->Password   = 'agmw bsaj vpjc otei';
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

function sendWelcomeEmail($recipientEmail)
{
  $mail = getMailer(); // Get PHPMailer object

  try {
    // Recipient
    $mail->addAddress($recipientEmail);

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'Welcome to Fitness and Nutrition Analysis!';

    // HTML email body with a modern and professional design
    $mail->Body = "
            <div style='font-family: Arial, sans-serif; color: #333; background-color: #f4f4f4; padding: 40px 0;'>
                <div style='max-width: 600px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'>
                    
                    <!-- Header Section -->
                    <div style='background-color: #0073e6; color: white; padding: 30px 20px; border-top-left-radius: 8px; border-top-right-radius: 8px; text-align: center;'>
                        <h1 style='font-size: 26px; margin: 0;'>Welcome to Fitness and Nutrition Analysis!</h1>
                        <p style='font-size: 16px; margin: 5px 0;'>Thank you for signing up with us!</p>
                    </div>

                    <!-- Body Section -->
                    <div style='padding: 20px; text-align: center;'>
                        <p style='font-size: 18px; color: #333;'>
                            Hello, <strong>New Member!</strong>
                        </p>
                        <p style='font-size: 16px; color: #555;'>
                            We are thrilled to have you join our community. 
                        </p>
                        <p style='font-size: 16px; color: #555; margin-top: 20px;'>
                            Your journey to a healthier lifestyle begins here!
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
        ";

    // Send email
    if ($mail->send()) {
      return true;
    } else {
      return false;
    }
  } catch (Exception $e) {
    return false;
  }
}

class Signup
{
  public $p_email_message = '';
  public $p_password_message = '';
  public $p_confirm_password_message = '';
  public $success_message = '';

  function processing()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $p_email = sanitize_input($_POST["h_email"]);
      $p_password = sanitize_input($_POST["h_password"]);
      $p_confirmPassword = sanitize_input($_POST["h_confirm_password"]);

      include './config.php';

      if (!filter_var($p_email, FILTER_VALIDATE_EMAIL)) {
        $this->p_email_message = "Invalid email format or required";
      }

      if (empty($p_password) || strlen($p_password) < 4) {
        $this->p_password_message = "Password should be at least 4 characters long";
      }

      if ($p_password !== $p_confirmPassword) {
        $this->p_confirm_password_message = "Passwords do not match";
      }

      if (empty($p_email) || empty($p_password) || empty($p_confirmPassword)) {
        $this->p_email_message = "Please enter your email";
        $this->p_password_message = "Please enter your password";
        $this->p_confirm_password_message = "Please confirm your password";
      } else {
        $check_existing_email_query = "SELECT * FROM signup JOIN userdetails_db ON signup.id_db = userdetails_db.id_db WHERE email_db = '$p_email'";
        $result = $conn->query($check_existing_email_query);
        if ($result->num_rows > 0) {
          $this->p_confirm_password_message = "You are already signed up";
        } else {
          $delete = "DELETE FROM signup WHERE email_db = '$p_email'";
          $conn->query($delete);
          $hash = password_hash($p_password, PASSWORD_DEFAULT);
          $sql = "INSERT INTO signup (email_db, password_db) VALUES ('$p_email', '$hash')";

          if ($conn->query($sql) === TRUE) {
            $id = $conn->insert_id;
            // Send welcome email
            if (sendWelcomeEmail($p_email)) {
              $this->success_message = "Welcome email sent successfully.";
            } else {
              $this->success_message = "Failed to send welcome email.";
            }
            $_SESSION['signup_email'] = $p_email;
            $_SESSION['id'] = $id;

            $_SESSION['signup_successful'] = true;

            header("Location: userdetails.php?signup=success");
            exit();
          } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
          }
        }
      }

      $conn->close();
    } else {
      $_SESSION['signup_successful'] = false;
    }
  }
}

$sign_up = new Signup();
$sign_up->processing();
include "signup_view.php";
