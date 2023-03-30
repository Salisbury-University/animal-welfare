<?php
$databaseIP = "localhost";
$dbusername = "rachelp";
$dbpassword = "XW1b17ltQJN4EQ2d";
$dbName = "zooDB";

session_start();

// Create connection
$connection = mysqli_connect($databaseIP, $dbusername, $dbpassword, $dbName);

// Check connection
if ($connection->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
