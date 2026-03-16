<?php
include 'config.php';
include 'session_login_logout.php';

// Assume $user_id is obtained from the session or other source
$user_id = $_SESSION["p_user_id_db"];

// Retrieve diet plan status for the user from the database
$query = "SELECT diet_plan_db FROM food WHERE user_id_db = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($dietPlan);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Tracker</title>
    <style>
/* Background Styling */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px; /* Add some padding for the container */
}

/* Gradient Animation */
@keyframes gradientBG {
    0% { background-position: 0% 0%; }
    50% { background-position: 100% 100%; }
    100% { background-position: 0% 0%; }
}

/* Headings */
h1, h2 {
    text-align: left;
    color: #4B9F91;
    text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.1);
}

h2 {
    margin-top: 20px;
}

/* Button Styling */
button {
    background-color: #4CAF50;
    border: none;
    color: white;
    padding: 10px 20px;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 50px; /* Full rounded corners */
    transition: background-color 0.3s ease, transform 0.2s ease;
}

button:hover {
    background-color: #45a049;
    transform: scale(1.05);
}

/* Table Styling */
th, td {
    padding: 10px;
    text-align: center;
    background: rgba(255, 255, 255, 1); /* Transparent for frosted effect */
    backdrop-filter: blur(10px); /* More pronounced frosted effect */
    border-radius: 100px; /* Full rounded corners */
}

th {
    color: black; /* Heading text color */
    font-weight: bold;
    padding: 20px; /* Add padding for better appearance */
    /* Different color for table headers */
}

/* Input Fields */
input[type=number], input[type=text] {
    width: 100px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    transition: border-color 0.3s ease;
    background: rgba(255, 255, 255, 0.8); /* Light transparent background */
    backdrop-filter: blur(5px); /* Frosted glass effect */
}

input[type=number]:focus, input[type=text]:focus {
    border-color: #4CAF50;
    outline: none;
}

/* Food Details and Table Containers */
.food_details,
#foodTable {
    margin: 20px auto; /* Center the containers */
    border-radius: 16px; /* Rounded corners for full containers */
    background: rgba(255, 255, 255, 0.5); /* More transparent for frosted look */
    padding: 20px;
    backdrop-filter: blur(15px); /* Stronger frosted effect */
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    max-width: 90%; /* Responsive max width */
}

/* Column Sizing */
.food_details, #foodTable {
    width: 100%; /* Full width of the parent */
}

.food_details th, .food_details td, #foodTable th, #foodTable td {
    width: calc(100% / 7); /* Equal column sizing */
}

/* Form Popup Styling */
.form-popup {
    display: none;
    position: fixed;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    border: 1px solid #ccc;
    padding: 20px;
    background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    z-index: 9;
}

/* Submit Button in Form Popup */
.form-popup input[type="submit"] {
    margin-top: 10px;
    padding: 8px 16px;
    border-radius: 50px; /* Full rounded corners */
    background-color: #4CAF50;
    color: #fff;
    cursor: pointer;
    border: none;
    transition: background-color 0.3s ease;
}

.form-popup input[type="submit"]:hover {
    background-color: #45a049;
}

/* Message Styling */
.message {
    color: green;
    margin-top: 10px;
    font-weight: bold;
    text-shadow: 0px 0px 2px rgba(0, 0, 0, 0.1);
}


    </style>
</head>

<body>
    <h1>Food Journal</h1><br>
    <button onclick="openForm()">Add New Food</button>

    <div class="form-popup" id="myForm">
        <h2>Add Food Details</h2>
        <form id="foodForm" method="POST">
            <label for="mealType">Select Meal:</label>
            <label for="mealType">Select Meal:</label>
            <select id="mealType" name="meal_type" required>
                <option value="">Select Meal</option>
                <option value="Breakfast">Breakfast</option>
                <option value="Lunch">Lunch</option>
                <option value="Dinner">Dinner</option>
                <option value="Snacks">Snacks</option>
            </select><br><br>

            <label for="foodName">Food Name:</label>
            <input type="text" id="foodName" name="food_name" required><br><br>
            <label for="serving_size">Serving Size:</label>
            <input type="number" id="serving_size" name="serving_size" required><br><br>

            <label for="calories">Calories (kcal):</label>
            <input type="number" id="calories" name="food_calories" required><br><br>

            <label for="carbs">Carbs (g):</label>
            <input type="number" id="carbs" name="carbs" required><br><br>

            <label for="fat">Fats (g):</label>
            <input type="number" id="fat" name="fats" required><br><br>

            <label for="protein">Proteins (g):</label>
            <input type="number" id="protein" name="protein" required><br><br>

            <label for="sugar">Sugar (g):</label>
            <input type="number" id="sugar" name="sugar" required><br><br>



            <input type="submit" value="Send Request">
        </form>
        <div class="message" id="responseMessage"></div>
        <button onclick="closeForm()">Close</button>
    </div>
    <?php
    try {
        if (
            $_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["food_name"])
            && isset($_POST["serving_size"]) && isset($_POST["food_calories"])
            && isset($_POST["protein"]) && isset($_POST["carbs"]) && isset($_POST["fats"]) && isset($_POST["sugar"]) && isset($_POST["meal_type"])
        ) {

            $foodName = $_POST["food_name"];
            $servingSize = $_POST["serving_size"];
            $food_calories = $_POST["food_calories"];
            $protein = $_POST["protein"];
            $carbs = $_POST["carbs"];
            $fats = $_POST["fats"];
            $sugar = $_POST["sugar"];
            $mealType = $_POST["meal_type"];
            $email = $_SESSION["email"];

            include './config.php';


            $stmt = $conn->prepare("SELECT f.diet_plan_db, s.id_db FROM food f JOIN signup s ON f.user_id = s.id_db WHERE s.email_db = ?");
            if (!$stmt) {
                die("Error preparing statement: " . $conn->error);
            }

            $stmt->bind_param("s", $email);
            if (!$stmt->execute()) {
                echo "Error executing statement: " . $stmt->error;
            }

            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row) {
                $dietPlan = $row["diet_plan_db"];
                $userId = $row["id_db"];

                $stmt = $conn->prepare("INSERT INTO food (food_name_db, quantity_db, user_id_db, food_calories_db, protein_db, carbohydrates_db, fats_db, sugar_db, meal_time_db) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                if (!$stmt) {
                    die("Error preparing statement: " . $conn->error);
                }

                $stmt->bind_param("ssissssss", $foodName, $servingSize, $userId, $food_calories, $protein, $carbs, $fats, $sugar, $mealType);
                if (!$stmt->execute()) {
                    die("Error executing statement: " . $stmt->error);
                }

                echo "<label>Food stored successfully.</label>";
                $stmt->close();
            } else {
                echo "<label>Error: User not found.</label>";
            }

            $conn->close();
        }
    } catch (Exception $e) {
        echo '<div class="alert alert-danger" role="alert">Something Went Wrong!</div>';
    }
    ?>
    <table class="food_details">
        <tr>
            <th>Food Name</th>
            <th>Calories (kcal)</th>
            <th>Carbs (g)</th>
            <th>Fat (g)</th>
            <th>Protein (g)</th>
            <th>Sugar (g)</th>
        </tr><br><br>
    </table>
    <?php  
include './config.php';
$totalCalories = 0;
$totalCarbs = 0;
$totalFat = 0;
$totalProtein = 0;
$totalSugar = 0;

if (isset($_GET['date'])) {
    $selectedDate = $_GET['date'];
    $mealTimes = array("Breakfast", "Lunch", "Dinner", "Snacks");

    $currentDate = date('Y-m-d');
    foreach ($mealTimes as $mealTime) {
        echo "<h3>$mealTime</h3>";
        $sql = "SELECT f.food_name_db, f.food_calories_db, f.carbohydrates_db, f.fats_db, f.protein_db, f.sugar_db 
                FROM userdetails_db ud 
                JOIN food f ON f.user_id_db = ud.id_db
                WHERE ud.id_db = '{$_SESSION["p_user_id_db"]}' AND f.meal_time_db = '$mealTime' AND f.date_db = '$selectedDate'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table class='food_details'>";
            echo "<tr><th>Food Name</th><th>Calories (kcal)</th><th>Carbs (g)</th><th>Fat (g)</th><th>Protein (g)</th><th>Sugar (g)</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . strtoupper($row["food_name_db"]) . "</td>";
                echo "<td>" . number_format($row["food_calories_db"], 2) . "</td>";
                echo "<td>" . number_format($row["carbohydrates_db"], 2) . "</td>";
                echo "<td>" . number_format($row["fats_db"], 2) . "</td>";
                echo "<td>" . number_format($row["protein_db"], 2) . "</td>";
                echo "<td>" . number_format($row["sugar_db"], 2) . "</td>";
                echo "</tr>";

                $totalCalories += $row["food_calories_db"];
                $totalCarbs += $row["carbohydrates_db"];
                $totalFat += $row["fats_db"];
                $totalProtein += $row["protein_db"];
                $totalSugar += $row["sugar_db"];
            }
            echo "</table>";
        } else {
            if ($selectedDate < $currentDate) {
                echo "<p>You did not enter the data available for $mealTime on $selectedDate.</p>";
            }
        }

        // Only show "Add Food" button if the selected date is today or in the future and diet_plan_db is not 1
        if ($selectedDate >= $currentDate) {
            if (isset($dietPlan)) {
                if ($dietPlan == 0) {
                    echo "<button name='$mealTime' onclick='addFood(\"$mealTime\")'>Add Food</button><br><br>";
                } else if ($dietPlan == 1) {
                    echo "<button name='$mealTime' disabled>(Diet plan restricted)</button><br><br>";
                } else {
                    echo "<label>No diet plan found for this user.</label><br><br>";
                }
            } else {
                echo "<label>No diet plan found for this user.</label><br><br>";
            }
        }            
    }
}

// Prepare to fetch diet plan based on user goal
$email = $_SESSION["email"]; // Get the email from session

// Fetch user's diet plan details
if ($conn->ping()) { // Check if the connection is still alive
    $stmt = $conn->prepare("SELECT ud.select_goal, f.diet_plan_db, s.id_db FROM userdetails_db ud 
                            JOIN food f ON f.user_id_db = ud.id_db 
                            JOIN signup s ON ud.id_db = s.id_db 
                            WHERE s.email_db = ?");
    $stmt->bind_param("s", $email); // Bind the email parameter
    $stmt->execute();
    
    // Get the results
    $userData = $stmt->get_result()->fetch_assoc();

    if ($userData) {
        // Retrieve the user's goal
        $userGoal = $userData['select_goal'];
        
        // Assuming user_calories_goal is stored in the session
        $totalCaloriesGoal = isset($_SESSION["user_calories_goal"]) ? $_SESSION["user_calories_goal"] : 0;

        // Set goals based on the user's selected goal
        switch ($userGoal) {
            case 'gain_weight':
                $goalCarbs = $totalCaloriesGoal * 0.50 / 4; // Carbs 50%
                $goalFat = $totalCaloriesGoal * 0.30 / 9;   // Fat 30%
                $goalProtein = $totalCaloriesGoal * 0.20 / 4; // Protein 20%
                break;

            case 'loss_weight':
                $goalCarbs = $totalCaloriesGoal * 0.40 / 4; // Carbs 40%
                $goalFat = $totalCaloriesGoal * 0.25 / 9;   // Fat 25%
                $goalProtein = $totalCaloriesGoal * 0.30 / 4; // Protein 30%
                break;

            case 'maintain_weight':
                $goalCarbs = $totalCaloriesGoal * 0.45 / 4; // Carbs 45%
                $goalFat = $totalCaloriesGoal * 0.30 / 9;   // Fat 30%
                $goalProtein = $totalCaloriesGoal * 0.25 / 4; // Protein 25%
                break;

            default:
                // Default to 0 if no valid goal is selected
                $goalCarbs = $goalFat = $goalProtein = 0;
                break;
        }

        // Calculate remaining values
        $remainingCalories = $totalCaloriesGoal - $totalCalories;
        $remainingCarbs = $goalCarbs - $totalCarbs;
        $remainingFat = $goalFat - $totalFat;
        $remainingProtein = $goalProtein - $totalProtein;
        $remainingSugar = 30 - $totalSugar; // Assuming 30g sugar daily goal by default

        // Output the results in a table
        echo "<table id='foodTable'>";
        echo "<tr>
                <td>Total Calories</td>
                <td id='totalCalories'>" . number_format($totalCalories, 2) . "</td>
                <td id='totalCarbs'>" . number_format($totalCarbs, 2) . "</td>
                <td id='totalFat'>" . number_format($totalFat, 2) . "</td>
                <td id='totalProtein'>" . number_format($totalProtein, 2) . "</td>
                <td id='totalSugar'>" . number_format($totalSugar, 2) . "</td>
              </tr>";
        echo "<tr>
                <td>Daily Goal</td>
                <td>" . number_format($totalCaloriesGoal, 2) . "</td>
                <td>" . number_format($goalCarbs, 2) . "</td>
                <td>" . number_format($goalFat, 2) . "</td>
                <td>" . number_format($goalProtein, 2) . "</td>
                <td>30.00</td> <!-- Daily sugar goal -->
              </tr>";
        echo "<tr>
                <td>Remaining</td>
                <td>" . number_format($remainingCalories, 2) . "</td>
                <td>" . number_format($remainingCarbs, 2) . "</td>
                <td>" . number_format($remainingFat, 2) . "</td>
                <td>" . number_format($remainingProtein, 2) . "</td>
                <td>" . number_format($remainingSugar, 2) . "</td>
              </tr>";
        echo "</table>";
    } else {
        echo "<p>No user data found.</p>";
    }
} else {
    echo "<p>Database connection issue.</p>";
}

$conn->close();
?>


<button id="saveButton">Save Nutrition Summary</button>


<script>
    function addFood(mealType) {
        // Store meal type in the database
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "food_data.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Redirect to food_data.php
                window.location.href = 'food_data.php?meal=' + mealType;
            }
        };
        xhr.send("meal_type=" + mealType);
    }

    function openForm() {
        document.getElementById("myForm").style.display = "block";
    }

    function closeForm() {
        document.getElementById("myForm").style.display = "none";
        document.getElementById("responseMessage").textContent = ''; // Clear previous messages
        document.getElementById("foodForm").reset(); // Reset the form fields
    }

    function updateTotals(calories, carbs, fat, protein, sugar) {
        document.getElementById('totalCalories').textContent = parseFloat(document.getElementById('totalCalories').textContent) + calories;
        document.getElementById('totalCarbs').textContent = parseFloat(document.getElementById('totalCarbs').textContent) + carbs;
        document.getElementById('totalFat').textContent = parseFloat(document.getElementById('totalFat').textContent) + fat;
        document.getElementById('totalProtein').textContent = parseFloat(document.getElementById('totalProtein').textContent) + protein;
        document.getElementById('totalSugar').textContent = parseFloat(document.getElementById('totalSugar').textContent) + sugar;
    }

    function calculateRemaining() {
        const dailyGoalCalories = parseFloat(document.getElementById('dailyGoalCalories').value);
        const dailyGoalCarbs = parseFloat(document.getElementById('dailyGoalCarbs').value);
        const dailyGoalFat = parseFloat(document.getElementById('dailyGoalFat').value);
        const dailyGoalProtein = parseFloat(document.getElementById('dailyGoalProtein').value);
        const dailyGoalSugar = parseFloat(document.getElementById('dailyGoalSugar').value);

        const totalCalories = parseFloat(document.getElementById('totalCalories').textContent);
        const totalCarbs = parseFloat(document.getElementById('totalCarbs').textContent);
        const totalFat = parseFloat(document.getElementById('totalFat').textContent);
        const totalProtein = parseFloat(document.getElementById('totalProtein').textContent);
        const totalSugar = parseFloat(document.getElementById('totalSugar').textContent);

        // Update the remaining values dynamically
        document.getElementById('remainingCalories').textContent = dailyGoalCalories - totalCalories;
        document.getElementById('remainingCarbs').textContent = dailyGoalCarbs - totalCarbs;
        document.getElementById('remainingFat').textContent = dailyGoalFat - totalFat;
        document.getElementById('remainingProtein').textContent = dailyGoalProtein - totalProtein;
        document.getElementById('remainingSugar').textContent = dailyGoalSugar - totalSugar;
    }

    document.getElementById("saveButton").addEventListener("click", function() {
        var userId = <?php echo $userId; ?>; // Assuming user ID is available in PHP
        var totalCalories = parseFloat(document.getElementById('totalCalories').textContent);
        var totalCarbs = parseFloat(document.getElementById('totalCarbs').textContent);
        var totalFat = parseFloat(document.getElementById('totalFat').textContent);
        var totalProtein = parseFloat(document.getElementById('totalProtein').textContent);
        var totalSugar = parseFloat(document.getElementById('totalSugar').textContent);
        var remainingCalories = parseFloat(document.getElementById('remainingCalories').textContent);
        var remainingCarbs = parseFloat(document.getElementById('remainingCarbs').textContent);
        var remainingFat = parseFloat(document.getElementById('remainingFat').textContent);
        var remainingProtein = parseFloat(document.getElementById('remainingProtein').textContent);
        var remainingSugar = parseFloat(document.getElementById('remainingSugar').textContent);
        var dateRecorded = new Date().toISOString().split('T')[0]; // Get current date

        // Prepare data to send
        var data = {
            user_id: userId,
            total_calories: totalCalories,
            total_carbs: totalCarbs,
            total_fat: totalFat,
            total_protein: totalProtein,
            total_sugar: totalSugar,
            remaining_calories: remainingCalories,
            remaining_carbs: remainingCarbs,
            remaining_fat: remainingFat,
            remaining_protein: remainingProtein,
            remaining_sugar: remainingSugar,
            date_recorded: dateRecorded
        };

        // Send data to PHP for saving in the database
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "save_nutrition_summary.php", true);
        xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert('Nutrition summary saved successfully!');
            }
        };
        xhr.send(JSON.stringify(data));
    });
</script>
</body>

</html>
