<?php
// Include necessary files and initialize MongoDB

include 'session_login_logout.php';
require 'vendor/autoload.php';

use MongoDB\Client;
use MongoDB\Driver\ServerApi;

if (isset($_POST['action']) && $_POST['action'] === 'search') {
    // Handle AJAX search request
    header('Content-Type: application/json');

    $searchTerm = isset($_POST['query']) ? trim($_POST['query']) : '';

    $synonyms = [
        'chaval' => 'rice',
        'chai' => 'Milk Tea',

    ];

    // Replace the search term with the standard term if it matches any synonym
    if (array_key_exists(strtolower($searchTerm), $synonyms)) {
        $searchTerm = $synonyms[strtolower($searchTerm)];
    }

    $response = [];

    // Connect to MongoDB
    $uri = 'mongodb+srv://nericproject:nericproject@cluster0.bzukd.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0';
    $apiVersion = new ServerApi(ServerApi::V1);
    $client = new Client($uri, [], ['serverApi' => $apiVersion]);

    try {
        // Send a ping to confirm successful connection
        $client->selectDatabase('admin')->command(['ping' => 1]);

        // Select the database and collection
        $database = $client->selectDatabase('Neric');
        $collection = $database->selectCollection('food');

        if ($searchTerm !== '') {
            // Perform a case-insensitive search using regex
            $cursor = $collection->find([
                'Name' => [
                    '$regex' => new MongoDB\BSON\Regex($searchTerm, 'i')
                ]
            ]);


            $results = iterator_to_array($cursor);

            if (count($results) > 0) {
                foreach ($results as $document) {
                    $response[] = [
                        'Name' => $document['Name'] ?? 'N/A',
                        'Calories (Kcal)' => $document['Calories (Kcal)'] ?? 'N/A',
                        'Total Carbohydrates' => $document['Total Carbohydrates'] ?? 'N/A',
                        'Total Fat' => $document['Total Fat'] ?? 'N/A',
                        'Protein' => $document['Protein'] ?? 'N/A',
                        'Sugars' => $document['Sugars'] ?? 'N/A',
                        'Serving Size' => $document['Serving Size'] ?? 'N/A',
                        'Saturated Fat' => $document['Saturated Fat'] ?? 'N/A',
                        'Trans Fat' => $document['Trans Fat'] ?? 'N/A',
                        'Polyunsaturated Fat' => $document['Polyunsaturated Fat'] ?? 'N/A',
                        'Monounsaturated Fat' => $document['Monounsaturated Fat'] ?? 'N/A',
                        'Cholesterol' => $document['Cholesterol'] ?? 'N/A',
                        'Sodium' => $document['Sodium'] ?? 'N/A',
                        'Dietary Fiber' => $document['Dietary Fiber'] ?? 'N/A',
                        'Vitamin D' => $document['Vitamin D'] ?? 'N/A',
                        'Calcium' => $document['Calcium'] ?? 'N/A',
                        'Iron' => $document['Iron'] ?? 'N/A',
                        'Potassium' => $document['Potassium'] ?? 'N/A',
                        'Vitamin A' => $document['Vitamin A'] ?? 'N/A',
                        'Vitamin C' => $document['Vitamin C'] ?? 'N/A',
                        'Caffeine' => $document['Caffeine'] ?? 'N/A',
                        'Allergy' => $document['Allergy'] ?? 'N/A',
                        'Food_image' => $document['Food_image'] ?? 'N/A',
                    ];
                }
                echo json_encode(['status' => 'success', 'data' => $response]);
            } else {
                echo json_encode(['status' => 'no_results']);
            }
        } else {
            echo json_encode(['status' => 'no_query']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Nutrition Information</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha384-fw8P0Gz5O51iZ6M2iYyyivbzK1A+4Kn2aCymOYk3M4RHnIbd9tB9PaNn3uAGGfYf" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #3b82f6;
            --background-color: #f8fafc;
            --card-background: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-radius: 16px;
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', system-ui, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
            color: #2d3748;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #1a365d;
        }

        .search-container {
            position: relative;
            margin-bottom: 3rem;
        }

        .search-input {
            width: 100%;
            padding: 1rem 1.5rem;
            font-size: 1.1rem;
            border: none;
            border-radius: 50px;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }

        .results-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .food-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .search-container {
            position: relative;
            margin-bottom: 3rem;
        }


        .search-input:focus {
            outline: none;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }

        .food-card:hover {
            transform: translateY(-5px);
        }

        .food-card h3 {
            color: #2d3748;
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }

        .macro-chart .carbs {
            background-color: #4299E1;
        }

        .macro-chart .protein {
            background-color: #DC143C;
        }

        .macro-chart .fat {
            background-color: #F6AD55;
        }

        .macro-legend {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .macro-legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;

        }

        .macro-legend-color {
            width: 12px;
            height: 12px;
            border-radius: 3px;
        }

        .macro-legend {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .macro-legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .nutrition-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 2rem;
        }

        .nutrition-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .nutrition-label {
            font-size: 0.9rem;
            color: #718096;
            display: block;
            margin-bottom: 0.25rem;
        }

        .nutrition-value {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .macro-chart {
            margin: 1rem 0;
            display: flex;
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
        }

        .macro-chart div {
            height: 100%;
        }


        .nutrition-item {
            font-weight: bold;
            line-height: 2;
            margin-bottom: 10px;

        }

        .food-image {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 250px;
            /* Fixed height */
            margin-bottom: 1.5rem;
            border-top-left-radius: 40px;
            border-top-right-radius: 20px;
            overflow: hidden;
            background-color: #f0f0f0;
            /* Light background for images with transparency */
        }

        .food-image img {
            max-width: 200%;
            max-height: 100%;
            width: 500px;
            height: auto;
            border-radius: 20px;
            object-fit: contain;
            /* Ensures entire image is visible */
            object-position: center;
            transition: transform 0.3s ease;
        }



        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .results-container {
                grid-template-columns: 1fr;
            }
        }

        .main-nutrition {
            background: #f8fafc;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin: 1rem 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }

        .main-nutrition .nutrition-item {
            border: none;
            text-align: center;
            padding: 1rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .main-nutrition .nutrition-label {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 1rem;
        }

        .main-nutrition .nutrition-value {
            font-size: 1.25rem;
            color: var(--text-primary);
        }

        .no-results {
            grid-column: 1 / -1;
            text-align: center;
            padding: 3rem;
            background: var(--card-background);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            color: var(--text-secondary);
            font-size: 1.1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Food Nutrition Information</h1>
            <div class="search-container">
                <!-- Search Section -->
                <div class="search-container">
                    <input type="search" id="search_query" class="search-input" placeholder="Search for a food item">
                </div>
            </div>
            <div id="search_results" class="results-container">

            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#search_query').on('keyup', function() {
                var query = $(this).val();

                if (query.length >= 3 || query.length === 0) {
                    $.ajax({
                        url: '',
                        method: 'POST',
                        data: {
                            action: 'search',
                            query: query
                        },
                        success: function(response) {
                            let resultHtml = '';

                            if (response.status === 'success') {
                                response.data.forEach(function(food) {
                                    let totalCarbs = parseFloat(food['Total Carbohydrates']) || 0;
                                    let totalProtein = parseFloat(food['Protein']) || 0;
                                    let totalFat = parseFloat(food['Total Fat']) || 0;
                                    let totalMacros = totalCarbs + totalProtein + totalFat;

                                    let carbsPercent = (totalCarbs / totalMacros * 100) || 0;
                                    let proteinPercent = (totalProtein / totalMacros * 100) || 0;
                                    let fatPercent = (totalFat / totalMacros * 100) || 0;

                                    resultHtml += `
                                      <div class="food-card">
        <div class="food-image">
         <img src="${food['Food_image']}" alt="${food['Name']}" 

                onerror="this.onerror=null; this.src='path/to/default-image.jpg';"
            />
        </div>
                                        <div class="food-card">
                                                    <h3 >${food['Name']}</h3>
                                                    <div class="main-nutrition">
                                                <div class="nutrition-item">
                                                    <span class="nutrition-label">Calories</span>
                                                    <span class="nutrition-value">${food['Calories (Kcal)']} kcal</span>
                                                </div>
                                                <div class="nutrition-item">
                                                    <span class="nutrition-label">Serving</span>
                                                    <span class="nutrition-value">${food['Serving Size']}</span>
                                                </div>
                                            </div>
                                                    <div class="macro-legend">
                                                        <div class="macro-legend-item">
                                                            <div class="macro-legend-color" style="background: #4299E1"></div>
                                                            <span>Carbs</span>
                                                        </div>
                                                        <div class="macro-legend-item">
                                                            <div class="macro-legend-color" style="background: #DC143C"></div>
                                                            <span>Protein</span>
                                                        </div>
                                                        <div class="macro-legend-item">
                                                            <div class="macro-legend-color" style="background: #F6AD55"></div>
                                                            <span>Fat</span>
                                                        </div>
                                                    </div>

                                            <div class="macro-chart">
                                                <div class="carbs" style="width: ${carbsPercent}%"></div>
                                                <div class="protein" style="width: ${proteinPercent}%"></div>
                                                <div class="fat" style="width: ${fatPercent}%"></div>
                                            </div>
                                            
                                            <div class="nutrition-grid">
                                               

<div class="nutrition-item" style="font-weight: bold; line-height: 2;">
    <span class="nutrition-label" style="font-size: 18px; font-weight: bold;">Carbs:</span>
    <span class="nutrition-value" style="font-size: 18px; font-weight: bold;">${food['Total Carbohydrates']}</span>
</div>

<div class="nutrition-item" style="font-weight: bold; line-height: 2;">
    <span class="nutrition-label" style="font-size: 18px; font-weight: bold;">Protein:</span>
    <span class="nutrition-value" style="font-size: 18px; font-weight: bold;">${food['Protein']}</span>
</div>

<div class="nutrition-item" style="font-weight: bold; line-height: 2;">
    <span class="nutrition-label" style="font-size: 18px; font-weight: bold;">Fat:</span>
    <span class="nutrition-value" style="font-size: 18px; font-weight: bold;">${food['Total Fat']}</span>
</div>

                                                <div class="nutrition-item">
                                                    <span class="nutrition-label">Sugars:</span>
                                                    <span class="nutrition-value">${food['Sugars']}</span>
                                                </div>
                                                     <div class="nutrition-item">
            <span class="nutrition-label">Saturated Fat:</span>
            <span class="nutrition-value">${food['Saturated Fat']}</span>
        </div>
        <div class="nutrition-item">
            <span class="nutrition-label">Trans Fat:</span>
            <span class="nutrition-value">${food['Trans Fat']}</span>
        </div>
        <div class="nutrition-item">
            <span class="nutrition-label">Polyunsaturated Fat:</span>
            <span class="nutrition-value">${food['Polyunsaturated Fat']}</span>
        </div>
        <div class="nutrition-item">
            <span class="nutrition-label">Monounsaturated Fat:</span>
            <span class="nutrition-value">${food['Monounsaturated Fat']}</span>
        </div>
        <div class="nutrition-item">
            <span class="nutrition-label">Cholesterol:</span>
            <span class="nutrition-value">${food['Cholesterol']}</span>
        </div>
        <div class="nutrition-item">
            <span class="nutrition-label">Sodium:</span>
            <span class="nutrition-value">${food['Sodium']}</span>
        </div>
        <div class="nutrition-item">
            <span class="nutrition-label">Dietary Fiber:</span>
            <span class="nutrition-value">${food['Dietary Fiber']}</span>
        </div>
        <div class="nutrition-item">
            <span class="nutrition-label">Vitamin D:</span>
            <span class="nutrition-value">${food['Vitamin D']} IU</span>
        </div>
        <div class="nutrition-item">
            <span class="nutrition-label">Calcium:</span>
            <span class="nutrition-value">${food['Calcium']}</span>
        </div>
        <div class="nutrition-item">
            <span class="nutrition-label">Iron:</span>
            <span class="nutrition-value">${food['Iron']}</span>
        </div>
        <div class="nutrition-item">
            <span class="nutrition-label">Potassium:</span>
            <span class="nutrition-value">${food['Potassium']}</span>
        </div>
        <div class="nutrition-item">
            <span class="nutrition-label">Vitamin A:</span>
            <span class="nutrition-value">${food['Vitamin A']} IU</span>
        </div>
        <div class="nutrition-item">
            <span class="nutrition-label">Vitamin C:</span>
            <span class="nutrition-value">${food['Vitamin C']} </span>
        </div>
        <div class="nutrition-item">
            <span class="nutrition-label">Caffeine:</span>
            <span class="nutrition-value">${food['Caffeine']}</span>
        </div>
                                            </div>`;





                                    resultHtml += `</div>`;
                                });
                                $('#search_results').html(resultHtml);
                            } else if (response.status === 'no_results') {
                                resultHtml = '<div class="no-results">No results found for your search.</div>';
                            }
                            $('#search_results').html(resultHtml);
                        },
                        error: function() {
                            $('#search_results').html('<div class="no-results">An error occurred. Please try again.</div>');
                        }
                    });

                }
            });
        });
    </script>
</body>

</html>