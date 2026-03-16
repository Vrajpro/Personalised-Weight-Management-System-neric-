<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <title>BMI Calculator</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-image: url('./images/blob-haikei.svg');
      background-size: 100%;
      background-attachment: fixed;
    }

    .navbar {
      background-color: rgba(56, 94, 65, 0.1);
      backdrop-filter: blur(10px);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: background 0.3s ease-out;
      /* Faster transition for background */
    }

    .navbar-brand img {
      width: 70px;
    }

    #calculator {
      margin: 20px;
      max-width: 400px;
      background-color: #F9F9F9;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.2);
      position: relative;
      z-index: 1;
    }

    /* Input Styles */
    input[type="number"] {
      width: calc(50% - 10px);
      padding: 12px;
      margin: 5px;
      border: 1px solid #ccc;
      border-radius: 25px;
      /* Rounded border */
      font-family: 'Roboto', sans-serif;
      color: #333;
    }

    /* Primary Button Styles */
    .btn-primary {
      width: 100%;
      padding: 12px;
      border-radius: 25px;
      /* Rounded border */
      background-color: #3F826D;
      /* Dark green */
      border: none;
      cursor: pointer;
      font-family: 'Roboto', sans-serif;
      color: white;
      font-weight: bold;
      text-transform: uppercase;
    }

    .btn-primary:hover {
      background-color: #2e7d32;
      /* Darker green on hover */
    }

    /* Error Message Styles */
    #error-msg {
      color: #f44336;
      margin-top: 10px;
      font-size: 14px;
    }

    /* Result Styles */
    #result {
      margin-top: 15px;
      text-align: center;
      font-size: 24px;
      font-weight: bold;
      color: #333;
    }

    /* BMI Category Styles */
    .bmi-category {
      margin-top: 15px;
      text-align: center;
      font-weight: bold;
      font-size: 18px;
      color: #388e3c;
      /* Dark green */
    }

    .bmi-category span {
      font-weight: normal;
    }

    /* BMI Scale Styles */
    #bmi-scale {
      margin-top: 15px;
    }

    #bmi-scale .progress {
      height: 20px;
      border-radius: 5px;
      background-color: #f1f1f1;
    }

    #bmi-scale .progress-bar {
      border-radius: 5px;
      background-color: #388e3c;
      /* Dark green */
    }

    /* BMI Info Styles */
    #bmi-info {
      background-color: #f9f9f9;
      padding: 20px;
      border-radius: 25px;
      /* Rounded border */
      box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
      position: relative;
      z-index: 1;
      margin-top: 20px;
      font-family: 'Roboto', sans-serif;
    }

    #bmi-info h3 {
      font-size: 24px;
      font-weight: bold;
      color: #3F826D;
      /* Dark green */
    }

    #bmi-info p {
      font-size: 16px;
      line-height: 1.6;
      color: #555;
    }

    /* BMI Table Styles */
    #bmi-table {
      margin-top: 20px;
      border-radius: 25px;
      /* Rounded border */
      overflow: hidden;
    }

    #bmi-table th,
    #bmi-table td {
      padding: 12px;
      text-align: center;
      font-family: 'Roboto', sans-serif;
    }

    #bmi-table th {
      background-color: #3F826D;
      /* Dark green */
      color: white;
    }
  </style>
</head>

<body>


<header>
  <div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top justify-content-center">
      <!-- Navbar logo -->
      <a class="navbar-brand">
        <img src="./images/nericlogo.jpg" class="rounded-circle" style="width: 70px;" alt="Logo">
      </a>
      
 <!-- Navbar toggler button for mobile view -->
 <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navbar links -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="./index.php"><i style="font-size: 20px;">Back</i></a>
          </li>
         </ul>
      </div>
    </nav>
  </div>
</header>


  <div class="container mt-5">
    <h2 class="text-left" style="margin-top: 150px; margin-left: 75px; ">BMI Calculator</h2>

    <div class="row">
      <div class="col-md-6">
        <div id="calculator" class="text-center">
          <div class="row">
            <div class="col-md-12">
              <label for="height">Height (ft): &nbsp;</label>
              <input type="number" id="height" min="1" max="8" step="0.01" required>
              <div class="invalid-feedback">Please enter a valid height (1-8 feet).</div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <label for="weight">Weight (kg):</label>
              <input type="number" id="weight" min="1" max="150" required>
              <div class="invalid-feedback">Please enter a valid weight (1-150 kg).</div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <label for="age">Age: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </label>
              <input type="number" id="age" min="1" max="120" required>
              <div class="invalid-feedback">Please enter a valid age (1-120 years).</div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <label for="gender">Gender:</label>
              <select id="gender" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
              </select>
            </div>
          </div>
          <button class="btn btn-primary mt-3" onclick="calculateBMI()">Calculate BMI</button>
          <div id="error-msg"></div>
          <div id="result" class="mt-3"></div>
          <div id="bmi-category" class="bmi-category"></div>
          <div id="bmi-scale">
            <div class="progress">
              <div id="bmi-progress" class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
            </div>
          </div>
          <div id="suggestion" class="mt-3"></div>
          <button id="start-button" class="btn btn-primary mt-3" style="display: none;" onclick="showSuggestion()">See Suggestion</button>
        </div>
      </div>
      <div class="col-md-6">
        <div id="bmi-info">
          <h3>What is BMI?</h3>
          <p>BMI stands for Body Mass Index. It is a measure of body fat based on height and weight that applies to adult men and women. BMI is calculated by dividing a person's weight in kilograms by the square of their height in meters.</p>
          <h3>BMI Classification</h3>
          <table id="bmi-table" class="table table-bordered">
            <thead>
              <tr>
                <th>BMI Range</th>
                <th>Classification</th>
              </tr>
            </thead>
            <tbody>
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
          </table>
        </div>
      </div>
    </div>

  </div>

  <script>
    function calculateBMI() {
      var height = parseFloat(document.getElementById("height").value);
      var weight = parseFloat(document.getElementById("weight").value);

      // Input validation
      if (isNaN(height) || isNaN(weight) || height <= 0 || height > 8 || weight <= 0 || weight > 150) {
        document.getElementById("error-msg").innerText = "Please enter valid height (2-8 feet) and weight (15-150 kg).";
        document.getElementById("result").innerText = "";
        document.getElementById("bmi-category").innerText = "";
        document.getElementById("bmi-progress").style.width = "0%";
        document.getElementById("suggestion").innerText = ""; // Clear suggestion
        document.getElementById("start-button").style.display = "none"; // Hide start button
        return;
      }

      // Convert height from feet to meters
      var heightInMeters = height * 0.3048;

      // Calculate BMI
      var bmi = weight / (heightInMeters * heightInMeters);

      var bmiCategory;
      if (bmi < 18.5) {
        bmiCategory = "Underweight";
      } else if (bmi < 25) {
        bmiCategory = "Normal weight";
      } else if (bmi < 30) {
        bmiCategory = "Overweight";
      } else if (bmi < 35) {
        bmiCategory = "Obese (Class 1)";
      } else if (bmi < 40) {
        bmiCategory = "Obese (Class 2)";
      } else {
        bmiCategory = "Obese (Class 3)";
      }

      // Update results
      document.getElementById("error-msg").innerText = "";
      document.getElementById("result").innerText = "Your BMI is: " + bmi.toFixed(1);
      document.getElementById("bmi-category").innerHTML = "BMI Category: <span>" + bmiCategory + "</span>";

      // Calculate progress bar percentage
      var progress;
      if (bmi <= 18.5) {
        progress = bmi / 18.5 * 100;
      } else if (bmi <= 24.9) {
        progress = 25 + (bmi - 18.5) / (24.9 - 18.5) * 25;
      } else if (bmi <= 29.9) {
        progress = 50 + (bmi - 25) / (29.9 - 25) * 25;
      } else if (bmi <= 34.9) {
        progress = 75 + (bmi - 30) / (34.9 - 30) * 25;
      } else {
        progress = 100;
      }

      document.getElementById("bmi-progress").style.width = progress + "%";
      document.getElementById("bmi-progress").innerHTML = progress.toFixed(1) + "%";

      // Suggesting exercise based on BMI
      let suggestion = "";
      if (bmi < 18.5) {
        suggestion = "You are underweight. It's important to gain some weight. Consult with a nutritionist to plan a healthy diet.";
      } else if (bmi < 25) {
        suggestion = "Congratulations! Your weight is normal. Maintain a balanced diet and regular exercise to stay healthy.";
      } else if (bmi < 30) {
        suggestion = "You are overweight. Try to lose weight by following a healthy diet and regular exercise.";
      } else if (bmi < 35) {
        suggestion = "You are obese (Class 1). It's important to lose weight to reduce the risk of health problems. Consult with a doctor to plan a weight loss strategy.";
      } else if (bmi < 40) {
        suggestion = "You are obese (Class 2). It's crucial to lose weight to reduce the risk of health problems. Consult with a doctor to plan a weight loss strategy and consider bariatric surgery if recommended.";
      } else {
        suggestion = "You are obese (Class 3). It's critical to lose weight to reduce the risk of severe health problems. Consult with a doctor to plan a weight loss strategy and consider bariatric surgery if recommended.";
      }

      // Update suggestion
      document.getElementById("suggestion").innerText = suggestion;

      // Show "See Suggestion" button
      document.getElementById("start-button").style.display = "block";
    }

    function showSuggestion() {
      // Scroll to the suggestion section
      window.location.href = "login.php";
    }
  </script>

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>