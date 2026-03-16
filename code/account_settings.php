<?php
include "session_login_logout.php";
$p_password_message = "";
$p_confirm_password_message = "";
?>
<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
 </script>
 <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
 <link rel="stylesheet" href="styles.css"> <!-- Your custom CSS file -->
 <title>Account Settings</title>

 <head>
  <style>
   .close:hover {
    background-color: red;
   }

   .cover-box {

    padding: 20px;
    margin-top: 20px;
    border-radius: 20px;
    display: flex;
    justify-content: space-between;
   }

   .cover-box>* {
    flex: 1;
   }

   .user-info {
    text-align: center;
   }

   .user-info h3 {
    margin-bottom: 10px;
   }

   .user-report {
    position: relative;
    padding: 10px;
    background-color: rgb(234, 234, 234);
    border-radius: 50px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    flex: 2.5;
    width: 100%;
    height: 100%;
    margin: auto;
    height: auto;

    overflow: hidden;
   }
  </style>
 </head>

<body>
 <form action="<?php echo htmlspecialchars(
                $_SERVER["PHP_SELF"]
               ); ?>" method="post" id="account_setting" autocomplete="off" onsubmit="return validateForm()">
  <div class="cover-b  <div class=" user-info>
   <div class="user-report alert alert-dismissible fade show" role="alert">
    
    <div class='text-center'>

     <h3> Change Password ?</h3>

    </div>
    <div class="blurry-background"></div>
    <div class="report-content">
     <div class="form-group">
      <label>
       <h5 class="float-left">New Password:</h5>
      </label>
      <div class="input-group mb-3">
       <div class="input-group-prepend">
        <span class="input-group-text"><i class="fas fa-lock"></i></span>

       </div>

       <input type='password' id='password' style="border-bottom-right-radius: 12px;border-top-right-radius: 12px;" name='new_pass' class='form-control w-25'>
      </div>

      <span class="error-message" id="password_message" style="color: red;"><?php echo $p_password_message; ?></span><br>
      <label>
       <h5 class="float-left">Confirm Password:</h5>
      </label>
      <div class="input-group mb-3">
       <div class="input-group-prepend">
        <span class="input-group-text"><i class="fas fa-lock"></i></span>
       </div>
       <input type='password' style="border-bottom-right-radius: 12px;border-top-right-radius: 12px;" id='confirm_password' name='con_new_pass' class='form-control w-25'>
      </div>
     </div>
     <div id="message" style="color: rgb(25, 25, 25);"></div>
     <div id="signup_successful" style="color: green"></div>
     <div class="form-check">
      <input class="form-check-input" type="checkbox" value="" id="sp" onclick=" togglePassword()">
      <label class="form-check-label" for="defaultCheck1">
       Show Passwords
      </label>
     </div>
     <span class="error-message" id="confirm_password_message" style="color: red;"><?php echo $p_confirm_password_message; ?></span>

     <?php

     error_reporting(0);
     require "vendor/autoload.php";

     use PHPMailer\PHPMailer\PHPMailer;
     use PHPMailer\PHPMailer\Exception;

     class Password_Updation
     {
      public  function processingUpdation()
      {
       try {
        // Attempt database connection
        $conn = new mysqli("localhost", "root", "", "neric");

        // Check for connection errors
        if ($conn->connect_error) {
         throw new Exception(
          "Connection failed: " . $conn->connect_error
         );
        }

        // Handle form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
         // Check if the form fields are set
         if (
          isset($_POST["new_pass"]) &&
          isset($_POST["con_new_pass"])
         ) {
          $p_new_password = $_POST["new_pass"];
          $p_confirm_new_password =
           $_POST["con_new_pass"];
          $password_updated = false;
          // Validate password fields
          if (
           empty($p_new_password) ||
           strlen($p_new_password) < 4
          ) {
           $p_password_message =
            "Password should be at least 4 characters long";
          }

          if (
           $p_new_password !== $p_confirm_new_password
          ) {
           $p_confirm_password_message =
            "Password and Confirm Password do not match";
          }

          if (
           empty($p_new_password) ||
           empty($p_confirm_new_password)
          ) {
           $p_password_message =
            "Please enter your password";
           $p_confirm_password_message =
            "Please confirm your password";
          }

          // If password validation passes, update the password
          if (
           empty($p_password_message) &&
           empty($p_confirm_password_message)
          ) {
           $email = $_SESSION["email"];

           // Prepare and execute SELECT statement to get user ID
           $select = "SELECT ud.id_db, ud.name_db
                FROM signup s
              JOIN userdetails_db ud ON s.id_db = ud.id_db
               WHERE s.email_db = ?";
           $stmt = $conn->prepare($select);
           $stmt->bind_param("s", $email);
           $stmt->execute();
           $result = $stmt->get_result();

           // If user found, update password
           if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_db = $row["id_db"];

            $hash = password_hash(
             $p_confirm_new_password,
             PASSWORD_DEFAULT
            );

            // Prepare and execute UPDATE statement to update password
            $update =
             "UPDATE signup SET password_db = ? WHERE id_db = ?";
            $stmt = $conn->prepare($update);
            $stmt->bind_param("si", $hash, $id_db);
            if ($stmt->execute()) {
             echo "<div class='alert alert-success' role='alert'><h3>Your Password is Updated.</h3></div>";

             $password_updated = true;
             $_SESSION = [];
             session_unset();
             session_destroy();
             // Send email notification
             require __DIR__ . "/mailer.php";
             $mail = getMailer();

             if (!($mail instanceof PHPMailer)) {
              echo "Failed to load mailer.php or it did not return a PHPMailer object.";
              exit();
             }

             $mail->addAddress($email);
             $mail->Subject = "Password Reset";
             $mail->isHTML(true);
             $mail->Body =
              "Fitness and Nutrition<br><strong>Hey " .
              $row["name_db"] .
              ", your Password is updated.</strong>";
             if (!$mail->send()) {
              echo "Error";
             }
            } else {
             throw new Exception(
              "Error updating password: " .
               $conn->error
             );
            }
           } else {
            echo "No user found with the provided email.";
           }
          }
         }
        }
        if ($password_updated)
         echo "<center> <a href='login.php'  style='border-radius: 17px;' class='btn btn-primary '>Login</a></center>";
        else {
         echo "<center><button type='submit' style='border-radius: 17px;' class='btn btn-primary'>Update Password</button> </center>";
        }
       } catch (Exception $e) {
        echo "<div class='alert alert-danger' role='alert'><h3>Something Went Wrong!</h3></div>";
        error_log($e->getMessage()); // Log error message for debugging
       }

       $conn->close();
      }
     }
     $password_updation = new  Password_Updation();
     $password_updation->processingUpdation();
     ?>

     <!-- <div class="input-group mb-3">
      <label>
       <h5 class="float-left" for="changeemail">Change Email:</h5><br>
      </label>
      <div class="input-group mb-3">
       <div class="input-group-prepend">
        <span class="input-group-text">
         <i id="email_icon" class="fas fa-user"></i>
        </span>
       </div>
       <input type="email" style="border-bottom-right-radius: 12px; border-top-right-radius: 12px;" class="form-control w-25" id="changeemail" aria-describedby="emailHelp">
      </div>
      <div class="d-flex justify-content-center align-items-center text-center">
       <button type="submit" style="border-radius: 17px;" class="btn btn-primary">Update email</button>
      </div>
     </div> -->


     <?php
     class Activity_updation extends Password_Updation
     {
     }

     ?>
     <script>
      function togglePassword() {
       let passwordField = document.getElementById('password');
       let confirmPasswordField = document.getElementById('confirm_password');
       let checkbox = document.getElementById('sp');

       if (checkbox.checked) {
        passwordField.type = 'text';
        confirmPasswordField.type = 'text';
       } else {
        passwordField.type = 'password';
        confirmPasswordField.type = 'password';
       }
      }

      function validateForm() {

       let password = document.getElementById('password').value;
       let confirmPassword = document.getElementById('confirm_password').value;
       let password_message = '';
       let confirm_password_message = '';

       if (password.trim() === '') {
        password_message = 'Please enter your password';
        document.getElementById('password').classList.add('is-invalid');
       } else if (password.length < 4 || !/[0-9]/.test(password)) {
        password_message = 'Password should be at least 4 characters long and contain at least one letter and one number';
        document.getElementById('password').classList.add('is-invalid');

       } else if (password.length >= 18) {
        password_message = 'Password is too long';
        document.getElementById('password').classList.add('is-invalid');
       } else {
        document.getElementById('password').classList.remove('is-invalid');
       }

       if (confirmPassword.trim() === '') {
        confirm_password_message = 'Please confirm your password';
        document.getElementById('confirm_password').classList.add('is-invalid');
       } else if (confirmPassword.length < 4 || !/[0-9]/.test(password)) {
        confirm_password_message = 'Password should be at least 4 characters long and contain at least one letter and one number';
        document.getElementById('confirm_password').classList.add('is-invalid');
       } else if (password !== confirmPassword) {
        confirm_password_message = 'Passwords do not match';
        document.getElementById('confirm_password').classList.add('is-invalid');
       } else {
        document.getElementById('confirm_password').classList.remove('is-invalid');
       }

       document.getElementById('password_message').innerHTML = password_message;
       document.getElementById('confirm_password_message').innerHTML = confirm_password_message;

       if (password_message === '' && confirm_password_message === '') {
        return true;
       } else {
        return false;
       }
      }
     </script>
     <div class='text-center'>

     </div>
    </div>
   </div>
  </div>
  </div>
 </form>
</body>

</html>