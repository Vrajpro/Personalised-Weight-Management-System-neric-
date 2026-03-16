<?php 
session_start();

if (isset($_SESSION['loggedin'])) {
    header('Location: home-page.php');
    exit();
}

if (isset($_SESSION['admin_loggedin'])) {
    header('Location: admin_panel.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/home_page.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
  <title>Index Page</title>
  <style>
    body {
      background: linear-gradient(to left, rgba(46, 204, 113, 0.3), #b3e5c5, #c1e8cd, #cef0d5, #daf3dc, #e8f7e3, #f4fbec, #fafcf6, #fcfcfb, #fdfdfd, #f2f7f2);
      transition: background 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
      background-size: 200% 200%;
      animation: gradientAnimation 20s infinite;
      background-attachment: fixed;
      margin: 0;
      padding: 0;
    }

    @keyframes gradientAnimation {
      0% {
        background-position: 0% 50%;
      }
      50% {
        background-position: 100% 50%;
      }
      100% {
        background-position: 0% 50%;
      }
    }

  
      .navbar {
  background-color: rgba(56, 94, 65, 0.1);
  backdrop-filter: blur(10px);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: background 0.3s ease-out;
  color: white;
  position: sticky; /* if wnats sticky navbar remove it */
  top: 0;
  z-index: 1000;
  border-radius: 50px; /* Rounded corners */
  /* Optional: add a subtle border */
  margin: 0 15px; /* Adjust margin to avoid overlap with the content */
}
    

    .navbar a {
      color: white !important;
    }

    .navbar-nav .nav-link {
      padding: 15px 20px;
      transition: color 0.3s;
    }

    .navbar-nav .nav-link:hover {
      color: #28a745;
    }

    .hero-section {
      position: relative;
      height: 80vh;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      color: white;
      background: rgba(0, 0, 0, 0.3);
    }

    .hero-section h1 {
      font-size: 3em;
      font-weight: 700;
      margin-bottom: 20px;
    }

    .hero-section p {
      font-size: 1.5em;
    }

    .hero-section .btn-primary {
      padding: 10px 20px;
      font-size: 1.2em;
      border-radius: 25px;
      color:#204a42;
    }

    .slogan-container {
      padding-top: 50px;
    }

    .slogan-container p {
      font-weight: 350;
      font-style: normal;
      color: #204a42;
      font-size: 2em;
      line-height: 1.5;
      transition: font-size 0.3s, color 0.1s;
    }

    .slogan-container span {
      font-weight: 600;
      font-family: 'Montserrat', sans-serif;
      color: #3F826D;
      font-size: 1.3em;
    }

    .slogan-container em {
      font-family: 'Montserrat', sans-serif;
      color: #04724D;
      font-size: 1.3em;
    }
    #slogan {
    color: #204a42; /* Dark green color */
    text-align : left;
  }

    .card {
      border-radius: 20px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s;
      background-color: rgba(255, 255, 255, 0.9);
      position: relative;
      overflow: hidden;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card-title {
      color: #204a42;
      font-weight: bold;
      margin-top: 25px;
      margin-bottom: 10px;
    }

    .card-text {
      color: #666;
      margin-bottom: 20px;
    }

    .card-body {
      padding: 30px;
      text-align: center;
    }

    .card-icon {
      font-size: 3em;
      color: #3F826D;
    }

    .btn-primary {
      background-color: #28a745;
      border-color: #28a745;
    }

    .btn-primary:hover {
      background-color: #218838;
      border-color: #218838;
    }

    .video-container {
      position: relative;
      width: 100%;
      height: 100vh;
      overflow: hidden;
    }

    .video-container video {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: -1;
    }

    .video-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.3);
      z-index: 1;
    }

    .video-text {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: white;
      font-size: 3em;
      text-align: center;
      z-index: 2;
      padding: 20px;
      font-family: 'Montserrat', sans-serif;
      font-weight: 400;
      animation: fadeIn 2s ease-in-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translate(-50%, -60%);
      }
      to {
        opacity: 1;
        transform: translate(-50%, -50%);
      }
    }

    .navbar-content {
      z-index: 2;
      position: relative;
    }

    .footer {
      background-color: rgba(56, 94, 65, 0.1);
      color: white;
      text-align: center;
      padding: 20px;
      position: relative;
      bottom: 0;
      width: 100%;
    }
    .scroll-down {
  position: absolute;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  color: white;
  font-size: 2em;
  text-align: center;
  z-index: 3;
  cursor: pointer;
  animation: bounce 2s infinite;
}

.scroll-down a {
  color: white;
  text-decoration: none;
}

.scroll-down i {
  display: block;
}

@keyframes bounce {
  0%, 20%, 50%, 80%, 100% {
    transform: translateY(0);
  }
  40% {
    transform: translateY(-20px);
  }
  60% {
    transform: translateY(-10px);
  }
}

    .footer a {
      color: white;
      margin: 0 10px;
    }

    .footer a:hover {
      color: #28a745;
    }
  </style>
</head>

<body>

<div class="video-container">
  <video autoplay muted loop>
    <source src="./video/fit4.mp4" type="video/mp4">
    Your browser does not support the video tag.
  </video>
  <div class="video-overlay"></div>
  <div class="video-text">Your health matters. Join now.</div>
  <div class="navbar-content">
    <?php include 'partials/index_navbar.php'; ?>
  </div>
  <div class="scroll-down">
    <a href="#main-content"><i class="fas fa-chevron-down"></i></a>
  </div>
</div>

<!-- Hero Section -->
<div class="hero-section">
  <div>
    <h1>Transform Your Fitness Journey</h1>
    <p>Achieve your health goals with our comprehensive plans and tools.</p>
    <a href="./login.php" class="btn btn-primary">Start Your Journey</a>
  </div>
</div>

<br><br>

<!-- Slogan and Image Section -->
<div class="container">
  <div class="row">
    <div class="col-md-6 slogan-container">
      <p id="slogan">Small steps, <span>big results.</span> Every action gets closer to your fitness goals. <br> <a href="./login.php" class="btn btn-primary">Start Journey</a></p>
    </div>
    <div class="col-md-6">
      <img src="./images/trackerapp.svg" class="custom-img rounded-circle" style="width: 450px;">
    </div>
  </div>
</div>

<br><br><br>

<!-- Features Section -->
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="text-center">
        <h2>Our Major Features</h2>
      </div>
    </div>
  </div>
</div>

<br><br>

<main class="main">
  <div class="container">
    <!-- Three cards -->
    <div class="row">
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <img class="card-img-top" src="./images/vfc.jpg" alt="Card image cap">
          <div class="card-body">
            <h5 class="card-title">Personalized Plans</h5>
            <p class="card-text">Receive workout and diet plans tailored to your unique goals and preferences.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <img class="card-img-top" src="./images/trackerapp.svg" alt="Card image cap">
          <div class="card-body">
            <h5 class="card-title">Track Calories</h5>
            <p class="card-text">Effortlessly track your daily calorie intake and monitor your nutritional goals.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <img class="card-img-top" src="./images/p.jpg" alt="Card image cap">
          <div class="card-body">
            <h5 class="card-title">Analyze Progress</h5>
            <p class="card-text">Visualize your progress through detailed graphs and reports.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Footer -->
<div class="footer">
  <p style = "color : #3F826D">&copy;  All rights reserved by neric. </p>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
  integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
  integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
  integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script>
  $(document).ready(function () {
    <?php
      $loggedIn = false; // Example: Set this to true if user is logged in
      if($loggedIn) {
        echo 'showLoggedInMenu();';
      } else {
        echo 'showLoggedOutMenu();';
      }
    ?>

    function showLoggedInMenu() {
      $("#profileDropdown").css("opacity", "1");
      $("#loginLink").css("opacity", "0");
    }

    function showLoggedOutMenu() {
      $("#profileDropdown").css("opacity", "0");
      $("#loginLink").css("opacity", "1");
    }
  });
</script>
</body>
</html>
