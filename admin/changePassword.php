<?php
// This page is designed for normal users only, So it does not check if you're an admin.
include_once("../fnc/auth/preventUnauthorizedUse.php");
include_once("../fnc/db/auth/databaseManager.php");

$database = new DatabaseManager;

$submittedEmail = $_POST['email'];
$submittedPassword = $_POST['password'];
$submittedRetypedPassword = $_POST['retypedPassword'];

    // Check if the two passwords are the same
if($submittedPassword == $submittedRetypedPassword){
    $sql="UPDATE `users` SET `pass`=? WHERE `users`.`email`=?;";
    $hashedPassword = password_hash($submittedPassword, PASSWORD_DEFAULT);
    $database->runParameterizedQuery($sql, "ss", array($hashedPassword, $submittedEmail));

    $_SESSION['changePasswordError'] = "Password successfully changed.";
}else{ // If they dont match then we store the error in the session variable.
    $_SESSION['changePasswordError'] = "Passwords do not match!";
}

header("Location: ../user_changePassword.php");

?>