<?php

include "../session_login_logout.php";


?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  <title>Suggestion</title>

  <style>
    body {
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      padding: 2rem 0;
    }

    .metrics-container {
      max-width: 500px;
      margin: 2rem auto;
      padding: 1rem;
      font-family: system-ui, -apple-system, sans-serif;
    }

    .metric-card {
      padding: 1.5rem;
      border-radius: 1rem;
      color: white;
      margin-bottom: 1.5rem;
      transition: transform 0.2s ease;
      cursor: pointer;
    }

    .metric-card:hover {
      transform: scale(1.02);
    }

    .bmi-card {
      background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
      box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
    }

    .calories-card {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .calories-card :hover {
      transform: scale(1.02);
    }

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
    }

    .card-title {
      font-size: 1.25rem;
      font-weight: 600;
      margin: 0;
    }

    .card-icon {
      width: 24px;
      height: 24px;
    }

    .metric-value {
      text-align: center;
      font-size: 2.5rem;
      font-weight: 700;
      margin: 0.5rem 0;
    }

    .metric-label {
      text-align: center;
      font-size: 0.875rem;
      opacity: 0.9;
    }

    .calories-card {
      background: linear-gradient(135deg, #34d399 0%, #10b981 50%, #059669 100%);
      border-radius: 1rem;
      padding: 1.5rem;
      color: white;
      text-align: center;
      transition: transform 0.2s ease;
      box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
      cursor: pointer;
    }

    .calories-card:hover {
      transform: scale(1.02);

    }

    .card-header {
      margin-bottom: 1.5rem;
    }

    .card-title {
      font-size: 1.25rem;
      font-weight: 600;
      margin: 0;
    }

    .circular-progress {
      position: relative;
      width: 210px;
      height: 210px;
      margin: 1rem auto;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .circular-progress svg {
      position: absolute;
      width: 100%;
      height: 100%;
      transform: rotate(-90deg);
    }

    .circle-bg {
      fill: none;
      stroke: rgba(255, 255, 255, 0.2);
      stroke-width: 12;
    }

    .circle-progress {
      fill: none;
      stroke: lightgreen;
      stroke-width: 12;
      stroke-linecap: round;
      stroke-dasharray: 566.4;
      /* Circumference of circle (2 * PI * r) */

    }

    .progress-value {
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
    }

    .calories-value {
      font-size: 2.5rem;
      font-weight: 700;
      margin: 0;
      line-height: 1;
    }

    .calories-label {
      font-size: 0.875rem;
      opacity: 0.9;
      margin-top: 0.5rem;
    }

    @media (max-width: 768px) {
      .metrics-container {
        flex-direction: column;
      }

      .metric-card {
        margin-bottom: 1.5rem;
      }
    }
  </style>
</head>

<body>
  <?php
  include "../config.php";


  $email = $_SESSION['email'];


  echo "<div style='display: flex; justify-content: center; align-items: center; margin-top: 1px;'>";
  echo "<div class='alert alert-light alert-dismissible fade show w-75' style='border-radius: 15px;' role='alert'>";
  echo "   <h3 class='alert-heading'> <strong class='text-dark'>Suggestion based on your details</strong></h3> <br>";
  $sql = "SELECT ud.select_goal,ud.height_db,ud.weight_db,ud.name_db,ud.weekly_user_routine_db
  FROM signup s
  JOIN userdetails_db ud ON s.id_db = ud.id_db
  WHERE s.email_db = ?";

  $stmt = $conn->prepare($sql);

  $stmt->bind_param("s", $email);

  $stmt->execute();

  $result = $stmt->get_result();

  if ($result) {
    if ($result->num_rows > 0) {
      // Fetch the name from the result set
      $row = $result->fetch_assoc();
      $p_user_goal = $row["select_goal"];
      $p_name = $row["name_db"];
      $height_meters = $row["height_db"] * 0.3048;
      $weight = $row["weight_db"];
      $p_weakly = $row["weekly_user_routine_db"];

      echo "<div class='table-responsive'>";
      echo "<table class='table bmi-table'>";
      echo "<thead><tr><th>BMI Range</th><th>Classification</th></tr></thead>";
      echo "<tbody>
     <tr>
       <td>Below 18.5</td>
       <td>Underweight</td>
     </tr>
     <tr>
       <td>18.5 - 24.9</td>
       <td>Normal weight</td>
     </tr>
     <tr>
       <td>25.0 - 29.9</td>
       <td>Overweight</td>
     </tr>
     <tr>
       <td>30.0 - 34.9</td>
       <td>Obese (Class 1)</td>
     </tr>
     <tr>
       <td>35.0 - 39.9</td>
       <td>Obese (Class 2)</td>
     </tr>
     <tr>
       <td>Above 40.0</td>
       <td>Obese (Class 3)</td>
     </tr>
   </tbody>
 </table><label>Referencess: <a href='https://www.ncbi.nlm.nih.gov/books/NBK535456/'  target='_blank'>NCBI Atricle</label></a>";

      $bmi = $weight / ($height_meters * $height_meters);
      $p_user_calories_goal = $_SESSION["user_calories_goal"];
      echo ' <div class="metrics-container">
        <div class="metric-card bmi-card">
            <div class="card-header">
                <h2 class="card-title">Current BMI</h2>
                
            </div>
         <div class="metric-value" id="bmiValue"> ' . round($bmi, 2) . '</div>' .  '<div class="bmi-category" id="bmiCategory"></div>
         </div></div><hr style = "border: 1px solid black;">';
      echo '
      <h5 >The below Daily Food Intake  is calulated on the basis of your Body Mass Index (BMI), Gender, DOB, Phyiscal Daily Habits  and also various scientific methods based on BMI. Hence  calculation of your daily food intake for ' . $p_user_goal . ' is done on the basis of your data.</h5>
         <div class="metrics-container">
        <div class="calories-card">
            <div class="card-header">
                <h2 class="card-title">Daily Calories Goal ( Daily Food Intake)</h2>
            </div>
            <div class="circular-progress">
                <svg>
                    <circle class="circle-bg" cx="100" cy="100" r="90"></circle>
                    <circle class="circle-progress" cx="100" cy="100" r="90"></circle>
                </svg>
                <div class="progress-value">';

      echo ' <div class="calories-value" id="caloriesValue">' . $p_user_calories_goal . '</div>
        <div class="calories-label">calories intake per day</div>
    
      </div></div>
  </div>
  </div>
</div>';

      if ($bmi < 18.5 && $p_user_goal == "gain_weight") {
        echo "<p>You can only gain or maintain weight; you cannot lose weight because your BMI falls in the underweight category.</p>";
        echo '<p class="amita-regular text-center">But ' . $p_name .
          ", you selected to " . $p_user_goal .
          ",<br> And daily calorie intake is below. As per your choice, " .
          $p_weakly . " grams is usually recommended to gain weight per week.</p>";
      } elseif ($bmi >= 18.5 && $bmi <= 24.99) {
        echo '<h5 class="amita-regular text-center text-grey">' . $p_name . ',your BMI falls in the healthy range, you are eligible for Weight Gain, Maintain Weight, or Loss Weight.</h5>';

        if ($p_user_goal == "gain_weight" && $p_weakly == 227) {
          echo '<p class="amita-regular text-center">But ' . $p_name .
            ", you selected to " . $p_user_goal .
            ",<br> And daily calorie intake is below. As per your choice, " .
            $p_weakly . " grams is usually recommended to gain weight per week.</p>";
        } elseif ($p_user_goal == "loss_weight" && $p_weakly == 340) {
          echo '<p class="amita-regular text-center">But ' . $p_name .
            ", you selected to " . $p_user_goal .
            ",<br> And daily calorie intake is appropriate, as " .
            $p_weakly . " grams is usually recommended to lose weight per week.</p>";
        } elseif ($p_user_goal == "maintain_weight") {
          echo '<p class="amita-regular text-center">But ' . $p_name .
            ", you selected to " . $p_user_goal .
            ",<br> And daily calorie intake aligns with maintaining your weight.</p>";
        }
      } elseif ($bmi >= 25 && $bmi <= 29.99) {
        echo '<h5 class=" text-center text-grey  ">Since your BMI falls in the overweight category, you are eligible for Weight Loss or Maintain Weight goals.</h5>';

        if ($p_user_goal == "loss_weight" && $p_weakly == 340) {
          echo '<p class="amita-regular text-center">But ' . $p_name .
            ", you selected to " . $p_user_goal .
            ",<br> And daily calorie intake is in line with your goal to lose weight by " .
            $p_weakly . " grams per week.</p>";
        } elseif ($p_user_goal == "maintain_weight") {
          echo '<p class="amita-regular text-center">But ' . $p_name .
            ", you selected to " . $p_user_goal .
            ",<br> And daily calorie intake is suitable for maintaining weight.</p>";
        }
      }
      // Obese category
      else {
        echo '<h2 class="amita-regular text-center text-white">You are eligible for Weight Loss as your BMI falls in the obesity category.</h2>';
      }

      $_SESSION["suggestion_shown_date"] = date("Y-m-d");

      echo '  </div> </div> </div></div>';
    }
  }

  ?>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>