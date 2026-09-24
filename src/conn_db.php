<?php

// Local XAMPP defaults. On Vercel, set these as environment variables instead.
$server = getenv('MYSQL_HOST') ?: 'localhost';
$username = getenv('MYSQL_USER') ?: 'root';
$password = getenv('MYSQL_PASSWORD');
if ($password === false) {
    $password = '';
}
$dbname = getenv('MYSQL_DATABASE') ?: 'crimsondb';
$port = getenv('MYSQL_PORT') ?: 3307;

// Create connection
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($server, $username, $password, $dbname, $port);
    $conn->set_charset('utf8mb4');
} catch (Exception $e) {
    error_log($e->getMessage());
    exit('Error connecting to database');
}