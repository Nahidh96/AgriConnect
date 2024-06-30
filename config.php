<?php

$servername = "db"; // Docker Compose service name
$username = "nahidh"; // MySQL user
$password = "password"; // MySQL password
$database = "shop_db"; // MySQL database name

// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
