<?php
include "dbConnection.php";
include "userLogHelper.php";

if(checkIsLoggedIn() == false){
    header("Location: ../index.php");
}
?>
