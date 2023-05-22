<!--Start PHP-->
<?php
       include "../Includes/preventUnauthorizedUse.php";
        

       $submittedEmail = $_POST['email'];
       $submittedPassword = $_POST['password'];
       $submittedAdminFlag = $_POST['admin'];

       $hashedPassword = password_hash($submittedPassword, PASSWORD_DEFAULT);

       $sql="INSERT INTO users(email, pass, administrator) VALUES ('$submittedEmail', '$hashedPassword', $submittedAdminFlag);";
       $result = mysqli_query($connection, $sql);
       
           
       // Redirect to home directory
       header("Location: ../admin.php");

        
?>

