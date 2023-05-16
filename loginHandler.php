<?php
include "Includes/DatabaseConnection.php";

    // Function used to sanitize inputs
function validateData($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
    // Get the form data
$submittedEmail = $_POST['submittedEmail'];
$submittedPassword = $_POST['submittedPassword'];

    // Sanitize the input data
$loginEmail = validateData($submittedEmail);
$loginPassword = validateData($submittedPassword);

    // Query the database, get results and format them so we can easily access it.
$sql = "select * from users where email='$loginEmail';";
$result = mysqli_query($connection, $sql);
$row = mysqli_fetch_assoc($result);

if($row != NULL){
        // Verify the password
    if(password_verify($loginPassword, $row['pass']) == true){
        echo "Logged in!";
        $_SESSION['email'] = $row['email'];
        $_SESSION['administrator'] = $row['administrator'];
        header("Location: home.php"); 
        exit();
    }
}

$_SESSION['loginError'] = "Incorrect Password";
header("Location: index.php");

