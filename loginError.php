<?php
// You include this file in order to run the code. 
// All it will do is print the information in a session variable to the user.

if(session_status() !== 2) // If the session has not started yet, start it.
    session_start();

if(isset($_SESSION['loginError']) == true){
    $temp = $_SESSION['loginError'];
    $_SESSION['loginError'] = NULL; // Clear out the session variable
    echo "<body style='color: white;'> $temp </body>";
}

?>