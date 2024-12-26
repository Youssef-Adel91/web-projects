<?php
// Database connection file: db_connection.php

$host = "localhost"; 
$db_name = "root"; 
$username = "root";
$password = ""; 

try {
    // Create PDO connection to the database
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If the connection fails, display an error message
    die("Connection failed: " . $e->getMessage());
}
?>
