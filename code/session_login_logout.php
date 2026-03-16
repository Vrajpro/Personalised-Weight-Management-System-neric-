<?php
class  Login_Logout
{
  function processLoginLogout()
  {
    session_start();
    try {
      if (!isset($_SESSION['visited'])) {

        $_SESSION['visited'] = true;
        header('Location: index.php');
        exit();
      } else if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
        header('Location:index.php');
        exit();
      } else {
        if (isset($_POST['logout'])) {
          unset($_SESSION['email']);
          $_SESSION["loggedin"] = true;
          // unset($_SESSION["loggedin"]);

          session_destroy();
          session_write_close();
          header('location:index.php');
          exit();
        }
      }
    } catch (Exception $e) {
      echo "Something Went Wrong";
    }
  }
}
$login_logout = new Login_Logout();
$login_logout->processLoginLogout();
