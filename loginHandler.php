<?php
include_once("Includes/databaseManipulation.php");
$database = new databaseManipulation;

    // Get the form data
$submittedEmail = $_POST['submittedEmail'];
$submittedPassword = $_POST['submittedPassword'];

    // Get config so we can read the info for the recovery account
$config = new configHandler;
$configFile = $config->readConfigFile();

    // Grab recovery account info
$recoveryAccountEnabled = $configFile['recoveryAccountEnabled'];
$recoveryAccountUsername = $configFile['recoveryAccountUsername'];
$recoveryAccountPassword = $configFile['recoveryAccountPassword'];

    // Check to see if the details are for the recovery account
if($recoveryAccountEnabled == 1){
    if($submittedEmail == $recoveryAccountUsername){
        if($submittedPassword == $recoveryAccountPassword){
            $config->disableDefaultAccountFlag(); // Disable the account so it cant be used anymore
            
                // Set the session variables and log the recovery user in.
            $_SESSION['email'] = "RECOVERYACCOUNT_" . $recoveryAccountUsername;
            $_SESSION['administrator'] = 1;
            header("Location: home.php");
            exit();
        }
    }
}

    // Prepare value array
$loginDetails = array($submittedEmail);

    // Query the database, get results and format them so we can easily access it.
$statement = "select * from users where email=?";
$result = $database->runParameterizedQuery($statement, "s", $loginDetails);
$row = mysqli_fetch_assoc($result);

if($row != NULL){
        // Verify the password
    if(password_verify($submittedPassword, $row['pass']) == true){
            // Set session variables and redirect to the home page
        $_SESSION['email'] = $row['email'];
        $_SESSION['administrator'] = $row['administrator'];
        header("Location: home.php"); 
        exit();
    }
}

$_SESSION['loginError'] = "Incorrect Password";
header("Location: index.php");
