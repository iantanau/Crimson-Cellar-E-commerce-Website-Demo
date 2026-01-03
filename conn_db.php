<?php

$server = "localhost";
$username = "root";
$password = "";
$dbname = "crimsondb";
$port = 3307;

// Create connection
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try{
    $conn = new mysqli($server, $username, $password, $dbname, $port);    
} catch (Exception $e) {
    error_log($e->getMessage());
    exit("Error connecting to database");
}
?>