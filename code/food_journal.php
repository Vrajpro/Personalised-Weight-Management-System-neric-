<?php
session_start();
try {
    if (!isset($_SESSION['visited'])) {
        $_SESSION['visited'] = true;
        header('Location: index.php');
        exit();
    } else if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: index.php');
        exit();
    }
} catch (Exception $e) {
    echo "Something went wrong";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["current_date"])) {
        // Update session with the posted date
        $_SESSION['current_date'] = $_POST["current_date"];
    }
}

// Default to today's date if no session date is set
if (isset($_SESSION['current_date'])) {
    $currentDate = new DateTime($_SESSION['current_date']);
} else {
    $currentDate = new DateTime();  // Default to today's date if no session date
}

// Optional: Store today's date in session if no previous date is set
if (!isset($_SESSION['current_date'])) {
    $_SESSION['current_date'] = $currentDate->format('Y-m-d');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Journal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <style>
      /* Body Background */
body {
    background-image: url('./images/b.svg');
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
    font-family: 'Lato', sans-serif;
    margin-top: 100px;
}

/* Date Month Styling */
.date_month {
    color: #444; /* Calmer dark tone */
    font-weight: bold;
    margin-top: 150px;
    text-shadow: 0px 0px 6px rgba(0, 0, 0, 0.2);
    transition: color 0.3s ease;
}

.date_month:hover {
    color: #3A7A6A; /* Positive, calm green shade */
}

/* Button Styling */
.btn-outline-secondary {
    margin-top: 150px;
    color: #4B9F91;
    background: rgba(255, 255, 255, 0.1);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(8px);
    transition: transform 0.2s ease, background 0.3s ease;
    border: none;
    border-radius: 8px;
}

.btn-outline-secondary:hover {
    background: rgba(200, 250, 230, 0.2); /* Soft green tint */
    transform: scale(1.05);
}

/* Date Picker Styles */
.date-picker {
    display: flex;
    align-items: center;
    margin-top: 150px;
}

.date-picker-container {
    position: relative;
    display: inline-block;
}

/* Date Picker Icon */
.date-picker-icon {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    cursor: pointer;
    color: #4B9F91;
    opacity: 0.8;
    transition: color 0.2s ease;
}

.date-picker-icon:hover {
    color: #3A7A6A; /* Matches the calm green shade */
}

/* Flatpickr Input */
.flatpickr-input {
    padding-right: 30px;
    background: rgba(255, 255, 255, 0.15); /* Subtle transparency */
    color: #333;
    border: none;
    border-radius: 8px;
    padding: 10px;
    transition: background 0.4s ease;
}

.flatpickr-input:focus {
    background: rgba(200, 250, 230, 0.3); /* Light green when focused */
    outline: none;
}

    </style>
    <?php
    include './partials/navbar.php'
    ?>
</head>

<body>
    <main class="container d-flex flex-column justify-content-center align-items-center">
        <div class="d-flex justify-content-center align-items-center">
            <form method="post">
                <input type="hidden" name="current_date" value="<?php echo $currentDate->format('Y-m-d'); ?>">
                <button class="btn btn-outline-secondary" name="previous" id="previous-day">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </form>
            <h5 class="date_month mb-0 mx-3"><span id="date"><?php echo $currentDate->format('Y-m-d'); ?></span></h5>
            <form method="post">
                <span style="position: relative;">
                    <input type="hidden" name="current_date" value="<?php echo $currentDate->format('Y-m-d'); ?>">
                    <button class="btn btn-outline-secondary" name="next" id="next-day">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <!-- Add an icon for selecting a date on the right side -->
                    <i class="far fa-calendar-alt date-picker-icon" style="position: absolute; top: 80px; left: calc(100% + 8px);"></i>
                </span>
            </form>
        </div>
        <div id="food-diary" class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Food</th>
                        <th>Calories</th>
                    </tr>
                </thead>
            </table>
        </div>
    </main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $(document).ready(function() {
            function formatDate(date) {
                var day = String(date.getDate()).padStart(2, '0');
                var month = String(date.getMonth() + 1).padStart(2, '0');
                var year = date.getFullYear();
                return year + '-' + month + '-' + day;
            }

            function updateFoodDiary(date) {
                var formattedDate = formatDate(date);
                $("#date").text(formattedDate);
                $.ajax({
                    url: "food_journal_body.php",
                    type: "GET",
                    data: {
                        date: formattedDate
                    },
                    success: function(response) {
                        $("#food-diary").html(response);
                    }
                });
            }

            var today = new Date(); // Get today’s date
            var selectedDate = new Date('<?php echo $currentDate->format('Y-m-d'); ?>');

            if (!selectedDate || selectedDate.getTime() === new Date("2022-01-01").getTime()) {
                selectedDate = today;
            }

            updateFoodDiary(selectedDate);

            $("#previous-day").click(function(e) {
                e.preventDefault();
                selectedDate.setDate(selectedDate.getDate() - 1);
                updateFoodDiary(selectedDate);
                checkFutureDate();
            });

            $("#next-day").click(function(e) {
                e.preventDefault();
                if (selectedDate < today) {
                    selectedDate.setDate(selectedDate.getDate() + 1);
                    updateFoodDiary(selectedDate);
                }
                checkFutureDate();
            });

            function checkFutureDate() {
                if (selectedDate >= today) {
                    $("#next-day").prop("disabled", true);
                } else {
                    $("#next-day").prop("disabled", false);
                }
            }

            checkFutureDate(); // Disable 'Next' button if the date is today or in the future

            // Initialize flatpickr on the input field and icon
            var datePicker = flatpickr("#date", {
                defaultDate: selectedDate,
                maxDate: today,  // Restrict to today and earlier
                onChange: function(selectedDates, dateStr) {
                    selectedDate = new Date(dateStr);
                    updateFoodDiary(selectedDate);
                    checkFutureDate();
                }
            });

            // Open flatpickr when the calendar icon is clicked
            $(".date-picker-icon").click(function() {
                datePicker.open();
            });
        });
    </script>
</body>

</html>
