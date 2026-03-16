<?php
session_start();

if (isset($_POST["logout"]) && isset($_SESSION["admin_loggedin"])) {

  session_unset();

  session_destroy();

  header("location: ../index.php");
  exit();
}
if (!isset($_SESSION["visited"])) {
  $_SESSION["visited"] = true;
  header("Location: index.php");
  exit();
}

if (
  !isset($_SESSION["admin_loggedin"])

) {
  header("Location:login.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Statistics</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    /* Sidebar */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      width: 250px;
      background-color: #343a40;
      color: #fff;
      padding-top: 56px;
      overflow-y: auto;
    }

    .sidebar-brand {
      font-size: 1.5rem;
      font-weight: bold;
      text-align: center;
      margin-bottom: 20px;
    }

    .sidebar-brand-icon {
      font-size: 1.75rem;
      margin-right: 5px;
    }

    .sidebar-brand-text {
      font-size: 1rem;
    }

    .nav-item {
      padding: 0.5rem 1rem;
    }

    .nav-item:hover {
      background-color: #495057;
    }

    .nav-link {
      color: #fff;
    }

    /* End of Sidebar */

    /* Content Wrapper */
    #content-wrapper {
      margin-left: 250px;
      padding: 20px;
    }

    /* Total Users Box */
    .total-users {
      background-color: #f8f9fa;
      padding: 20px;
      border-radius: 5px;
      margin-bottom: 20px;
    }

    /* Chart Container */
    .chart-container {
      margin-top: 20px;
      padding: 20px;
      background-color: #fff;
      border-radius: 5px;
    }

    /* Medium Size Chart */
    .chart {
      max-width: 400px;
      max-height: 400px;
      margin: 0 auto;
      display: block;
    }
  </style>
</head>

<body>
  <form method="post" id="myForm">
    <!-- Sidebar -->
    <div class="sidebar">
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="./home-page.php">
        <img src="./images/nericlogo.jpg" alt="Logo" class="rounded-circle" width="90" height="90">
      </a>
      <hr class="sidebar-divider my-0">
      <!-- Nav Item - Dashboard -->
      <ul class="nav flex-column">
        <li class="nav-item active">
          <a class="nav-link" href="./admin_panel.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <!-- Nav Item - Charts -->
        <li class="nav-item">
          <a class="nav-link" href="">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Statistics</span>
          </a>

      </ul><br>

      <!-- Logout Button -->
      <div class="logout-button ml-5 mt-3">
        <form method="post">
          <button class="btn btn-danger" name="logout">Logout</button>
        </form>
      </div>
      <!-- End of Logout Button -->
    </div>

    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper">
      <!-- Main Content -->
      <div id="content">
        <!-- Middle Content -->
        <div class="container-fluid">
          <div class="middle-content">
            <div class="total-users">
              <h2>Total Login Users</h2>
              <div class="user-count">
                <p>Currently, there are <strong><?php if (isset($_SESSION["user"])) {
                                                  echo $_SESSION["user"];
                                                } ?></strong> users logged in.</p>
              </div>
            </div>
          </div>

          <!-- Navigation links -->
          <ul class="nav bg-dark">
            <li class="nav-item">
              <a class="nav-link active " href="#" id="age-link">Age</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" id="gender-link">Gender</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" id="routine-link">Routine</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" id="disease-link">Disease</a>
            </li>
          </ul>

          <!-- Container for the charts -->
          <div id="age-chart-container" class="chart-container">
            <canvas id="age-chart" class="chart" width="800" height="800"></canvas>
          </div>
          <div id="gender-chart-container" class="chart-container" style="display: none;">
            <canvas id="gender-chart" class="chart" width="600" height="600"></canvas>
          </div>
          <div id="routine-chart-container" class="chart-container" style="display: none;">
            <canvas id="routine-chart" class="chart" width="600" height="600"></canvas>
          </div>
          <div id="disease-chart-container" class="chart-container" style="display: none;">
            <canvas id="disease-chart" class="chart" width="600" height="600"></canvas>
          </div>
          <!-- End of Container Fluid -->
        </div>
        <!-- End of Page Content -->
      </div>
      <!-- End of Content Wrapper -->
    </div>
  </form>
  <?php
  try {
   
    include '../config.php';

    $select = "SELECT * from userdetails_db ";

    $sql = mysqli_query($conn, $select);

    if (!$sql) {
      echo "Unable to fetch";
    }

    $p_total_teenage = 0;
    $p_total_adults = 0;
    $p_total_middle_age_adults = 0;
    $p_total_olds = 0;
    $p_total_males = 0;
    $p_total_females = 0;
    $p_total_others = 0;
    $p_very_active_level = 0;
    $p_lightly_active_level = 0;
    $p_moderate_active_level = 0;
    $p_total_pre_existed_condition = 0;
    $p_total_allergies = 0;
    if ($sql->num_rows > 0) {
      function age_calculation(
        $p_user_dob,
        &$p_total_teenage,
        &$p_total_adults,
        &$p_total_middle_age_adults,
        &$p_total_olds,
        &$p_gender,
        &$p_total_males,
        &$p_total_females,
        &$p_total_others,
        &$p_physical_activity,
        &$p_very_active_level,
        &$p_lightly_active_level,
        &$p_moderate_active_level,
        &$p_pre_existing_condition,
        &$p_total_pre_existed_condition,
        &$p_allergy,
        &$p_total_allergies
      ) {
        $birthdate = new DateTime($p_user_dob);
        $today = new DateTime("today");
        $p_age = $birthdate->diff($today)->y;
        //echo "<center>Date of Birth:" .  $p_age . "</center><br>";

        if ($p_age >= 16 && $p_age <= 19) {
          $p_total_teenage += 1;
        } elseif ($p_age >= 20 && $p_age <= 30) {
          $p_total_adults += 1;
        } elseif ($p_age >= 31 && $p_age <= 50) {
          $p_total_middle_age_adults += 1;
        } elseif ($p_age >= 51 && $p_age <= 65) {
          $p_total_olds += 1;
        }


        if ($p_gender == "male") {
          $p_total_males += 1;
        } elseif ($p_gender == "female") {
          $p_total_females += 1;
        } else {
          $p_total_others += 1;
        }



        if ($p_physical_activity == "very_active") {

          $p_very_active_level += 1;
        } elseif ($p_physical_activity == "lightly_active") {
          $p_lightly_active_level += 1;
        } elseif ($p_physical_activity == "moderately_active") {
          $p_moderate_active_level += 1;
        }



        if (!empty($p_pre_existing_condition) || strtolower($p_pre_existing_condition) == 'none' || strtolower($p_pre_existing_condition == 'NO')) {

          $p_total_pre_existed_condition += 1;
        }



        if (!empty($p_allergy) || strtolower($p_pre_existing_condition) == 'none' || strtolower($p_pre_existing_condition == 'NO')) {

          $p_total_allergies += 1;
        }
      }

      while ($row = $sql->fetch_assoc()) {
        $p_user_dob = $row["date_of_birth_db"];
        $p_gender = $row["gender_db"];
        $p_physical_activity = $row["physical_activity_level_db"];
        $p_pre_existing_condition = $row["pre_existing_conditions_db"];
        $p_allergy = $row["allergies_db"];
        age_calculation(
          $p_user_dob,
          $p_total_teenage,
          $p_total_adults,
          $p_total_middle_age_adults,
          $p_total_olds,
          $p_gender,
          $p_total_males,
          $p_total_females,
          $p_total_others,
          $p_physical_activity,
          $p_very_active_level,
          $p_lightly_active_level,
          $p_moderate_active_level,
          $p_pre_existing_condition,
          $p_total_pre_existed_condition,
          $p_allergy,
          $p_total_allergies
        );
        // 	if(empty($p_pre_existing_condition)){
        // 		echo "<center>condition empty<br>" ;}
        // 		else{
        // 			echo $p_pre_existing_condition;
        // 		} 
      }
      // echo "<center>Total Teenagers:$p_total_teenage </center>";
      // echo "<center>Total Adults:$p_total_adults  </center>";
      // echo "<center>Total Males:$p_total_males</center>";
      // echo "<center>Total Females:$p_total_females</center>";
      // echo "<center>Total others:$p_total_others</center>";
      // echo "<center>Total very active:	$p_very_active_level</center>";
      // echo "<center>Total lightly active:$p_lightly_active_level</center>";
      // echo "<center>Total middle:$p_total_middle_age_adults </center>";

    } else {
      echo "Not Found";
    }
  } catch (Exception $e) {
    echo "<div class='alert alert-danger' role='alert'><h3>Something Went Wrong!</h3></div>";
  }

  $conn->close();
  ?>

  <script>
    $(document).ready(function() {
      // Function to generate age data distribution
      function generateAgeDistribution() {
        // Define age groups and their corresponding counts
        var ageGroups = [
          ' Teenage (16-19) :<?php echo $p_total_teenage ?> ',
          ' Adult (20-30)  :<?php echo $p_total_adults ?>',
          ' Mid-Adult (31-50) :<?php echo $p_total_middle_age_adults ?>',
          ' Old (51-65) :<?php echo $p_total_olds ?>'
        ];
        var ageCounts = [<?php echo $p_total_teenage ?>, <?php echo $p_total_adults ?>,
          <?php echo $p_total_middle_age_adults ?>, <?php echo $p_total_olds ?>
        ];
        // Calculate counts for each age group based on the specified age range
        for (var age =
            16; age <=
          65; age++) {
          if (age >= 16 &&
            age <= 19) {
            ageCounts[
              0]++; // Increment Teenage count
          } else if (age >=
            20 && age <=
            39) {
            ageCounts[
              1]++; // Increment Adult count
          } else if (age >=
            40 && age <=
            50) {
            ageCounts[
              2]++; // Increment Mid-Adult count
          } else if (age >=
            51 && age <=
            65) {
            ageCounts[
              3]++; // Increment Old count (decreased range)
          }
        }

        return {
          labels: ageGroups,
          data: ageCounts
        };
      }

      // Function to generate sample age data
      function generateSampleAgeData() {
        var ageDistribution =
          generateAgeDistribution();

        return {
          labels: ageDistribution
            .labels,
          datasets: [{
            label: 'Age Distribution',
            backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)'
            ],
            borderColor: [
              'rgba(255, 99, 132, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1,
            data: ageDistribution
              .data,
          }]
        };
      }

      // Function to load age chart data
      function loadAgeChart() {
        var ageData =
          generateSampleAgeData();
        loadChart('age-chart',
          ageData);
      }

      // Load the age chart by default
      loadAgeChart();

      // Event listener for clicking on the age link
      $('#age-link').on('click',
        function() {
          // Show age chart
          $('#age-chart-container')
            .show();
          // Hide gender chart
          $('#gender-chart-container')
            .hide();
          // Hide routine chart
          $('#routine-chart-container')
            .hide();
          $('#disease-chart-container')
            .hide();
        });

      // Function to generate gender data distribution
      function generateGenderDistribution() {
        // Define gender categories and their corresponding counts
        var genders = ['Males <?php echo $p_total_males ?>',
          'Females <?php echo $p_total_females ?>',
          'Others <?php echo $p_total_others ?>'
        ];
        var genderCounts = [
          <?php echo $p_total_males ?>, <?php echo $p_total_females ?>, <?php echo $p_total_others ?>
        ];
        return {
          labels: genders,
          data: genderCounts
        };
      }

      // Function to generate sample gender data
      function generateSampleGenderData() {
        var genderDistribution =
          generateGenderDistribution();

        return {
          labels: genderDistribution
            .labels,
          datasets: [{
            label: 'Gender Distribution',
            backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)'
            ],
            borderColor: [
              'rgba(255, 99, 132, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)'
            ],
            borderWidth: 1,
            data: genderDistribution
              .data,
          }]
        };
      }

      // Function to load gender chart data
      function loadGenderChart() {
        var genderData =
          generateSampleGenderData();
        loadChart(
          'gender-chart',
          genderData);
      }

      // Event listener for clicking on the gender link
      $('#gender-link').on(
        'click',
        function() {
          // Show gender chart
          $('#gender-chart-container')
            .show();
          // Hide age chart
          $('#age-chart-container')
            .hide();
          // Hide routine chart
          $('#routine-chart-container')
            .hide();
          $('#disease-chart-container')
            .hide();
          // Load gender chart data
          loadGenderChart
            ();
        });

      // Function to generate routine data distribution
      function generateRoutineDistribution() {
        // Define routine categories and their corresponding counts
        var routines = [

          'Very Active : <?php echo $p_very_active_level ?>',
          'Moderate :<?php echo $p_moderate_active_level ?>',
          'Light Active :<?php echo $p_lightly_active_level ?>'
        ];
        var routineCounts = [
          <?php echo $p_very_active_level ?>, <?php echo $p_moderate_active_level ?>, <?php echo $p_lightly_active_level ?>
        ]; // Sample data

        return {
          labels: routines,
          data: routineCounts
        };
      }

      // Function to generate sample routine data
      function generateSampleRoutineData() {
        var routineDistribution =
          generateRoutineDistribution();

        return {
          labels: routineDistribution
            .labels,
          datasets: [{
            label: 'Routine Activity Distribution',
            backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)'
            ],
            borderColor: [
              'rgba(255, 99, 132, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1,
            data: routineDistribution
              .data,
          }]
        };
      }

      // Function to load routine chart data
      function loadRoutineChart() {
        var routineData =
          generateSampleRoutineData();
        loadChart(
          'routine-chart',
          routineData);
      }

      // Event listener for clicking on the routine link
      $('#routine-link').on(
        'click',
        function() {
          // Show routine chart
          $('#routine-chart-container')
            .show();
          // Hide age chart
          $('#age-chart-container')
            .hide();
          // Hide gender chart
          $('#gender-chart-container')
            .hide();
          $('#disease-chart-container')
            .hide();
          // Load routine chart data
          loadRoutineChart
            ();
        });
      // Function to generate disease data distribution
      function generateDiseaseDistribution() {
        // Define disease categories and their corresponding counts
        var diseases = [
          'Pre-existing Condition :   <?php echo $p_total_pre_existed_condition ?>',
          'Allergy :<?php echo $p_total_allergies ?>'
        ];
        var diseaseCounts = [
          <?php echo $p_total_pre_existed_condition ?>, <?php echo $p_total_allergies ?>
        ]; // Sample data

        return {
          labels: diseases,
          data: diseaseCounts
        };
      }

      // Function to generate sample disease data
      function generateSampleDiseaseData() {
        var diseaseDistribution =
          generateDiseaseDistribution();

        return {
          labels: diseaseDistribution
            .labels,
          datasets: [{
            label: 'Disease Distribution',
            backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(54, 162, 235, 0.2)'
            ],
            borderColor: [
              'rgba(255, 99, 132, 1)',
              'rgba(54, 162, 235, 1)'
            ],
            borderWidth: 1,
            data: diseaseDistribution
              .data,
          }]
        };
      }

      // Function to load disease chart data
      function loadDiseaseChart() {
        var diseaseData =
          generateSampleDiseaseData();
        loadChart(
          'disease-chart',
          diseaseData);
      }

      // Event listener for clicking on the disease link
      $('#disease-link').on(
        'click',
        function() {
          // Show disease chart
          $('#disease-chart-container')
            .show();
          // Hide age chart
          $('#age-chart-container')
            .hide();
          // Hide gender chart
          $('#gender-chart-container')
            .hide();
          // Hide routine chart
          $('#routine-chart-container')
            .hide();
          // Load disease chart data
          loadDiseaseChart
            ();
        });

      // Function to load chart data and create chart
      function loadChart(
        chartId, chartData) {
        var ctx = document
          .getElementById(
            chartId)
          .getContext('2d');
        new Chart(ctx, {
          type: 'bar',
          data: chartData,
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true
                }
              }]
            }
          }
        });
      }

    });
  </script>

</body>

</html>