<?php
$databaseIP = "localhost";
$dbusername = "restricted_user";
$dbpassword = "j60oPoObT3PSnEvZ";
$dbName = "zooDB";

session_start();

// Create connection
$connection = mysqli_connect($databaseIP, $dbusername, $dbpassword, $dbName);

// Check connection
if ($connection->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
