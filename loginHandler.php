<?php
include "Includes/DatabaseConnection.php";

session_start();

function validateData($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$submittedEmail = $_POST['submittedEmail'];
$submittedPassword = $_POST['submittedPassword'];

$loginEmail = validateData($submittedEmail);
$loginPassword = validateData($submittedPassword);

echo "Incorrect Password<br><br>";
echo "Details provided for debugging purposes:<br>";
echo "Login Email: $loginEmail <br>";
echo "Login Password: $loginPassword <br>";

//$sql = "select * from useraccounts where username='$loginUsername' AND password_hash='$loginPassword';";
$sql = "select * from users where email='$loginEmail' AND pass='$loginPassword';";
$result = mysqli_query($connection, $sql);

$row = mysqli_fetch_assoc($result);

if($row['email'] === $loginEmail && $row['pass'] === $loginPassword){
    echo "Logged in!";
    $_SESSION['email'] = $row['email'];
    $_SESSION['administrator'] = $row['administrator'];
    header("Location: home.php"); 
    exit();
}
