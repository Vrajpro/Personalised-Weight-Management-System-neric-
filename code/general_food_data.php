<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Nutrition</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #b7e4c7;
            margin: 0;
            padding: 0;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-top: 40px;
        }

        /* Form Styles */
        form {
            max-width: 100%;
            margin: 40px auto;

            background-color: whitesmoke;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 4px 5px rgba(0, 0, 0, 0.1);
        }

        @media (min-width: 768px) {
            form {
                max-width: 1465px;
                margin: 40px auto;
            }
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 10px;
        }

        input[type="search"],
        input[type="text"],
        .s {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 20px;
        }

        /* Response Styles */
        pre {
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 4px;
            font-family: 'Courier New', Courier, monospace;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        table {
            max-width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            max-width: 100%;
            padding: 8px;
            text-align: center;
            border-bottom: 3px solid #ddd;
        }

        th {
            max-width: 100%;
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-light justify-content-between">
            <a class="navbar-brand"><img src="./images/nericlogo.jpg" class="rounded-circle" style="width: 80px;"></a>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="./index.php">
                        <i style="font-size: 24px;">Back</i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <h1>Nutrition Information</h1>

    <form id="nutrition-form" method="GET" action="">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-6">
                    <div id='result'></div>
                    <label for="query" class="mr-5">Search Your Food Calories:</label>
                    <div class="d-flex justify-content-center  align-items-center">
                        <input type="search" id="query" class="form-control mr-2" placeholder="Search" name="query">
                        <input type="text" class='form-control mr-2 w-25' name="qty" id="qty" placeholder="Qty">
                        <select name='serving' placeholder="How much?" class='s form-control mr-2 h-25 w-50'>
                            <option value="" disabled selected>How much?</option>
                            <option value="1 gram">1 gram</option>
                            <option value="10 gram">10 gram</option>
                            <option value="1 kg">1 kg</option>
                        </select>
                        <a href="barcode_scanner.php"><img src='./images/barcode-scanner_3269554.png' style='width:30px;height:30px;'></a>
                    </div>
                </div>
            </div>
        </div>
        <div id="nutrition-info"></div><br>


    </form>
    <script>
        var servingQty = 1; // Default serving quantity

        document.querySelector('.s').addEventListener('change', function(event) {
            var selectedOption = event.target.value;
            var query = document.getElementById('query').value;
            if (query.includes("gram") || query.includes("kg")) {
                query = query.replace(/(1|10) (gram|kg)/, '');
            }
            document.getElementById('query').value = selectedOption + " " + query.trim();
            document.getElementById('query').dispatchEvent(new Event('input'));
        });

        var timeout;
        document.getElementById('query').addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                var query = document.getElementById('query').value;
                fetchNutrition(query);
            }, 100);
        });

        document.getElementById('qty').addEventListener('input', function() {
            servingQty = parseFloat(this.value) || 1;
            var query = document.getElementById('query').value;
            fetchNutrition(query);
        });

        document.getElementById('nutrition-form').addEventListener('submit', function(event) {
            event.preventDefault();
            var query = document.getElementById('query').value;
            fetchNutrition(query);
        });

        function fetchNutrition(query) {
            var url = 'https://api.calorieninjas.com/v1/nutrition?query=' + encodeURIComponent(query);
            const apiKey = 'zc6ayqOCbDkVmzgi+WUZeQ==wQyoXooXbibtyM93'; // Replace with your actual API key
            var xhr = new XMLHttpRequest();
            xhr.open('GET', url);
            xhr.setRequestHeader('X-Api-Key', apiKey);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    displayNutrition(data.items);
                } else {
                    console.log('Request failed. Status:', xhr.status, 'Response:', xhr.responseText);
                }
            };
            xhr.onerror = function() {
                console.log('Request failed. Network error.');
            };
            xhr.send();
        }

        function displayNutrition(data) {
            var nutritionInfo = document.getElementById('nutrition-info');
            var tableHTML = "<table class='table-responsive'>";
            tableHTML += "<tr><th>Name</th><th>Calories</th><th>Serving Size (g)</th><th>Protein (g)</th><th>Total Carbohydrates (g)</th><th>Total Fat (g)</th><th>Saturated Fat (g)</th><th>Sodium (mg)</th><th>Potassium (mg)</th><th>Cholesterol (mg)</th><th>Fiber (g)</th><th>Sugar (g)</th></tr>";
            data.forEach(function(item) {
                tableHTML += "<tr>";
                tableHTML += "<td>" + item.name + "</td>";
                tableHTML += "<td>" + (item.calories * servingQty) + "</td>";
                tableHTML += "<td>" + item.serving_size_g + "</td>";
                tableHTML += "<td>" + (item.protein_g * servingQty) + "</td>";
                tableHTML += "<td>" + (item.carbohydrates_total_g * servingQty) + "</td>";
                tableHTML += "<td>" + (item.fat_total_g * servingQty) + "</td>";
                tableHTML += "<td>" + (item.fat_saturated_g * servingQty) + "</td>";
                tableHTML += "<td>" + (item.sodium_mg * servingQty) + "</td>";
                tableHTML += "<td>" + (item.potassium_mg * servingQty) + "</td>";
                tableHTML += "<td>" + (item.cholesterol_mg * servingQty) + "</td>";
                tableHTML += "<td>" + (item.fiber_g * servingQty) + "</td>";
                tableHTML += "<td>" + (item.sugar_g * servingQty) + "</td>";

            });
            tableHTML += "</table>";
            nutritionInfo.innerHTML = tableHTML;
        }

        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('food') && event.target.classList.contains('btn')) {

                let foodName = event.target.closest('tr').querySelector('td:first-child').textContent;
                let food_calories = event.target.closest('tr').querySelector('td:nth-child(2)').textContent;
                let servingSize = event.target.closest('tr').querySelector('td:nth-child(3)').textContent;
                let protein = event.target.closest('tr').querySelector('td:nth-child(4)').textContent;
                let carbs = event.target.closest('tr').querySelector('td:nth-child(5)').textContent;
                let fats = event.target.closest('tr').querySelector('td:nth-child(6)').textContent;
                let sodium = event.target.closest('tr').querySelector('td:nth-child(8)').textContent;
                let potassium = event.target.closest('tr').querySelector('td:nth-child(9)').textContent;;
                let cholesterol = event.target.closest('tr').querySelector('td:nth-child(10)').textContent;
                let fiber = event.target.closest('tr').querySelector('td:nth-child(11)').textContent;
                let sugar = event.target.closest('tr').querySelector('td:nth-child(12)').textContent;

                const urlParams = new URLSearchParams(window.location.search);
                const mealType = urlParams.get('meal');

                var xhr = new XMLHttpRequest();
                xhr.open('POST', '<?php echo $_SERVER['PHP_SELF']; ?>');
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        console.log('Food stored successfully.');
                        document.getElementById('result').innerHTML = "<div class='alert alert-success' role='alert'>Food Added Successfully!</div>";
                    } else {
                        console.error('Failed to store food. Status:', xhr.status);
                    }
                };
                xhr.onerror = function() {
                    console.error('Request failed. Network error.');
                };
                xhr.send(
                    'food_name=' + encodeURIComponent(foodName) +
                    '&serving_size=' + encodeURIComponent(servingSize) +
                    '&food_calories=' + encodeURIComponent(food_calories) +
                    '&protein=' + encodeURIComponent(protein) +
                    '&carbs=' + encodeURIComponent(carbs) +
                    '&fats=' + encodeURIComponent(fats) +
                    '&sodium=' + encodeURIComponent(sodium) +
                    '&potassium=' + encodeURIComponent(potassium) +
                    '&cholesterol=' + encodeURIComponent(cholesterol) +
                    '&fiber=' + encodeURIComponent(fiber) +
                    '&sugar=' + encodeURIComponent(sugar) +
                    '&meal_type=' + encodeURIComponent(mealType)
                );
            }
        });
    </script>


</body>

</html>