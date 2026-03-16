<?php
global  $p_deactivated_message;
global $p_status_ad;
$p_status_ad = 0;
include "session_login_logout.php";
include "./config.php";
$p_email = mysqli_real_escape_string($conn, $_SESSION["email"]);
$p_deactivated_message = "";
// Perform the query
$sql = "SELECT * FROM signup WHERE email_db = '$p_email'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
  while ($row = mysqli_fetch_assoc($result)) {
    $p_status = $row['status_db'];

    if ($p_status == 'deactivated') {
      $p_status_ad = 1;

      $p_deactivated_message = "<p class='alert alert-warning text-center m-5' role='alert'><strong>Important:</strong> Your,account is<strong> {$p_status}</strong>🚫.";

      session_destroy();
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en" class="en">

<head>
  <meta charset="utf-8">
  <title>NERIC</title>
  <meta name="robots" content="all, follow">
  <meta name="Googlebot" content="index, follow, snippet">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, viewport-fit=cover">
  <meta name="csrf-token" content="uhlpgBXZX0zP8kLc6XBpxq1Vbu4vwPrsbA5APAmk">
  <meta name="author" content="Art4web">
  <meta name="copyright" content="Art4web">
  <link rel="canonical" href="https://www.art4web.co">
  <meta name="keywords" content="webdesign, web design, website, UX design, UI design, original, creative, graphic, responsive">
  <meta name="description" content="Design and branding studio specializing in bespoke " wow-factor" websites, web & app UX UI, and branding. Crafting unique digital experiences for decades.">
  <meta name="apple-mobile-web-app-title" content="Art4web">
  <meta property="og:title" content="Websites, UX/UI Design &amp; Branding | Art4web">
  <meta property="og:description" content="Design and branding studio specializing in bespoke " wow-factor" websites, web & app UX UI, and branding. Crafting unique digital experiences for decades.">
  <meta property="og:locale" content="en_US">
  <meta property="og:url" content="https://www.art4web.co">
  <meta property="og:image" content="images/og-hp-services-content.jpg" data-content="https://www.art4web.co/images/og-hp-services-content.jpg">
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Art4web">
  <meta name="twitter:title" content="Websites, UX/UI Design &amp; Branding | Art4web">
  <meta name="twitter:description" content="Design and branding studio specializing in bespoke " wow-factor" websites, web & app UX UI, and branding. Crafting unique digital experiences for decades.">
  <meta name="twitter:image" content="https://www.art4web.co/images/og-hp-services-content.jpg" data-content="https://www.art4web.co/images/og-hp-services-content.jpg">
  <link rel="shortcut icon" href="images/favicon-art4web.png">
  <link rel="apple-touch-icon" sizes="180x180" href="images/favicon-art4web-touch.png">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-art4web-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-art4web-16x16.png">
  <link rel="manifest" href="https://www.art4web.co/images/site.webmanifest">
  <link rel="mask-icon" href="images/favicon-art4web-safari.svg" color="#bcd331">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#ffffff">
  <link rel="stylesheet" href="css/master.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
  </script>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <!--<link rel="stylesheet" href="./css/home_page.css">-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cookie&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fira+Mono:wght@400;500;700&family=Inconsolata:wght@200..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Oswald:wght@200..700&display=swap" rel="stylesheet">
  <style id="dynamic-css">
    <!-- 
    -->
  </style>
  <link rel="alternate" hreflang="en" href="https://www.art4web.co">
  <link rel="alternate" hreflang="sk" href="https://www.art4web.co/sk">
  <link rel="alternate" hreflang="x-default" href="https://www.art4web.co">
  <script defer="" data-domain="art4web.co" src="js/plausible.js"></script>
  <style>
    /* Ensure all font colors are black */
    * {
      color: black;
    }

    body {
      margin: 0;
      padding: 0;
      height: 100vh;
      overflow: auto;
      /* Allow scrolling on the body */
      overflow-x: hidden;
    }

    main {
      width: 110%;
      height: 100vh;
      overflow-y: scroll;
      /* Enable vertical scrolling */
      overflow-x: hidden;
    }

    /* Overlay */
    .overlay {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: black;
      /* Changed to black */
      padding: 100%;
      border-radius: 10px;
      font-size: 2em;
      z-index: 1000;
      overflow: auto;
      /* Ensure overlay content is scrollable if needed */
    }

    /* Content */
    .content {
      position: relative;
      z-index: 2;
      padding: 20px;
      background: rgba(255, 255, 255, 0.9);
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      overflow: auto;
      /* Ensure content is scrollable if needed */
    }

    /* Gradient Animation */
    @keyframes gradientAnimation {
      0% {
        background-position: 0% 0%;
      }

      50% {
        background-position: 100% 100%;
      }

      100% {
        background-position: 0% 0%;
      }
    }

    /* Blur Circle */
    .blur-circle {
      position: absolute;
      top: 1.8vh;
      left: 6.8%;
      transform: translateX(-50%);
      background-color: rgba(46, 204, 113, 0.3);
      border-radius: 50%;
      width: 240px;
      height: 240px;
      backdrop-filter: blur(20px);
      z-index: -1;
      overflow: auto;
      /* Ensure blur circle content is scrollable if needed */
    }

    /* Heading 1 */
    h1 {
      color: black;
      /* Ensure color is black */
      font-size: 3rem;
    }

    /* Slogan */
    #slogan {
      margin-top: 80px;
      /* Moves the element 260px from the top */
      margin-right: 100%;
      /* Moves the element 88% from the right */
      font-family: "Cookie", cursive;
      /* Sets the font to "Cookie" in cursive style */
      font-weight: 500;
      /* Sets font weight to 500 */
      font-style: normal;
      /* Sets font style to normal */
      font-size: 30.5px;
      /* Sets the font size to 30.5px */
      text-align: center;
      /* Aligns the text to the center */
      display: flex;
      /* Enables flexbox layout */
      justify-content: center;
      /* Centers content horizontally */
      align-items: center;
      /* Centers content vertically */
      width: 200px;
      /* Sets the width of the circle */
      height: 200px;
      /* Sets the height of the circle */
      background-color: rgba(128, 128, 128, 0.03);
      /* Sets a semi-transparent gray background */
      border-radius: 50%;
      /* Rounds the corners to make it a circle */
      backdrop-filter: blur(10px);
      /* Applies a blur effect to the background */
      overflow: auto;
      /* Ensure slogan is scrollable if needed */
    }

    /* Navbar Food Calorie Link */
    .navbar-nav .food-calorie-link {
      font-size: 16px;
      margin-right: 35px;
    }

    /* Profile Dropdown Menu */
    .profile-dropdown-menu {
      margin-right: 30px;
      left: auto;
    }

    /* General Container for Progress Circles */
    .container-fluid {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-wrap: nowrap;
      width: 100%;
      padding: 0 15px;
      overflow: auto;
      /* Ensure container is scrollable if needed */
    }

    /* Card Styling */
    .card {
      width: 100%;
      max-width: 1200px;
      margin: 20px auto;
      padding: 20px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      border-radius: 150px;
      /* Adjusted to a more typical value */
      background-color: rgba(255, 255, 255, 0.3);
      /* White with transparency */
      backdrop-filter: blur(10px);
      /* Apply blur effect */
      overflow: auto;
      /* Ensure card is scrollable if needed */
    }

    /* Progress Container */
    .progress-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 250px;
      height: 250px;
      margin: 0 10px;
      overflow: hidden;
      /* Hide overflow for a clean look */
      position: relative;
    }

    /* Progress Ring */
    .progress-ring {
      width: 100%;
      height: 100%;
      border-radius: 50%;
      position: relative;
      background: rgba(255, 255, 255, 0.2);
      /* Semi-transparent background */
      backdrop-filter: blur(10px);
      /* Apply blur effect */
      border: 2px solid rgba(255, 255, 255, 0.3);
      /* Subtle border to enhance the glass effect */
    }

    /* Progress Bar */
    .progress-bar {
      width: 100%;
      height: 100%;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.2);
      /* Semi-transparent background */
      border: 12px solid rgba(0, 255, 0, 0.5);
      /* Semi-transparent border to show progress */
      position: absolute;
      top: 0;
      left: 0;
      clip-path: conic-gradient(rgba(0, 255, 0, 0.5) var(--progress), rgba(0, 255, 0, 0.2) var(--progress));
      /* Progress effect with conic gradient */
      transition: clip-path 0.4s ease;
      /* Smooth transition for progress updates */
      backdrop-filter: blur(10px);
      /* Apply blur effect */
    }

    /* Progress Text */
    .progress-text {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 22px;
      font-family: 'Roboto', sans-serif;
      /* Modern and clean font */
      color: #333;
      /* Darker text color for readability */
      font-weight: 500;
      /* Slightly bolder text for emphasis */
      z-index: 1;
      /* Ensure text appears above other elements */
    }

    /* Container for Symbols */
    .symbols-container {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      margin: 0 20px;
      font-size: 3rem;
      padding: 10px;
    }

    /* Symbol Styling */
    .symbol {
      margin: 10px 0;
      line-height: 1;
    }

    /* Container for Net Calories */
    .net-calories-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 150px;
      margin: 0 10px;
      border-radius: 90px;
      padding: 10px;
      border: 2px solid rgba(0, 0, 0, 0.1);
      background-color: rgba(255, 255, 255, 0.8);
      overflow: auto;
      /* Ensure net calories container is scrollable if needed */
    }

    /* Net Calories */
    #net_calories {
      font-size: 2rem;
      text-align: center;
      margin: 0;
    }

    /* Net Calories Heading */
    #net_calories_heading {
      font-size: 1rem;
      text-align: center;
      font-weight: bolder;
      margin-top: 10px;
    }

    /* Loading Animation */
    #load-page {
      position: fixed;
      /* Make it fixed to the viewport */
      top: 50%;
      /* Center vertically */
      left: 0;
      /* Start from the left */
      width: 100%;
      /* Full width */
      transform: translateY(-50%);
      /* Adjust for centering */
      overflow: hidden;
      /* Hide overflow */
    }

    .lg-cnt {
      animation: moveLogo 1s forwards;
      /* Duration of animation and keeps the last state */
    }

    @keyframes moveLogo {
      0% {
        left: 0;
        /* Start at the left */

      }

      100% {
        left: calc(100% - 100px);
        /* Move to the right, accounting for the logo width */
      }
    }

    /* Logo Animation */
    .logo-anime {
      position: relative;
      /* Make it relative to allow movement */
    }
  </style>
</head>

<body>
  <aside id="load-page" class="general-loading">
    <div class="lg-cnt">
      <div class="logo-anime">
        <img src="images/nericlogo.jpg" alt="Loading Logo" width="150" height="150">
      </div>
    </div>
  </aside>

  <?php
  include "./partials/navbar.php";

  ?>

  <br><br>

  <?php include "home_page_content/home_page_content.php"; ?>



  <div id="page-content">
    <section id="intro">
      <aside id="mouse-parallax" class="parallax" data-parallax="true" data-scroll-speed="0.27" data-direction="down">
        <ul id="scene">
          <li class="layer" data-depth="0.133" data-depth-mobile="0.23">
            <div class="canvas group1" data-parallax="true" data-scroll-speed="0.35" data-direction="up"></div>
          </li>
          <li class="layer" data-depth="0.083" data-depth-mobile="0.32">
            <div class="canvas group2" data-parallax="true" data-scroll-speed="0.31" data-direction="up"></div>
            <div class="canvas group3" data-parallax="true" data-scroll-speed="0.226" data-direction="up"></div>
          </li>
          <li class="layer" data-depth="0.103" data-depth-mobile="0.38">
            <div class="canvas group4" data-parallax="true" data-scroll-speed="0.31" data-direction="up"></div>
            <div class="canvas group5" data-parallax="true" data-scroll-speed="0.226" data-direction="up"></div>
            <div class="canvas group6" data-parallax="true" data-scroll-speed="0.31" data-direction="up"></div>
          </li>
        </ul>
      </aside>
      <aside id="bubbles-container" class="video-box">
        <img class="video-ref flb-img" src="images/home-bubble-min.svg" data-videosrc="https://www.art4web.co/video/bubbles.mp4" alt="Atr4web Bubbles" rel="nofollow">
      </aside>
      <div class="scroll-icon">
        <div class="icon"><span><!--Jump--></span></div>
        <div class="title">Scroll</div>
      </div>
    </section>
  </div>
  <div class="overlay">


    <script>
      $(function() {
        $('[data-toggle="popover"]').popover()
      })
    </script>
    </head>

    <body>
      <div class="background-container"></div>


      <form method="post">

        <main class="main">



          <?php try {

            include 'config.php';

            $sql = "SELECT ud.name_db
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
                $p_name = $row["name_db"];

                echo '<h2 id="slogan" class="amita-regular text-center">Wellness Begins with<br> You , ' .
                  $p_name .
                  "</h2>";
              } else {
                echo "No records found";
              }

              $result->free();
            } else {
              echo "Error executing query: " . $conn->error;
            }

            $stmt->close();
          } catch (Exception $e) {
            echo "<div class='alert alert-danger' role='alert'><h3>Something Went Wrong!</h3></div>";
          } ?>


          <?php $p_daily_remaining_calories = round(
            $p_user_calories_goal -
              $p_total_consumption_daily_calories +
              $p_total_daily_calories,
            1
          ); ?>

          <br><br>
          <div class="container">
            <h2 id='remaining_cal_heading'><?php echo $p_name; ?>,your remaining calories</h2><br>
            <h3 class="display-3" id='remaining_cal_value'> <?php echo $p_daily_remaining_calories; ?></h3>
          </div>

          <?php
          require "vendor/autoload.php";

          use PHPMailer\PHPMailer\PHPMailer;
          use PHPMailer\PHPMailer\Exception;

          function getCountryFromAddress($address)
          {

            if (strpos($address, "USA") !== false) {
              return "USA";
            } elseif (strpos($address, "India") !== false) {
              return "India";
            } elseif (strpos($address, "UK") !== false) {
              return "UK";
            } elseif (strpos($address, "Canada") !== false) {
              return "Canada";
            }
          }

          function getTimeZoneFromCountry($country)
          {
            // Map countries to time zones
            $timeZones = [
              "USA" => "America/New_York",
              "India" => "Asia/Kolkata",
              "UK" => "Europe/London",
              "Canada" => "America/Toronto",
            ];

            return $timeZones[$country] ?? "UTC"; // Default to UTC if the country is not in the mapping
          }

          $p_address;
          $country = getCountryFromAddress($p_address);
          $timeZone = getTimeZoneFromCountry($country);

          date_default_timezone_set($timeZone);
          $currentTime = new DateTime();

          $targetTime = new DateTime('15:06:03');
          //   echo 'Current Time: ' . $currentTime->format('Y-m-d H:i:s') . "\n";
          if (
            $p_daily_remaining_calories == $p_user_calories_goal &&
            $currentTime == $targetTime
          ) {
            require __DIR__ . "/mailer.php";
            $mail = getMailer();

            if (!($mail instanceof PHPMailer)) {
              echo "Failed to load mailer.php or it did not return a PHPMailer object.";
              exit();
            }

            $mail->addAddress($email);
            $mail->Subject = "Enter Calories ";
            $mail->isHTML(true);
            $mail->Body =
              "Fitness and Nutrition<br><strong>Hey " .
              $p_name .
              ", your are not compeleting your task ,your calories is still " .
              $p_daily_remaining_calories .
              " remaining. </strong>";
            if (!$mail->send()) {
              echo "Error";
            }
          }
          ?>

          <div class="container-fluid">
            <div class="card">
              <div class="container-fluid d-flex justify-content-center align-items-center">
                <!-- Progress Circles -->
                <div class="progress-container">
                  <div class="progress-ring" id="progress-ring-1">
                    <div class="progress-bar"></div>
                    <div class="progress-text">
                      <label>Your Daily Calories</label>
                      <div id="progress-value"><?php echo $p_user_calories_goal; ?></div>
                      <?php $_SESSION["user_calories_goal"] = $p_user_calories_goal; ?>

                    </div>
                  </div>
                </div>

                <!-- Progress Circles -->
                <div class="progress-container">
                  <div class="progress-ring" id="progress-ring-2">
                    <a href="food_data.php" style="text-decoration: none;">
                      <div class="progress-bar" id="progress-bar-2">
                        Food Consumption
                        <div id="progress-value"><?php echo $p_total_consumption_daily_calories; ?></div>
                      </div>
                    </a>
                  </div>
                </div>

                <!-- Symbols -->
                <div class="symbols-container">
                  <span class="symbol">-</span>
                </div>


                <div class="progress-container">
                  <div class="progress-ring" id="progress-ring-3">
                    <a href="burn_calories.php" style="text-decoration: none;">
                      <div class="progress-bar" id="progress-bar-3">
                        Burned Calories
                        <div id="progress-value"><?php echo $p_total_daily_calories; ?></div>
                      </div>
                    </a>
                  </div>
                </div>

                <!-- Symbols -->
                <div class="symbols-container">
                  <span class="symbol">=</span>
                </div>

                <!-- Net Calories -->
                <div class="net-calories-container">
                  <?php
                  $p_net_calories = round($p_total_consumption_daily_calories - $p_total_daily_calories, 1);
                  ?>
                  <h1 id="net_calories"><?php echo $p_net_calories; ?></h1>
                  <div id="net_calories_heading">Net Calories</div>
                </div>
              </div>
            </div>
          </div>


  </div>
  <script>
    let p_total_daily_calories = <?php echo $p_total_daily_calories; ?>;
    let totalCalories = <?php echo $p_user_calories_goal; ?>;
    let total_daily_food_consumption_calories = <?php echo  $p_total_consumption_daily_calories; ?>;

    function update_progress_value_burn_calories(p_total_daily_calories) {
      let percentage = (p_total_daily_calories / totalCalories) * 100;

      let progressBar = document.getElementById("progress-bar-3");
      let progressText = document.getElementById("progress-text");

      let gradient = `conic-gradient(yellow 0% ${percentage}%, grey ${percentage}% 100%)`;
      progressBar.style.background = gradient;
      progressText.textContent = `${Math.round(percentage)}%`;
    }

    function update_progress_value_food_calories(total_daily_food_consumption_calories) {
      let percentage = (total_daily_food_consumption_calories / totalCalories) * 100;

      let progressBar = document.getElementById("progress-bar-2");
      let progressText = document.getElementById("progress-text-2");

      // Set the circular progress ring to a red gradient regardless of percentage
      let gradient = `conic-gradient(red 0% ${percentage}%, grey ${percentage}% 100%)`;
      progressBar.style.background = gradient;

      progressText.textContent = `${Math.round(percentage)}%`;
    }

    update_progress_value_burn_calories(p_total_daily_calories);
    update_progress_value_food_calories(total_daily_food_consumption_calories);
  </script>

  </main>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
  </script>

  <script src="./js/Home.js"></script>
  </form>
  </div>
  <script src="js/master.min.js"></script>
</body>

</html>