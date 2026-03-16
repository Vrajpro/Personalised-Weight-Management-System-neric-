<?php
session_start();
require "vendor/autoload.php"; // Include PHPMailer autoload file

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
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "nericproject@gmail.com";
        // Use app-specific password if 2-Step Verification is enabled
        $mail->Password = "agmw bsaj vpjc otei";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender information
        $mail->setFrom("nericproject@gmail.com", "NERIC");

        // Return the PHPMailer object
        return $mail;
    } catch (Exception $e) {
        // Display error message if initialization fails
        echo "Failed to initialize PHPMailer: {$e->getMessage()}";
        exit();
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
        $mail->Subject = "Login Success";

        // HTML email body with a modern and professional design
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; color: #333; background-color: #f4f4f4; padding: 40px 0;'>
                <div style='max-width: 600px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'>
                    
                    <!-- Header Section -->
                    <div style='background-color: #0073e6; color: white; padding: 30px 20px; border-top-left-radius: 8px; border-top-right-radius: 8px; text-align: center;'>
                        <h1 style='font-size: 26px; margin: 0;'>Welcome to Fitness and Nutrition Analysis!</h1>
                        <p style='font-size: 16px; margin: 5px 0;'>Your health journey begins here.</p>
                    </div>

                    <!-- Body Section -->
                    <div style='padding: 20px; text-align: center;'>
                        <p style='font-size: 18px; color: #333;'>
                            Hello, <strong>Welcome!</strong>
                        </p>
                        <p style='font-size: 16px; color: #555;'>
                            We are excited to have you with us. You have <span style='color: #28a745; font-weight: bold;'>successfully</span> logged in to your account.
                        </p>
                        <p style='font-size: 16px; color: #555; margin-top: 20px;'>
                            Stay tuned for personalized nutrition tips and much more to help you reach your goals!
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
            return true; // Email sent successfully
        } else {
            return false; // Failed to send email
        }
    } catch (Exception $e) {
        return false; // Exception occurred
    }
}

$p_email_message = "";
$p_password_message = "";
$success_message = "";
$p_status = "";
include './config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $p_email = sanitize_input($_POST["h_email"]);
    $p_password = sanitize_input($_POST["h_password"]);


    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (!filter_var($p_email, FILTER_VALIDATE_EMAIL)) {
        $p_email_message = "Invalid email format";
    } else {
        // Check if the provided credentials are for the admin
        if ($p_email === "admin@gmail.com" && $p_password === "admin123") {
            $_SESSION["admin_loggedin"] = true;
            header("Location: ./admin/admin_panel.php");
            exit();
        } else {
            // Proceed with the regular login process
            $sql = "SELECT * FROM signup JOIN userdetails_db ON signup.id_db = userdetails_db.id_db WHERE email_db = '$p_email'";
            $result = $conn->query($sql);

            if ($result->num_rows == 1) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Compare the plain text password with the password from the database
                    if (password_verify($p_password, $row["password_db"])) {
                        session_regenerate_id(true);
                        $_SESSION["loggedin"] = true;
                        $_SESSION["email"] = $p_email;
                        $p_status = $row['status_db'];

                        setcookie("user_email", $p_email, time() + (30 * 24 * 60 * 60), "/");
                        if ($p_status == 'deactivated') {
                            session_unset();
                            session_destroy();

                            break;
                        }
                        // Send welcome email
                        if (sendWelcomeEmail($p_email)) {
                            $success_message =
                                "Welcome email sent successfully.";
                        } else {
                            $success_message = "Failed to send welcome email.";
                        }
                        header("Location: home-page.php");
                        exit();
                    } else {
                        $p_password_message = "Incorrect password";
                    }
                }
            } else {
                $p_email_message = "Email does not exist";
            }
        }
    }
}

$email_value = isset($_COOKIE['user_email']) ? $_COOKIE['user_email'] : '';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <link rel="stylesheet" href="./css/login.css">
</head>
<style>
    .error-message {
        color: red;
    }

    .success-message {
        color: green;
    }
</style>

<body>
    <div class="container-fluid">
        <div class="login_outer">
            <div class="col-md-12 logo_outer">
                <img src="./images/nericlogo.jpg" class="rounded-circle" style="width: 100px;" />
            </div>
            <?php
            if ($p_status) {
                echo "<p class='alert alert-warning text-center' role='alert'>Your,account is<strong> {$p_status}</strong>🚫.";
            }
            ?>
            <form id="loginForm" method="post" autocomplete="off" onsubmit="return validateForm()">
                <div class="form-row">
                    <h4 class="title my-3">Login For Access</h4>
                    <div class="col-12">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i id="email_icon" class="fas fa-user"></i>
                                </span>
                            </div>
                            <input id="email" style="border-bottom-right-radius: 12px;border-top-right-radius: 12px;" name="h_email" type="email" class="input form-control" placeholder="Email" value="<?php echo $email_value ?>">
                        </div>
                        <span id="email_message" class="error-message"><?php echo $p_email_message; ?></span>
                    </div>
                    <div class="col-12">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i id="password_icon" class="fas fa-lock"></i>
                                </span>
                            </div>
                            <input id="password" style="border-bottom-right-radius: 12px;border-top-right-radius: 12px;" name="h_password" type="password" class="input form-control" id="password" placeholder="Password">
                        </div>
                        <span id="password_message" class="error-message"><?php echo $p_password_message; ?></span>
                    </div>
                    <div class="col-sm-12 pt-3 text-right">
                        <p>Create an Account <a href="./signup.php">Register</a></p>
                        <a id="forgot_password" href="forgot-password.php">Forgot password?</a> <!-- Added id attribute -->
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit" name="h_login" id="loginBtn">
                            <span id="loginBtnText">Login</span>
                            <span id="loginBtnSpinner" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </div>
                    <script>
                        document.getElementById('loginBtn').addEventListener('click', function() {
                            var btnText = document.getElementById('loginBtnText');
                            var btnSpinner = document.getElementById('loginBtnSpinner');


                            btnText.style.display = 'none';
                            btnSpinner.style.display = 'inline-block';


                            setTimeout(function() {

                                btnText.style.display = 'inline-block';
                                btnSpinner.style.display = 'none';
                            }, 6000);
                        });
                    </script>

                </div>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script>
        try {
            document.getElementById('email').addEventListener('input', function() {
                var email = this.value.trim();
                var forgotPasswordLink = document.getElementById('forgot_password');
                if (email !== '') {
                    forgotPasswordLink.style.display = 'block';
                } else {
                    forgotPasswordLink.style.display = 'none';
                }
            });


            function validateForm() {
                var email = document.getElementById('email').value;
                var password = document.getElementById('password').value;

                var email_regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                var email_message = '';
                var password_message = '';

                if (email.trim() === '') {
                    email_message = 'Please enter your email';
                    document.getElementById('email_message').innerHTML = email_message;
                    document.getElementById('email_icon').style.color = 'red';
                } else if (!email_regex.test(email)) {
                    email_message = 'Please enter a valid email address';
                    document.getElementById('email_message').innerHTML = email_message;
                    document.getElementById('email_icon').style.color = 'red';
                } else {
                    document.getElementById('email_message').innerHTML = '';
                    document.getElementById('email_icon').style.color = 'green';
                }

                if (password.trim() === '') {
                    password_message = 'Please enter your password';
                    document.getElementById('password_message').innerHTML = password_message;
                    document.getElementById('password_icon').style.color = 'red';
                } else {
                    document.getElementById('password_message').innerHTML = '';
                    document.getElementById('password_icon').style.color = 'green';
                }

                if (email_message === '' && password_message === '') {
                    return true;
                } else {
                    return false;
                }
            }
        } catch (error) {
            console.log("Something went wrong")
        }
    </script>


</body>

</html>