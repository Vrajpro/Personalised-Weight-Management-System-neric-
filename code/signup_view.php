<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Signup</title>
 <link rel="stylesheet" href="./css/login.css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-**************" crossorigin="anonymous" referrerpolicy="no-referrer" />
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous" />
 <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
 <style>.button-container {
    display: flex;
    gap: 10px; /* Space between buttons */
}

.btn {
    /* Add any additional button styling here */
}

    </style>
</head>

<body>
 <div class="container-fluid">
  <div class="login_outer">
   <div class="col-md-12 logo_outer">
    <img src="./images/nericlogo.jpg" class="rounded-circle" style="width: 100px;" />
   </div>
   <h4 class="title my-3 text-center">Sign Up For Access</h4>
   <div class="form-box">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="signup_form" autocomplete="off" onsubmit="return validateForm()">
     <div class="input-field">
      <label for="email">Email</label>
      <div class="input-group mb-3">
       <div class="input-group-prepend">
        <span class="input-group-text"><i class="fas fa-envelope" id="email_icon"></i></span>
       </div>
       <input name="h_email" style="border-bottom-right-radius: 12px;border-top-right-radius: 12px;" type="text" class="form-control" id="email" placeholder="Enter your email" aria-label="Enter your email" aria-describedby="basic-addon1" value="<?php echo isset($_POST['h_email']) ? htmlspecialchars($_POST['h_email']) : ''; ?>">
      </div>
      <span class="error-message" id="p_email_message" style="color: red;"><?php echo $sign_up->p_email_message; ?></span>
     </div>
     <div class="input-field">
      <label for="password">Password</label>
      <div class="input-group mb-3">
       <div class="input-group-prepend">
        <span class="input-group-text"><i class="fas fa-lock" id="password_icon"></i></span>
       </div>
       <input name="h_password" style="border-bottom-right-radius: 12px;border-top-right-radius: 12px;" type="password" class="form-control" id="password" placeholder="Enter your password" aria-label="Enter your password" aria-describedby="basic-addon1">
      </div>
      <span style="color:red;"><?php echo $sign_up->p_password_message; ?></span>
     </div>
     <div class="input-field">
      <label for="confirm_password">Confirm Password</label>
      <div class="input-group mb-3">
       <div class="input-group-prepend">
        <span class="input-group-text"><i class="fas fa-lock" id="confirm_password_icon"></i></span>
       </div>
       <input name="h_confirm_password" style="border-bottom-right-radius: 12px;border-top-right-radius: 12px;" type="password" class="form-control" id="confirm_password" placeholder="Confirm password" aria-label="Password" aria-describedby="basic-addon1">
      </div>
      <span class="error-message" id="confirm_password_message" style="color: red;"><?php echo $sign_up->p_confirm_password_message; ?></span>
     </div>
<div class="button-container">
    <form action="signup_process.php" method="post">
        <button class="btn" id="button1" type="submit" name="signup">Sign up</button>
    </form>
    <form action="login.php" method="get">
        <button class="btn" id="button2" type="submit">Back</button>
    </form>
</div>
<div id="message" style="color: rgb(25, 25, 25);"></div>
<div id="signup_successful" style="color: green"><?php echo $sign_up->success_message; ?></div>

<div id="message" style="color: rgb(25, 25, 25);"></div>
<div id="signup_successful" style="color: green"><?php echo $sign_up->success_message; ?></div>

    </form>
   </div>
  </div>
 </div>
 <script>
  function validateForm() {
   var email = document.getElementById('email').value;
   var password = document.getElementById('password').value;
   var confirmPassword = document.getElementById('confirm_password').value;

   var email_regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

   var email_message = '';
   var password_message = '';
   var confirm_password_message = '';

   if (email.trim() === '') {
    email_message = 'Please enter your email';
    document.getElementById('email_icon').style.color = 'red';
   } else if (!email_regex.test(email)) {
    email_message = 'Please enter a valid email address';
    document.getElementById('email_icon').style.color = 'red';
   } else {
    document.getElementById('email_icon').style.color = 'green';
   }

   if (password.trim() === '') {
    password_message = 'Please enter your password';
    document.getElementById('password_icon').style.color = 'red';
   } else if (password.length < 4) {
    password_message = 'Password should be at least 4 characters long';
    document.getElementById('password_icon').style.color = 'red';
   } else {
    document.getElementById('password_icon').style.color = 'green';
   }

   if (confirmPassword.trim() === '') {
    confirm_password_message = 'Please confirm your password';
    document.getElementById('confirm_password_icon').style.color = 'red';
   } else if (confirmPassword.length < 4) {
    confirm_password_message = 'Confirm password should be at least 4 characters long';
    document.getElementById('confirm_password_icon').style.color = 'red';
   } else if (password !== confirmPassword) {
    confirm_password_message = 'Passwords do not match';
    document.getElementById('confirm_password_icon').style.color = 'red';
   } else {
    document.getElementById('confirm_password_icon').style.color = 'green';
   }

   document.getElementById('p_email_message').innerHTML = email_message;
   document.getElementById('password_message').innerHTML = password_message;
   document.getElementById('confirm_password_message').innerHTML = confirm_password_message;

   if (email_message === '' && password_message === '' && confirm_password_message === '') {
    return true;
   } else {
    return false;
   }
  }

  document.getElementById('email').addEventListener('input', function() {
   validateForm();
  });

  document.getElementById('password').addEventListener('input', function() {
   validateForm();
  });

  document.getElementById('confirm_password').addEventListener('input', function() {
   validateForm();
  });
 </script>
</body>

</html>