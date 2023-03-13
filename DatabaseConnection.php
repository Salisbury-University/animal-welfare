<?php
$servername = 'localhost:3306';
$username = 'rachel';
$password = 'Kt0qvUB5QWzAkQqS';
$dbname = 'Rachel';

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

##if ($conn)
 ## echo "oo";

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>