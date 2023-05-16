<!--Start PHP-->
<?php
include "../Includes/DatabaseConnection.php";
include "../Includes/preventUnauthorizedUse.php";
        
$submittedEmail = $_POST['email'];
$submittedPassword = $_POST['password'];
$submittedAdminFlag = $_POST['admin'];

        // Hash the password
$temp = password_hash($submittedPassword, PASSWORD_DEFAULT);
$submittedPassword = $temp;

if($submittedPassword != NULL && $submittedAdminFlag != NULL){
        $sql="UPDATE `users` SET `pass`='$submittedPassword', `administrator`=b'$submittedAdminFlag' WHERE `users`.`email`='$submittedEmail';";
}elseif($submittedPassword == NULL && $submittedAdminFlag != NULL){ // Only want to modify the admin flag
        $sql="UPDATE `users` SET `administrator`=b'$submittedAdminFlag' WHERE `users`.`email`='$submittedEmail';";
}elseif($submittedPassword != NULL && $submittedAdminFlag == NULL){ // Only want to update the password
        $sql="UPDATE `users` SET `pass`='$submittedPassword' WHERE `users`.`email`='$submittedEmail';";
}elseif($submittedPassword == NULL && $submittedAdminFlag == NULL){
        header("Location: ../admin.php"); // Do nothing and return to the admin page if the form is left blank
}else{ // Should never arrive here.
        $sql = NULL;
        echo "Email: $submittedEmail - Password: $submittedPassword - AdminFlag: $submittedAdminFlag";
        die("An unknown error occurred");
}

$result = mysqli_query($connection, $sql);

        // Redirect to home directory
header("Location: ../admin.php");

        
?>

