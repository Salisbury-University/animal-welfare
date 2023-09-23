<!--Start PHP-->
<?php
include_once("../Includes/preventUnauthorizedUse.php");
include_once("../Includes/databaseManipulation.php");

$database = new databaseManipulation;

$submittedEmail = $_POST['email'];
$submittedPassword = $_POST['password'];
$submittedAdminFlag = $_POST['admin'];

$hashedPassword = password_hash($submittedPassword, PASSWORD_DEFAULT);

$valueArr = array($submittedEmail, $hashedPassword, $submittedAdminFlag);
$statement = "INSERT INTO users(email, pass, administrator) VALUES (?, ?, ?);";
$database->runParameterizedQuery($statement, "ssi", $valueArr);

       // Redirect to home directory
header("Location: ../admin.php");

        
?>

