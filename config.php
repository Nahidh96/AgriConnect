<?php

$servername = "localhost"; // or the remote host name if applicable
$username = "agriconn_nahidh"; // MySQL user
$password = "Gp2351532!"; // MySQL password
$database = "agriconn_agriconnect_db"; // MySQL database name

// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Below section is for Docker, XAMPP, or WAMP, uncomment if needed
/*
$servername = "db"; // Use "db" for Docker, "localhost" for XAMPP
$username = "nahidh"; // MySQL user
$password = "password"; // MySQL password
$database = "agriconnect_db"; // MySQL database name

// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
*/

?>