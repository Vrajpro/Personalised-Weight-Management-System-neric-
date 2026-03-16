<?php

$token = $_GET["token"] ?? '';

if (empty($token)) {
 die("Token is missing");
}

$token = filter_var($token, FILTER_SANITIZE_STRING);

if (strlen($token) < 10) { // Just an example check
 die("Invalid token format");
}

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/database.php";

$sql = "SELECT * FROM signup WHERE reset_token_hash = ?";
$stmt = $mysqli->prepare($sql);
if (!$stmt) {
 die("Database error: " . $mysqli->error);
}

$stmt->bind_param("s", $token_hash);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user === null) {
 die("Token not found");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
 die("Token has expired");
}

?>

<!DOCTYPE html>
<html>

<head>
 <title>Reset Password</title>
 <meta charset="UTF-8">
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
 <style>
  body {
   background-color: #f0f0f0;
   /* Light gray background color */
   height: 100vh;
   margin: 0;
   display: flex;
   justify-content: center;
   /* Center horizontally */
   align-items: center;
   background-image: linear-gradient(45deg, #eaeaea 25%, transparent 25%), linear-gradient(-45deg, #eaeaea 25%, transparent 25%), linear-gradient(45deg, transparent 75%, #eaeaea 75%), linear-gradient(-45deg, transparent 75%, #eaeaea 75%);
   background-size: 20px 20px;
  }

  .center-content {
   position: absolute;
   top: 50%;
   left: 50%;
   transform: translate(-50%, -50%);
  }

  form {
   width: 300px;
   /* Adjust the width of the form as needed */
   margin: auto;
   /* Set margin to auto for centering horizontally */
   background-color: rgba(255, 255, 255, 0.9);
   /* Set background color to white with 90% opacity */
   padding: 20px;
   /* Add padding to create space between content and border */
   border-radius: 8px;
   /* Add border radius for rounded corners */
   box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
   /* Add box shadow for depth */
  }

  label {
   color: #333;
   /* Change label color to a darker shade */
   font-size: 18px;
   /* Decrease font size for better proportion */
   margin-bottom: 10px;
   /* Add margin bottom for spacing */
  }

  input[type="email"],
  input[type="password"],
  input[type="text"] {
   background-color: #f9f9f9;
   /* Light gray background color */
   border: 1px solid #ccc;
   /* Light gray border */
   border-radius: 4px;
   /* Add border radius for rounded corners */
   padding: 10px;
   /* Adjust padding */
   width: calc(100% - 20px);
   /* Adjust width to account for padding */
   margin-bottom: 20px;
   /* Add margin bottom for spacing */
   color: black;
   /* Set text color to black */
  }

  button {
   background-color: #007bff;
   /* Blue background color */
   color: #fff;
   /* White text color */
   border: none;
   /* Remove border */
   border-radius: 4px;
   /* Add border radius for rounded corners */
   padding: 12px 24px;
   /* Add padding */
   font-size: 18px;
   /* Increase font size */
   cursor: pointer;
   /* Add pointer cursor */
   transition: background-color 0.3s ease;
   /* Add smooth transition for hover effect */
   width: 100%;
   /* Make width 100% */
  }

  button:hover {
   background-color: #0056b3;
   /* Darker blue on hover */
  }

  .login-button {
   background-color: #28a745;
   /* Green background color */
   color: #fff;
   /* White text color */
   border: none;
   /* Remove border */
   border-radius: 4px;
   /* Add border radius for rounded corners */
   padding: 12px 24px;
   /* Add padding */
   font-size: 18px;
   /* Font size */
   cursor: pointer;
   /* Add pointer cursor */
   transition: background-color 0.3s ease;
   /* Add smooth transition for hover effect */
   width: 100%;
   /* Make width 100% */
   margin-top: 10px;
   /* Add margin top */
  }

  .login-button:hover {
   background-color: #218838;
   /* Darker green on hover */
  }

  h1 {
   color: #333;
   /* Change the text color to a darker shade */
   text-align: center;
   /* Center the text */
   font-size: 48px;
   /* Increase font size for emphasis */
   text-transform: uppercase;
   /* Convert text to uppercase */
   font-family: 'Arial Black', sans-serif;
   /* Add a bold font */
   letter-spacing: 3px;
   /* Add letter spacing for emphasis */
   margin-bottom: 40px;
   /* Add margin bottom for spacing */
  }

  .error-message,
  .success-message {
   color: red;
   /* Red color for error messages */
   margin-top: 10px;
   /* Margin top for spacing */
   text-align: center;
  }

  .success-message {
   color: green;
  }
 </style>
</head>

<body>

 <div class="center-content">
  <h1>Reset Password</h1>

  <form id="resetPasswordForm" method="post" action="process-reset-password.php" onsubmit="return validateForm(event)">
   <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">

   <label for="password">New password</label>
   <input type="password" id="password" name="password" required>

   <label for="password_confirmation">Repeat password</label>
   <input type="password" id="password_confirmation" name="password_confirmation" required>

   <button type="submit">Send</button>
   <div id="errorMessage" class="error-message"></div>
   <div id="successMessage" class="success-message"></div>
   <button id="loginButton" class="login-button" style="display: none;" onclick="window.location.href='login.php';">Login</button>
  </form>
 </div>

 <script>
  function validateForm(event) {
   event.preventDefault();

   var password = document.getElementById("password").value;
   var confirmPassword = document.getElementById("password_confirmation").value;

   if (password !== confirmPassword) {
    document.getElementById("errorMessage").innerText = "Passwords do not match";
    document.getElementById("successMessage").innerText = "";
    document.getElementById("loginButton").style.display = "none";
    return false;
   }

   if (password.length < 4 || !/[a-zA-Z]/.test(password) || !/[0-9]/.test(password)) {
    document.getElementById("errorMessage").innerText = "Password must be at least 4 characters long and contain at least one letter and one number";
    document.getElementById("successMessage").innerText = "";
    document.getElementById("loginButton").style.display = "none";
    return false;
   }

   // If validation passes, submit form via AJAX
   var xhr = new XMLHttpRequest();
   xhr.open("POST", "process-reset-password.php", true);
   xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

   xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
     if (xhr.responseText.includes("Password updated")) {
      document.getElementById("successMessage").innerText = "Password updated. You can now login.";
      document.getElementById("errorMessage").innerText = "";
      document.getElementById("loginButton").style.display = "block";
     } else {
      document.getElementById("errorMessage").innerText = xhr.responseText;
      document.getElementById("successMessage").innerText = "";
      document.getElementById("loginButton").style.display = "none";
     }
    }
   };

   var params = "token=" + encodeURIComponent(document.querySelector('input[name="token"]').value) +
    "&password=" + encodeURIComponent(password) +
    "&password_confirmation=" + encodeURIComponent(confirmPassword);

   xhr.send(params);

   return false;
  }
 </script>

</body>

</html>