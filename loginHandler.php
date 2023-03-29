<?php
$databaseIP = "localhost";
$dbusername = "restricted_user";
$dbpassword = "j60oPoObT3PSnEvZ";
$dbName = "test_useraccounts";

session_start();

$connection = mysqli_connect($databaseIP, $dbusername, $dbpassword, $dbName);

function validateData($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$submittedUsername = $_POST['submittedUsername'];
$submittedPassword = $_POST['submittedPassword'];

$loginUsername = validateData($submittedUsername);
$loginPassword = validateData($submittedPassword);

echo "Incorrect Password<br><br>";
echo "Details provided for debugging purposes:<br>";
echo "Login Username: $loginUsername <br>";
echo "Login Password: $loginPassword <br>";

$sql = "select * from users where username='$loginUsername' AND password='$loginPassword';";
$result = mysqli_query($connection, $sql);

$row = mysqli_fetch_assoc($result);

if($row['username'] === $loginUsername && $row['password'] === $loginPassword){
    echo "Logged in!";
    $_SESSION['username'] = $row['username'];
    header("Location: home.php"); 
    exit();
}
