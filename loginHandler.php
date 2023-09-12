<?php
include_once("Includes/databaseManipulation.php");

$database = new databaseManipulation;

    // Get the form data
$submittedEmail = $_POST['submittedEmail'];
$submittedPassword = $_POST['submittedPassword'];

    // Prepare value array
$loginDetails = array($submittedEmail);

    // Query the database, get results and format them so we can easily access it.
$statement = "select * from users where email=?";
$result = $database->runParameterizedQuery($statement, "s", $loginDetails);
$row = mysqli_fetch_assoc($result);

if($row != NULL){
        // Verify the password
    if(password_verify($submittedPassword, $row['pass']) == true){
        echo "Logged in!";
        $_SESSION['email'] = $row['email'];
        $_SESSION['administrator'] = $row['administrator'];
        header("Location: home.php"); 
        exit();
    }
}

$_SESSION['loginError'] = "Incorrect Password";
header("Location: index.php");
