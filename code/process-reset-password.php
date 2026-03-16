<?php

$token = filter_input(INPUT_POST, "token");

if (!$token) {
    die("Invalid token");
}

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/database.php";

$sql = "SELECT * FROM signup WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if (!$user) {
    die("Token not found");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("Token has expired");
}

$password = filter_input(INPUT_POST, "password");
$password_confirmation = filter_input(INPUT_POST, "password_confirmation");

if (!$password || !$password_confirmation) {
    die("Password and password confirmation are required");
}

if ($password !== $password_confirmation) {
    die("Passwords must match");
}

if (strlen($password) < 4 || !preg_match("/[a-zA-Z]/", $password) || !preg_match("/[0-9]/", $password)) {
    die("Password must be at least 4 characters long and contain at least one letter and one number");
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "UPDATE signup
        SET password_db = ?,
            reset_token_hash = NULL,
            reset_token_expires_at = NULL
        WHERE id_db = ?";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("si", $password_hash, $user["id_db"]);

$stmt->execute();

echo "Password updated. You can now login.";
