<?php
include "Includes/DatabaseConnection.php";

session_start();



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

$sql = "select * from useraccounts where username='$loginUsername' AND password_hash='$loginPassword';";
$result = mysqli_query($connection, $sql);

$row = mysqli_fetch_assoc($result);

if($row['username'] === $loginUsername && $row['password_hash'] === $loginPassword){
    echo "Logged in!";
    $_SESSION['username'] = $row['username'];
    header("Location: home.php"); 
    exit();
}
