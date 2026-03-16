<?php
include 'session_login_logout.php';
?>
<!doctype html>
<html lang="en">

<head>
 <!-- Required meta tags -->
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

 <!-- Bootstrap CSS -->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
 <!-- Add Bootstrap CSS for styling -->
 <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
 <!-- Add Font Awesome for icons -->
 <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
 <title>Burn Calories</title>
 <style>
  .navbar {
   background-color: rgba(56, 94, 65, 0.1);
   /* Dark green color with faint blur */
   backdrop-filter: blur(10px);
   box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .cover-box>* {
   flex: 1;
  }

  .cover-box {
   padding: 20px;
   margin-top: 120px;
   border-radius: 20px;
   display: flex;
   justify-content: center;
   align-items: center;
   width: 100%;
   /* Ensure it takes full width */
  }

  .user-info {
   display: flex;
   justify-content: center;
   align-items: center;
   width: 100%;
  }

  .user-report {
   position: relative;
   padding: 10px;
   background-color: rgba(255, 255, 255, 0.5);
   border-radius: 50px;
   box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
   flex: 1.5;
   width: 80%;
   height: 100%;
   overflow: hidden;
   max-width: 700px;
  }

  .report-content {
   display: flex;
   justify-content: center;
   align-items: center;
  }
 </style>
</head>
<?php
include './partials/navbar.php'
?>

<body>
    
 <form method="post">
  <div class="text-center">
   <div class="cover-box">
    <div class="user-info">
     <div class="user-report">
      <div class="report-content">
       <div class="form-group">
        <h3>Burn Calories</h3>
        <input type="hidden" class="form-control">
        <br>
        <label>
         <h5 class="float-left">How many steps do you walk?<img src="./images/icons8-walk.gif"></h5>
        </label>
        <div class="input-group mb-3">
         <input type="text" id="walk" name="walk" class="form-control" placeholder="Enter the steps">
        </div>
        <span class="validation-message" style="color:red;"></span><br>
        <label>
         <h5 class="float-left">Choose your walk Speed</h5>
        </label>
        <div class="input-group mb-3">
         <select name="speed" id="speed" class="form-control">
          <option disabled selected>Select speed</option>
          <option value="0.9">Slow</option>
          <option value="1.3">Average</option>
          <option value="1.79">Fast</option>
         </select>

        </div>
        <button type="submit" class="btn bg-warning" name="calories" onclick="return validation()">Add Calories</button>
       </div>
      </div>
     </div>
    </div>
   </div>
   <img src="./images/walk (2).jpg" style="width:410px; float:left;">
  </div>
 </form>

 <?php
 try {
  error_reporting(0);
  
  include './config.php';


  $p_user_id = $_SESSION['p_user_id_db'];
  $sql = "SELECT ud.daily_burn_calories FROM userdetails_db s JOIN daily_burn_calories ud ON s.id_db = ud.calories_user_id_db WHERE s.id_db = ?";

  $stmt = $conn->prepare($sql);

  $stmt->bind_param("s",   $p_user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result && $result->num_rows > 0) {
   $row = $result->fetch_assoc();
   $p_daily_calories = $row['daily_burn_calories'];
   $p_id = $row['id'];
  }
  if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['calories'])) {
   $p_height = $_SESSION['height_db'];
   $p_weight = $_SESSION['weight_db'];

   list($feet, $inches) = explode("'", $p_height);
   $total_inches = $feet * 12 + $inches;
   $p_height_in_meters = round($total_inches * 0.0254, 2);

   $stride_length = $p_height_in_meters * 0.414;
   $p_Walk = $_POST['walk'];
   $p_Speed = $_POST['speed'];

   $p_distance_walked = $stride_length * $p_Walk;
   if ($p_Speed == '1.3') { //Average
    $p_seconds = $p_distance_walked / $p_Speed;
    $p_Burned_Calories = round($p_seconds * 3.5 * 3.5 * $p_weight / (200 * 60), 2);

    $insert = "INSERT INTO daily_burn_calories (calories_user_id_db, daily_burn_calories, date) VALUES ('$p_user_id', '$p_Burned_Calories', CURDATE())";
    $insertion = $conn->query($insert);

    if (!$insertion) {
     echo "<div class='alert alert-warning' role='alert'>Today not burned calories</div>";
    }

    $fetch = "SELECT id, daily_burn_calories FROM daily_burn_calories WHERE calories_user_id_db ='$p_user_id' AND date = CURDATE()";
    $display = $conn->query($fetch);

    if ($display->num_rows > 0) {
     echo "<form method='POST'>";
     echo "<table style='border: 2px solid black; border-collapse: collapse; width: 50%; '>";
     echo "<tr class='text-center'>";
     echo "<th style='border: 2px solid black; padding: 10px;'>Calories</th>";
     echo "<th style='border: 2px solid black; padding: 10px;'>Select</th>";
     echo "<th style='border: 2px solid black; padding: 10px;'>Action</th>";
     echo "</tr>";

     while ($row = $display->fetch_assoc()) {
      $p_daily_burned_calories = $row['daily_burn_calories'];
      $p_id = $row['id'];

      echo "<tr class='text-center'>";
      echo "<td style='border: 2px solid black; padding: 10px;'>$p_daily_burned_calories</td>";
      echo "<td style='border: 2px solid black; padding: 10px;'><input type='checkbox' name='calories[]' value='" . $p_id . "'></td>";
      echo "<td style='border: 2px solid black; padding: 10px;'> <button type='submit' name='delete' value='" . $p_id . "' class='btn btn-danger'><i class='fa-solid fa-trash-can'></i> Delete</button></td>";
      echo "</tr>";
     }

     echo "</table>";
     echo "</form>";
    }
   }

   // Handle deletion of selected records
   if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['delete'])) {
    if (!empty($_POST['calories'])) {
     $delete_ids = $_POST['calories'];
     $escaped_delete_ids = array_map(function ($id) use ($conn) {
      return mysqli_real_escape_string($conn, $id);
     }, $delete_ids);

     $delete_query = "DELETE FROM daily_burn_calories WHERE id IN ('" . implode("','", $escaped_delete_ids) . "') AND calories_user_id_db='$p_user_id' AND date= CURDATE()";
     $delete_result = $conn->query($delete_query);

     if (!$delete_result) {
      echo "<div class='alert alert-danger' role='alert'>Failed to delete the record(s)</div>";
     } else {
      echo "<div class='d-flex justify-content-center'><div class='alert alert-success' role='alert' style='display: inline-block; width: auto;'>Record(s) deleted successfully.</div></div>";
      exit;
     }
    } else {
     echo "<div class='alert alert-warning' role='alert'>No rows selected for deletion.</div>";
    }
   } elseif ($p_Speed == '0.9') { //Slow

    $p_seconds = $p_distance_walked / $p_Speed;


    $p_Burned_Calories = round($p_seconds * 2.8 * 3.5 * $p_weight / (200 * 60), 2);
    $insert = "Insert into daily_burn_calories (calories_user_id_db ,daily_burn_calories) VALUES ('$p_user_id','$p_Burned_Calories')";
    $insertion = $conn->query($insert);
    if (!$insertion) {
     echo "<div class='alert alert-warning' role='alert'>Today not burned calories</div>";
    }

    $fetch = "SELECT id, daily_burn_calories FROM daily_burn_calories WHERE calories_user_id_db ='$p_user_id' AND date = CURDATE()";
    $display = $conn->query($fetch);

    if ($display->num_rows > 0) {
     echo "<form method='POST'>";
     echo "<table style='border: 2px solid black; border-collapse: collapse; width: 50%; '>";
     echo "<tr class='text-center'>";
     echo "<th style='border: 2px solid black; padding: 10px;'>Calories</th>";
     echo "<th style='border: 2px solid black; padding: 10px;'>Select</th>";
     echo "<th style='border: 2px solid black; padding: 10px;'>Action</th>";
     echo "</tr>";

     while ($row = $display->fetch_assoc()) {
      $p_daily_burned_calories = $row['daily_burn_calories'];
      $p_id = $row['id'];

      echo "<tr class='text-center'>";
      echo "<td style='border: 2px solid black; padding: 10px;'>$p_daily_burned_calories</td>";
      echo "<td style='border: 2px solid black; padding: 10px;'><input type='checkbox' name='calories[]' value='" . $p_id . "'></td>";
      echo "<td style='border: 2px solid black; padding: 10px;'> <button type='submit' name='delete' value='" . $p_id . "' class='btn btn-danger'><i class='fa-solid fa-trash-can'></i> Delete</button></td>";
      echo "</tr>";
     }

     echo "</table>";
     echo "</form>";
    }


    if (isset($_POST['delete'])) {
     $delete_user_id = $_POST['delete'];
     $delete_query = "DELETE FROM daily_burn_calories WHERE calories_user_id_db = '$delete_user_id'";
     $delete_result = $conn->query($delete_query);
     if (!$delete_result) {
      echo "<div class='alert alert-danger' role='alert'>Failed to delete the record</div>";
     }
    }
   }

   // echo "<div class='alert alert-info mr-5 ml-5 ' role='alert'>Calories Burned: $p_Burned_Calories</div>";
   elseif ($p_Speed == '1.79') { //Fast
    $p_seconds = $p_distance_walked / $p_Speed;
    $p_Burned_Calories = round($p_seconds * 3.5 * 3.5 * $p_weight / (200 * 60), 2);

    $insert = "Insert into daily_burn_calories (calories_user_id_db ,daily_burn_calories) VALUES ('$p_user_id','$p_Burned_Calories')";
    $insertion = $conn->query($insert);

    if (!$insertion) {
     echo "<div class='alert alert-warning' role='alert'>Today not burned calories</div>";
    }

    $fetch = "SELECT id, daily_burn_calories FROM daily_burn_calories WHERE calories_user_id_db ='$p_user_id' AND date = CURDATE()";
    $display = $conn->query($fetch);

    if ($display->num_rows > 0) {
     echo "<form method='POST'>";
     echo "<table style='border: 2px solid black; border-collapse: collapse; width: 50%; '>";
     echo "<tr class='text-center'>";
     echo "<th style='border: 2px solid black; padding: 10px;'>Calories</th>";
     echo "<th style='border: 2px solid black; padding: 10px;'>Select</th>";
     echo "<th style='border: 2px solid black; padding: 10px;'>Action</th>";
     echo "</tr>";

     while ($row = $display->fetch_assoc()) {
      $p_daily_burned_calories = $row['daily_burn_calories'];
      $p_id = $row['id'];

      echo "<tr class='text-center'>";
      echo "<td style='border: 2px solid black; padding: 10px;'>$p_daily_burned_calories</td>";
      echo "<td style='border: 2px solid black; padding: 10px;'><input type='checkbox' name='calories[]' value='" . $p_id . "'></td>";
      echo "<td style='border: 2px solid black; padding: 10px;'> <button type='submit' name='delete' value='" . $p_id . "' class='btn btn-danger'><i class='fa-solid fa-trash-can'></i> Delete</button></td>";
      echo "</tr>";
     }

     echo "</table>";
     echo "</form>";
    }
   }

   // Handle deletion of selected records
   if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['delete'])) {
    if (!empty($_POST['calories'])) {
     $delete_ids = $_POST['calories'];
     $escaped_delete_ids = array_map(function ($id) use ($conn) {
      return mysqli_real_escape_string($conn, $id);
     }, $delete_ids);

     $delete_query = "DELETE FROM daily_burn_calories WHERE id IN ('" . implode("','", $escaped_delete_ids) . "') AND calories_user_id_db='$p_user_id' AND date= CURDATE()";
     $delete_result = $conn->query($delete_query);

     if (!$delete_result) {
      echo "<div class='alert alert-danger' role='alert'>Failed to delete the record(s)</div>";
     } else {
      echo "<div class='d-flex justify-content-center'><div class='alert alert-success' role='alert' style='display: inline-block; width: auto;'>Record(s) deleted successfully.</div></div>";
     }
    } else {
     echo "<div class='alert alert-warning' role='alert'>No rows selected for deletion.</div>";
    }
   }
  }
 } catch (Exception $e) {
  echo "$_output=<div class='alert alert-danger' role='alert'><h3>Something Went Wrong!</h3></div>";
 }
 ?>

 <script>
  function validation() {
   let walk = document.getElementById("walk").value;
   let speed = document.getElementById("speed").value;
   let validationMessage = document.querySelector(".validation-message");
   const regex = /^[0-9]+$/;
   if (walk === null || walk <= 0 || !regex.test(walk)) {

    validationMessage.textContent = 'Invalid Step: ' + walk;

    return false;
   } else if (speed == null || speed === 'Select speed') {


    validationMessage.textContent = 'Choose the Speed';
    return false;

   } else {

    validationMessage.textContent = '';
    return true;
   }

  }
 </script>

 <!-- Optional JavaScript -->
 <!-- jQuery first, then Popper.js, then Bootstrap JS -->
 <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
 <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7W3mgPxhUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>