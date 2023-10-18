<?php
include_once("loggedInUserHelper.php");

if(session_status() != 2) // If session has not started yet, Start it.
    session_start();

if(checkIsLoggedIn() == false){
    header("Location: /home/joshb/website/final/slog/animal-welfare/index.php");
}

?>