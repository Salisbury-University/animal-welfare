<?php
$databaseIP = "localhost";
$dbusername = "restricted_user";
$dbpassword = "j60oPoObT3PSnEvZ";
$dbName = "zooDB_OLD"; //temp until new database is done

if (session_status() != 2) // If session has not started yet, Start it.
    session_start();

// Create connection
$connection = mysqli_connect($databaseIP, $dbusername, $dbpassword, $dbName);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_write_close();
exit();
?>