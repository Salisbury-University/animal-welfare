<?php
include "DatabaseConnection.php";
include "loggedInUserHelper.php";

if(checkIsLoggedIn() == false){
    header("Location: ../index.php");
}

?>