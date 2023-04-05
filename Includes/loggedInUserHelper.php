<?php

function checkIsAdmin(){
    if($_SESSION[administrator] == 1){
        return true;
    }
    return false;
}

function checkIsLoggedIn(){

}

?>
