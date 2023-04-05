<?php
session_start();
include "Includes/DatabaseConnection.php";
include "Includes/loggedInUserHelper.php";
$isAdmin = checkIsAdmin();
if($isAdmin == false){
    header('Location: home.php');
}

echo "admin_createUser.php script <br>";

//TODO: Check if currently logged in user has the admin flag (To prevent unprivileged users from using it)
$submittedEmail = $_POST['email'];
$submittedPassword = $_POST['password'];
$submittedAdminFlag = $_POST['admin'];

echo "DEBUG: '$submittedUsername' - '$submittedEmail' - '$submittedPassword' - '$submittedAdminFlag' <br>";
$sql="INSERT INTO users(email, pass, administrator) VALUES ('$submittedEmail', '$submittedPassword', $submittedAdminFlag);";
$result = mysqli_query($connection, $sql);

// If the query fails, leave the user on the page.
// This will need to be changed later.
if($result == false){
    echo "MYSQL query failed <br>";
    exit;
} else{
    echo "MSQL query successfull <br>";
}

// Redirect to home directory
header("Location: admin.php");

?>