<?php
$databaseIP = "localhost";
$dbusername = "rachel";
$dbpassword = "Kt0qvUB5QWzAkQqS";
$dbName = "Rachel";

session_start();

// Create connection
$connection = mysqli_connect($databaseIP, $dbusername, $dbpassword, $dbName);

// Check connection
if ($connection->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
