<style>
.navbar {
      background-color: rgba(56, 94, 65, 0.1);
      backdrop-filter: blur(10px);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: background 0.3s ease-out; /* Faster transition for background */
      padding: 12px;
    }

</style>

<script>
    $(document).ready(function () {
      <?php
        // Check if the user is logged in (You need to implement this logic)
        $loggedIn = false; // Example: Set this to true if user is logged in
        if($loggedIn) {
          echo 'showLoggedInMenu();';
        } else {
          echo 'showLoggedOutMenu();';
        }
      ?>

      function showLoggedInMenu() {
        $("#profileDropdown").css("opacity", "1");
        $("#loginLink").css("opacity", "0");
      }

      function showLoggedOutMenu() {
        $("#profileDropdown").css("opacity", "0");
        $("#loginLink").css("opacity", "1");
      }
    });
  </script>

<header>
    <div class="container-fluid">
      <nav class="navbar navbar-expand-lg navbar-light bg-muted fixed-top justify-content-center">
        <a class="navbar-brand"><img src="./images/nericlogo.jpg" class="rounded-circle"
            style="width: 70px;"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse bg-black-50" id="navbarNav">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown" id="profileDropdown" style="opacity: 0;">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                &nbsp;
                <i class="fa fa-user" aria-hidden="true"></i>
              </a>
              
            </li>
            <li class="nav-item">
              <a class="nav-link food-calorie-link" href="./bmi_calculator.php">Bmi calculator</a>
            </li>
            <li class="nav-item">
              <a class="nav-link food-calorie-link" href="./general_food_data.php">Food Nutritional Data</a>
            </li>
            <li class="nav-item" id="loginLink" style="opacity: 0;">
              <a class="nav-link " href="./login.php">Login</a>
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </header>