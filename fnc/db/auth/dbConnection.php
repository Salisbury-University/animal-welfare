<?php
$databaseIP = "localhost";
$dbusername = "restricted_user";
$dbpassword = "j60oPoObT3PSnEvZ";
$dbName = "zooDB";

if (session_status() != 2) // If session has not started yet, Start it.
    session_start();

// Create connection
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$connection = mysqli_connect($databaseIP, $dbusername, $dbpassword, $dbName);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

?>