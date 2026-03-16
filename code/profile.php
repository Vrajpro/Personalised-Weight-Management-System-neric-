<?php
include "session_login_logout.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="styles.css"> <!-- Your custom CSS file -->
  <title>User Profile</title>
  <style>
    .navbar-nav .coll-link.link {
      margin-right: 35px;
    }

    .btn:hover {
      background-color: #5cd796;
    }

    .img {
      position: fixed;
      left: 6%;
      transform: translate(-50%, -50%);
      top: 86%;
      width: 280px;
      max-width: 280px;
      height: auto;
    }

    .img1 {
      position: fixed;
      left: 94%;
      transform: translate(-50%, -50%);
      top: 70%;
      width: 295px;
      max-width: 295px;
      height: auto;
    }
  </style>
  <script>
    $(document).ready(function() {
      // Load Profile Info by default on page load
      $.ajax({
        url: "personal_info.php",
        type: 'POST',
        success: function(result) {
          $('#output').html(result); // Show profile info by default
        }
      });

      // Load Profile Info when the button is clicked
      $('#personal_info').click(function(event) {
        $.ajax({
          url: "personal_info.php",
          type: 'POST',
          success: function(result) {
            $('#output').html(result); // Show profile info when clicked
          }
        });
      });

      // Load Account Settings when the button is clicked
      $('#account').click(function(event) {
        $.ajax({
          url: "account_settings.php",
          type: 'POST',
          success: function(result) {
            $('#output').html(result); // Show account settings when clicked
          }
        });
      });
    });
  </script>
</head>

<body>
  <form method="post">
    <div class="container-fluid">
      <nav class="navbar navbar-expand-lg navbar-light justify-content-between">
        <a class="navbar-brand" href="./home-page.php"><img src="./images/nericlogo.jpg" class="rounded-circle" style="width: 80px;"></a>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ">
            <li class="nav-item">
              <a class="navbar-brand coll-link btn ml-4" id='personal_info'>Personal Info</a>
            </li>
          </ul>
          <ul class="navbar-nav ">
            <li class="nav-item">
              <a class="navbar-brand coll-link btn ml-4" id='account'>Account Settings</a>
            </li>
          </ul>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="./home-page.php">
              <i class="fa fa-home" aria-hidden="true" style="font-size: 24px;"></i> Home
            </a>
          </li>
        </ul>
      </nav>
    </div>

    <div><img src="./images/bg.jpg" class='img'></div>
    <div><img src="./images/bg1.jpg" class='img1'></div>
    <div class="container">
      <div id="output">
      </div>
      <br><br>
      <?php
      $conn = mysqli_connect('localhost', 'root', '', 'neric');

      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }
      if (isset($_SESSION["p_user_id_db"])) {
        $p_user_id_db = $_SESSION["p_user_id_db"];

        $sql = "SELECT 
                ud.food_calories_db,
                ud.date_db, ud.food_name_db
                FROM userdetails_db s
                JOIN food ud ON s.id_db = ud.user_id_db 
                WHERE s.id_db = ? AND ud.date_db = CURDATE() ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $p_user_id_db);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $p_food_calories = $row['food_calories_db'];
              $p_consumption_data[] = [
                'calories_consume' => $row['food_calories_db'],
                'name_food' => $row['food_name_db']
              ];
            }
          }
        }
      }
      if (!isset($p_food_calories)) {
        echo "<h2 class='alert alert-warning text-center' style='border-radius:20px' role='alert'><strong>You haven't entered any food consumption calories.</strong></h2>";
      }

      $sql = "SELECT 
              ud.daily_burn_calories, ud.date
              FROM userdetails_db s
              JOIN daily_burn_calories ud ON s.id_db = ud.calories_user_id_db
              WHERE s.id_db = ? AND ud.date = CURDATE()";

      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $p_user_id_db);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result) {
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $p_burn_calories = $row['daily_burn_calories'];
            $p_daily_burn_calories[] = [
              'calories_burn' => $row['daily_burn_calories']
            ];
          }
        }
      }

      if (!isset($p_burn_calories)) {
        echo "<h2 class='alert alert-warning text-center' style='border-radius:20px' role='alert'><strong>You haven't entered any burned calories.</strong></h2>";
      }
      ?>

      <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
      <script type="text/javascript">
        google.charts.load("current", {
          packages: ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
          let data = google.visualization.arrayToDataTable([
            ["", "Calories", {
              role: "style"
            }],
            <?php
            foreach ($p_consumption_data as $data) {
              echo '["Consume Calories (+): ' . $data['name_food'] . '", ' . $data['calories_consume'] . ', "blue"],';
            }
            foreach ($p_daily_burn_calories as $value) {
              echo '["Burn Calories (-):", ' . $value['calories_burn'] . ', "red"],';
            }
            ?>
          ]);

          let view = new google.visualization.DataView(data);
          view.setColumns([0, 1,
            {
              calc: "stringify",
              sourceColumn: 1,
              type: "string",
              role: "annotation"
            },
            2
          ]);

          let options = {
            title: "Your Activity",
            width: 900,
            height: 400,
            bar: {
              groupWidth: "92%"
            },
            legend: {
              position: "none"
            },
          };
          var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
          chart.draw(view, options);
        }
      </script>
      <div id="columnchart_values" class="mt-5 table-responsive" style="width: 900px; height: 500px;"></div>

      <?php
      // Fetch nutrition summary data for the last week
      $sql = "SELECT 
      DATE(date_recorded) AS date_recorded,
      SUM(total_calories) AS total_calories,
      SUM(total_carbs) AS total_carbs,
      SUM(total_protein) AS total_protein,
      SUM(total_fat) AS total_fats  -- Use the correct column name
    FROM nutrition_summary 
    WHERE user_id = ?
    AND date_recorded >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY DATE(date_recorded)";

      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $p_user_id_db);
      $stmt->execute();
      $result = $stmt->get_result();

      $total_nutrition = [
        'calories' => 0,
        'carbs' => 0,
        'protein' => 0,
        'fats' => 0,
      ];

      while ($row = $result->fetch_assoc()) {
        $total_nutrition['calories'] += $row['total_calories'];
        $total_nutrition['carbs'] += $row['total_carbs'];
        $total_nutrition['protein'] += $row['total_protein'];
        $total_nutrition['fats'] += $row['total_fats'];
      }

      // Nutrition Summary Display
      echo "<div class='alert alert-info mt-5'>
        <h4 class='alert-heading'>Nutrition Summary (Last Week)</h4>
        <p>Total Calories: {$total_nutrition['calories']} kcal</p>
        <p>Total Carbs: {$total_nutrition['carbs']} g</p>
        <p>Total Protein: {$total_nutrition['protein']} g</p>
        <p>Total Fats: {$total_nutrition['fats']} g</p>
      </div>";
      ?>

        <script type="text/javascript">
          google.charts.load("current", {
            packages: ['corechart']
          });
          google.charts.setOnLoadCallback(drawNutritionChart);

          function drawNutritionChart() {
            let data = google.visualization.arrayToDataTable([
              ['Nutrient', 'Amount', { role: 'style' }],
              ['Calories', <?= $total_nutrition['calories'] ?>, 'color: #4285F4'],
              ['Carbs', <?= $total_nutrition['carbs'] ?>, 'color: #DB4437'],
              ['Protein', <?= $total_nutrition['protein'] ?>, 'color: #F4B400'],
              ['Fats', <?= $total_nutrition['fats'] ?>, 'color: #0F9D58']
            ]);

            let options = {
              title: 'Nutrition Summary (Last Week)',
              width: 600,
              height: 400,
              bar: { groupWidth: '60%' },
              legend: { position: 'none' },
              hAxis: {
                title: 'Nutrient',
              },
              vAxis: {
                title: 'Amount (g/kcal)',
              }
            };

            let chart = new google.visualization.ColumnChart(document.getElementById('nutrition_summary_chart'));
            chart.draw(data, options);
          }
        </script>

      <div id="nutrition_summary_chart" class="mt-5" style="width: 600px; height: 400px;"></div>
    </div>
</form>
</body>

</html>
