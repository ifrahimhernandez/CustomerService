<?php
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=customer_service_experts", $dbUsername, $dbPassword);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully"; 
    }
catch(PDOException $e)
    {
    //echo "Connection failed: " . $e->getMessage();
    }
?>