<?php

$servername = "localhost";
$database = "neric";
$username = "root";
$password = "";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_errno) {
    die("Connection error: " . $conn->connect_error);
}

return $conn;
?>
