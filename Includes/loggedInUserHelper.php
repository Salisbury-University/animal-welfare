<?php

function checkIsAdmin(){
    if($_SESSION['administrator'] == 1){
        return true;
    }
    return false;
}

function checkIsLoggedIn(){
    if(isset($_SESSION['email']) == false){
        return false;
    }
    return true;
}

?>
