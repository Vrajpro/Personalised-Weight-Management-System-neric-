<!DOCTYPE html>
<html>
<head>
    <title>BMI update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            background-color: #ffffff;
            border-radius: 35px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: scale(1.02);
        }
        .card h2 {
            margin-top: 0;
            color: #3F826D; /* New color */
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .card label {
            display: block;
            text-align: left;
            margin: 15px 0 5px;
            font-weight: bold;
            color: #333;
        }
        .card input[type="number"] {
            width: calc(100% - 20px);
            padding: 12px;
            margin: 5px 0 20px;
            border: 2px solid #ccc;
            border-radius: 30px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s ease;
        }
        .card input[type="number"]:focus {
            border-color: #3F826D; /* New color */
        }
        .card input[type="submit"] {
            background-color: #3F826D; /* New color */
            color: #fff;
            border: none;
            border-radius: 30px;
            padding: 12px 20px;
            cursor: pointer;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background-color 0.3s ease;
        }
        .card input[type="submit"]:hover {
            background-color: #336B56; /* Darker shade of new color on hover */
        }
        .card input[type="submit"]:active {
            background-color: #2C5D4B; /* Even darker shade of new color on active */
        }
        #error-message {
            color: #f44336;
            margin-top: 10px;
            font-size: 14px;
            display: none;
        }
    </style>
    <script>
        function validateForm(event) {
            event.preventDefault();
            let weight = document.getElementById('weight').value;
            let height = document.getElementById('height').value;
            let errorMsg = '';

            // Check if fields are empty
            if (!weight && !height) {
                errorMsg = 'Both Weight and Height fields are required.';
            } else if (!weight) {
                errorMsg = 'Weight field is required.';
            } else if (!height) {
                errorMsg = 'Height field is required.';
            } else {
                weight = parseFloat(weight);
                height = parseFloat(height);

                // Validate weight
                if (weight < 20 || weight > 120) {
                    errorMsg = 'Weight must be between 20 and 120 kg.';
                }

                // Validate height
                if (height < 2 || height > 8) {
                    errorMsg = 'Height must be between 2 and 8 ft.';
                }

                if ((weight < 20 || weight > 120) && (height < 2 || height > 8)) {
                    errorMsg = 'Weight must be between 20 and 120 kg and Height must be between 2 and 8 ft.';
                } 

                // Additional checks
                if (!errorMsg && (weight <= 0 || height <= 0)) {
                    errorMsg = 'Weight and Height must be positive numbers.';
                }
            }

            const errorElement = document.getElementById('error-message');

            if (errorMsg) {
                errorElement.textContent = errorMsg;
                errorElement.style.display = 'block';
            } else {
                errorElement.style.display = 'none';
                document.querySelector('form').submit();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('form').addEventListener('submit', validateForm);
        });
    </script>
</head>
<body>
    <div class="card">
        <h2>Update yours</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="weight">Weight (kg):</label>
            <input type="number" name="weight" id="weight" placeholder="e.g., 70" step="0.1">
            <label for="height">Height (ft):</label>
            <input type="number" name="height" id="height" placeholder="e.g., 5.7" step="0.1">
            <input type="submit" value="Update">
            <div id="error-message"></div>
        </form>
    </div>
</body>
</html>
