<style>
  .navbar {
    background: rgba(128, 128, 128, 0.0);
    /* Gray with 50% transparency */
    backdrop-filter: blur(10px);
    /* Increase blur for more effect */
    -webkit-backdrop-filter: blur(90px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 0 0 70px 70px;
    transition: background 0.3s ease-out;
    padding: 5px 10px;
    max-width: 980px;
    margin: auto;
  }

  .navbar .navbar-brand img {
    border-radius: 50%;
    width: 50px;
    /* Adjusted size */
    transition: transform 0.3s;
    margin-left: 20px;
  }

  .navbar .navbar-brand img:hover {
    transform: scale(1.1);
  }

  .nav-link {
    color: #333;
    transition: background-color 0.3s, color 0.3s, transform 0.3s;
    margin: 0 8px;
    /* Slightly reduced margin */
  }

  .nav-link:hover {
    background-color: rgba(144, 238, 144, 0.2);
    border-radius: 50px;
    transform: scale(1.05);
  }

  .btn {
    background-color: rgba(144, 238, 144, 0.15);
    border: none;
    color: #333;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    transition: background-color 0.3s, transform 0.3s;
  }

  .btn-primary {
    background-color: rgba(144, 238, 144, 0.3);
  }

  .btn-primary:hover {
    background-color: rgba(144, 238, 144, 0.5);
    transform: scale(1.05);
  }

  .profile-dropdown-menu {
    background: linear-gradient(135deg, rgba(144, 238, 144, 0.2), rgba(144, 238, 144, 0.15));
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .dropdown-item {
    color: #333;
    transition: background-color 0.3s, transform 0.3s;
  }

  .dropdown-item:hover {
    background-color: rgba(144, 238, 144, 0.3);
    transform: scale(1.05);
  }

  .alert {
    background-color: rgba(255, 0, 0, 0.7);
    color: #fff;
    border: none;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 5px;
    padding: 10px 20px;
  }
</style>




<header>
  <div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top justify-content-center">
      <!-- Navbar logo -->
      <a class="navbar-brand">
        <img src="./images/nericlogo.jpg" class="rounded-circle" style="width: 70px;" alt="Logo">
      </a>

      <?php
      $email = $_SESSION["email"];

      class calculation_duration_goal
      {
        function __construct()
        {
          $p_streak = 1;
          try {
            $p_current_date = date('Y-m-d');
            include './config.php';

            $sql = "SELECT          
              ud.id_db,
              ud.weight_db, ud.physical_activity_level_db, ud.start_streak_db,
              ud.select_goal, ud.weekly_user_routine_db, goal_weight_db
              FROM signup s
              JOIN userdetails_db ud ON s.id_db = ud.id_db
              WHERE s.email_db = ?";
            global $email;
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
              $row = $result->fetch_assoc();
              $p_user_goal = $row['select_goal'];
              $p_user_current_weight = $row['weight_db'];
              $p_goal_weight = $row['goal_weight_db'];
              $p_user_weekly_rountine = $row['weekly_user_routine_db'];
              $p_start_streak = $row['start_streak_db'];
              $p_date1 = new DateTime($p_start_streak);
              $p_date2 = new DateTime($p_current_date);
              $interval = $p_date2->diff($p_date1);
              $p_days_passed = $interval->days;

              if ($p_user_goal == 'maintain_weight') {
                $p_streak += $p_days_passed;
                echo '<button type="button" class="btn btn-primary ml-3" name="track" data-toggle="popover" data-placement="right" data-content="It is a daily countdown to track how many days you are maintaining weight.">
                  Streak #' . $p_streak . '</button>';
              } elseif ($p_user_goal == 'gain_weight') {
                echo '<button type="button" class="btn btn-primary ml-3" name="track" data-toggle="popover" data-placement="right" data-content="It is a daily countdown to track your progress towards your desirable goal.">
                  Streak #';
                $p_user_desire_weight = $p_goal_weight - $p_user_current_weight;
                if ($p_user_weekly_rountine == "227") {
                  $p_weekly_duration = round($p_user_desire_weight / 0.227, 2);
                  $p_day_duration = round($p_weekly_duration * 7, 2);
                  if ($p_days_passed <= $p_day_duration) {
                    $p_streak += $p_days_passed;
                  }
                  echo $p_streak . ' </button>';
                } else {
                  $p_weekly_duration = $p_user_desire_weight / 0.453;
                  $p_day_duration = $p_weekly_duration * 7;
                  if ($p_days_passed <= $p_day_duration) {
                    $p_streak += $p_days_passed;
                  }
                  echo $p_streak . ' </button>';
                }
              } elseif ($p_user_goal == 'loss_weight') {
                echo '<button type="button" class="btn btn-primary ml-3" name="track" data-toggle="popover" data-placement="right" data-content="It is a daily countdown to track your progress towards your desirable goal.">
                  Streak #</button>';
              }
            }
          } catch (Exception $e) {
            echo "<div class='alert alert-danger' role='alert'><h3>Something Went Wrong!</h3></div>";
          }
        }
      }
      $p_duration = new calculation_duration_goal();
      ?>

      <!-- Navbar toggler button for mobile view -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navbar links -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="./partials/suggestion.php">Suggestion</a>
          </li>
          <?php $p_home_page = (basename($_SERVER['PHP_SELF']) === "home-page.php");
          if (!$p_home_page) {
            echo '  <li class="nav-item">' .
              ' <a class="nav-link" href="./home-page.php">Home</a>' .
              ' </li> ';
          } ?>
          <?php $p_food_journal = (basename($_SERVER['PHP_SELF']) === "food_journal.php");
          if (!$p_food_journal) {

            echo ' <li class="nav-item">' .
              ' <a class="nav-link" href="./food_journal.php">Food Journal</a>' .
              ' </li>';
          }
          ?>
          <li class="nav-item">
            <a class="nav-link" href="./dietsplans/diet.php">Diet</a>
          </li>
          <?php $p_burn_calories = (basename($_SERVER['PHP_SELF']) === "burn_calories.php");
          if (!$p_burn_calories) {
            echo '
          <li class="nav-item">
            <a class="nav-link" href="./burn_calories.php">Burn Calories</a>
          </li>';
          }
          ?>
          <li class="nav-item">
            <a class="nav-link" href="./food_data.php">Food Data</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-user" aria-hidden="true"></i> &nbsp; Profile
            </a>
            <div class="dropdown-menu profile-dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="./profile.php">View Profile</a>

              <form method="post" action="session_login_logout.php" style="display: none;" id="logout-form">
                <input type="hidden" name="logout" value="true">
              </form>

              <button class="dropdown-item" type="button" onclick="document.getElementById('logout-form').submit();">Logout</button>
            </div>
          </li>
        </ul>
      </div>
    </nav>
  </div>
</header>