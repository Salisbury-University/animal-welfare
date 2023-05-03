<?php
include "../Includes/DatabaseConnection.php";
include "../Includes/loggedInUserHelper.php";

if(checkIsLoggedIn() == false){
    header("Location: ../index.php");
}

?>