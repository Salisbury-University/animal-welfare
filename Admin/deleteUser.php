<?php
include "../Includes/preventUnauthorizedUse.php";

$isAdmin = checkIsAdmin();
if($isAdmin == false){
    header('Location: ../home.php');
}

$submittedEmail = $_POST['email'];
echo $submittedEmail;


$sql="DELETE FROM users WHERE users.email = '$submittedEmail';";
$result = mysqli_query($connection, $sql);

// If the query fails, leave the user on the page.
if($result == false){
    echo "MYSQL query failed <br>";
    exit;
} else{
    echo "MSQL query successfull <br>";
}

// Redirect to home directory
header("Location: ../admin.php");

?>