<?php
include_once("../db/auth/databaseManager.php");
$database = new DatabaseManager;

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
            $config->writeToConfigFile("recoveryAccountEnabled", "0", "1"); // Disable the account so it cant be used anymore

                // Set the session variables and log the recovery user in.
            $_SESSION['email'] = $recoveryAccountUsername;
            $_SESSION['administrator'] = 1;
            $_SESSION['isRecoveryAccount'] = 1;
            header("Location: ../../ui/home.php");
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
        header("Location: /home/joshb/website/final/slog/animal-welfare/ui/model/home.php"); 
        exit();
    }
}

$_SESSION['loginError'] = "Incorrect Password";
header("Location: /home/joshb/website/final/slog/animal-welfare/index.php");
