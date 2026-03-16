<?php
session_start();
if (!isset($_SESSION['visited'])) {
    $_SESSION['visited'] = true;
    header('Location: index.php');
    exit();
  }
  


if(!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !=true){

    header('Location:login.php');
    exit();
   
   }
else{
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['logout'])) {
            $_SESSION = array();
            session_unset();
            session_destroy();
            header('location:index.php');
            exit();
        }
    }
}
// Establish database connection
$conn = mysqli_connect("localhost", "root", "", "neric");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["food_name"])) {
        foreach ($_POST["food_name"] as $food_name) {
            // Sanitize the food name to prevent SQL injection
            $food_name = mysqli_real_escape_string($conn, $food_name);
            // SQL query to delete food item with the specified name
            $deleteQuery = "DELETE FROM food_calories_view WHERE food_name_db='$food_name'";
            // Execute the query
            if (!mysqli_query($conn, $deleteQuery)) {
          
                echo "<center>Error deleting food item '$food_name': " . mysqli_error($conn) . "</center><br>";
            }
        }
        // Reload the page after the delete action is performed
        echo '<meta http-equiv="refresh" content="0">';
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $foodname = $_POST['foodname'];
    $calories = $_POST['calories'];
    $protein = $_POST['protein'];
    $carbohydrate = $_POST['carbohydrate'];
    $fats = $_POST['fats'];
    $pattern = '/^[a-zA-Z0-9 ]+$/';
    // Validate form data
    if (!empty($foodname) && !empty($calories) && !empty($protein) && !empty($carbohydrate) && !empty($fats)) {
        // Check if the food already exists in the database
        $check_query = "SELECT * FROM food_calories_view WHERE food_name_db = '$foodname'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            // Food already exists
            echo "<div  class='text-center alert alert-warning' role='alert' >The food item '$foodname' already exists in the database.</div>";
        } 


        
        else {
            // Insert the new food item
            $insert_query = "INSERT INTO food_calories_view (food_name_db, calories_db, protein_db, carbohydrate_db, fats_db) VALUES ('$foodname', $calories, $protein, $carbohydrate, $fats)";
            if($calories<=$protein){
                echo "<div  class='text-center alert alert-warning' role='alert' >Food Protein is $protein  not greater or equal too than Food Calories $calories</div>";                    
                }
                elseif($calories<=$carbohydrate){
                    echo "<div  class='text-center alert alert-warning' role='alert' >Food Carbohydrate is$carbohydrate  not greater or equal too than Food Calories $calories</div>";                    
                    }
                    elseif($calories<=$fats){
                        echo "<div  class='text-center alert alert-warning' role='alert' >Food Fats is $fats not greater or equal too than Food Calories $calories</div>";                    
                        }
                       
                        elseif(!preg_match($pattern,$foodname)){
                            echo "<div  class='text-center alert alert-danger' role='alert' >The food item '$foodname' contain special character.</div>";
                           if ($foodname<0){
                            echo "<div  class='text-center alert alert-danger' role='alert' >The food item '$foodname' cannot negative.</div>";

                           }
                          
                        }
                        elseif($calories<0){
                            echo "<div  class='text-center alert alert-danger' role='alert' >The food calories $calories cannot negative.</div>";

                        }
                        elseif($carbohydrate<0){
                            echo "<div  class='text-center alert alert-danger' role='alert' >The food carbohydrate $carbohydrate cannot negative.</div>";

                        }
                        elseif($protein<0){
                            echo "<div  class='text-center alert alert-danger' role='alert' >The food protein $protein cannot negative.</div>";

                        }
                        elseif($fats<0){
                            echo "<div  class='text-center alert alert-danger' role='alert' >The food fats $fats cannot negative.</div>";

                        }
            elseif (mysqli_query($conn, $insert_query)) {
                echo '<meta http-equiv="refresh" content="0">';
                echo "<div  class='text-center alert alert-success' role='alert' >New food item added successfully!</div>";
              }
                
             else {
                echo "Error: " . $insert_query . "<br>" . mysqli_error($conn);
            }
        }
        
    } else {
        echo "<div  class='text-center alert alert-danger' role='alert' >All fileds are required!</div>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Food Calories</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
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

        .active.nav-item {
            background-color: #007bff;
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
        
    </style>
</head>

<body>
    <form method="post" action="#">
        <div class="sidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center">
                <img src="./images/nericlogo.jpg" alt="Logo" class="rounded-circle" width="90" height="90">
            </a>
            <hr class="sidebar-divider my-0">
            <!-- Nav Item - Dashboard -->
            <ul class="nav flex-column">
                <li class="nav-item ">
                    <a class="nav-link" href="./admin_panel.php">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./statistics.php">
                        <i class="fas fa-fw fa-chart-area"></i>
                        <span>Statistics</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./add_food_calories.php">
                        <span>Add Food Calories</span>
                    </a>
                </li>
            </ul><br>
            <!-- Logout Button -->
            <div class="logout-button ml-5">
                <form method="post">
                    <button class="btn btn-danger " name="logout">Logout</button>
                </form>
            </div>
            <!-- End of Logout Button -->
        </div>
    </form>

    <div id="content-wrapper">
        <!-- Main Content -->
        <div id="content">
            <!-- Begin Page Content -->
            <div class="container-fluid">
                <!-- Middle Content -->
                <div class="middle-content">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2>Add Food Calories</h2>
                    </div>
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="foodname">Food name</label>
                            <input type="text" class="form-control" id="foodname" name="foodname" placeholder="Enter food name">
                        </div>
                        <div class="form-group">
                            <label for="calories">Food Calories</label>
                            <input type="number" class="form-control" id="calories" name="calories">
                        </div>
                        <div class="form-group">
                            <label for="protein">Protein<b> (g)</b></label>
                            <input type="number" class="form-control" id="protein" name="protein">
                        </div>
                        <div class="form-group">
                            <label for="carbohydrate">Carbohydrate<b> (g)</b></label>
                            <input type="number" class="form-control" id="carbohydrate" name="carbohydrate">
                        </div>
                        <div class="form-group">
                            <label for="fats">Fats<b> (g)</b></label>
                            <input type="number" class="form-control" id="fats" name="fats">
                        </div>
                        <div class="form-check">
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                   
                    <!-- Display Available Food -->
                    <hr>
                    <div class="container mt-5">
                        <h2>Available Food</h2>
                        <table class="table">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search...">
                            <table id="foodTable"class="table border-muted" border="2px solid " >
                                <thead>
                                <tr class='text-center' >
                                        <th>Food Name</th>
                                        <th>Calories</th>
                                        <th>Protein</th>
                                        <th>Carbohydrate</th>
                                        <th>Fats</th>
                                        <th>Select</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch available food data from the database
                                    $sql = "SELECT * FROM food_calories_view";
                                    $result = mysqli_query($conn, $sql);

                                    // Check if there are any results
                                    if (mysqli_num_rows($result) > 0) {
                                        // Output data of each row
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr class='text-center'>".
                                                '<td>' . $row["food_name_db"] . '</td>' .
                                                '<td>' . $row["calories_db"] . '</td>' .
                                                '<td>' . $row["protein_db"] . '</td>' .
                                                '<td>' . $row["carbohydrate_db"] . '</td>' .
                                                '<td>' . $row["fats_db"] . '</td>' .
                                                '<td class="text-center"><input type="checkbox" name="food_name[]" value="' . $row["food_name_db"] . '"></td>' .
                                                '<td class="text-center">' .
                                                '<button type="submit" name="delete" class="btn btn-danger btn-sm" value="' . $row["food_name_db"] . '">Delete</button>' .
                                                '</td>' .
                                                '</tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="7">No Food found</td></tr>';
                                    }
                                   
                                    mysqli_close($conn);
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                                </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for filtering table -->
    <script>
        function filterTable() {
            // Get input value and convert to lowercase for case-insensitive search
            var input = document.getElementById('searchInput').value.toLowerCase();
            // Get table rows
            var rows = document.getElementById('foodTable').getElementsByTagName('tr');

            // Loop through all table rows
            for (var i = 0; i < rows.length; i++) {
                var row = rows[i];
                // Get cell data in the first column (food name)
                var foodName = row.getElementsByTagName('td')[0];
                if (foodName) {
                    var textValue = foodName.textContent || foodName.innerText;
                    // Check if input value matches any part of the food name
                    if (textValue.toLowerCase().indexOf(input) > -1) {
                        // Show row if it matches
                        row.style.display = '';
                    } else {
                        // Hide row if it doesn't match
                        row.style.display = 'none';
                    }
                }
            }
        }

        // Attach event listener to the search input field
        document.getElementById('searchInput').addEventListener('keyup', filterTable);
    </script>
</body>

</html>


