<?php
include_once("../Includes/preventUnauthorizedUse.php");
include_once("../Includes/databaseManipulation.php");

$database = new databaseManipulation;

$submittedEmail = $_POST['email'];
$submittedPassword = $_POST['password'];
$submittedAdminFlag = $_POST['admin'];

        // Hash the password
$hashedPassword = password_hash($submittedPassword, PASSWORD_DEFAULT);

if($submittedPassword != NULL && $submittedAdminFlag != NULL){
        $sql="UPDATE `users` SET `pass`=?, `administrator`=? WHERE `users`.`email`=?;";
        $database->runParameterizedQuery($sql, "sbs", array($hashedPassword, $submittedAdminFlag, $submittedEmail));

}elseif($submittedPassword == NULL && $submittedAdminFlag != NULL){ // Only want to modify the admin flag
        $sql="UPDATE `users` SET `administrator`=? WHERE `users`.`email`=?;";
        $database->runParameterizedQuery($sql, "bs", array($submittedAdminFlag, $submittedEmail));

}elseif($submittedPassword != NULL && $submittedAdminFlag == NULL){ // Only want to update the password
        $sql="UPDATE `users` SET `pass`=? WHERE `users`.`email`=?;";
        $database->runParameterizedQuery($sql, "ss", array($hashedPassword, $submittedEmail));

}elseif($submittedPassword == NULL && $submittedAdminFlag == NULL){
        header("Location: ../admin.php"); // Do nothing and return to the admin page if the form is left blank
}else{ // Should never arrive here.
        $sql = NULL;
        echo "Email: $submittedEmail - Password: $submittedPassword - AdminFlag: $submittedAdminFlag";
        die("An unknown error occurred");
}

        // Redirect to home directory
header("Location: ../admin.php");

        
?>

