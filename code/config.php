<?php
// Database connection settings
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "neric";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
      
?>