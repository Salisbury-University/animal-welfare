<?php
// You include this file in order to run the code. 
// All it will do is print the information in a session variable to the user.

if(session_status() !== 2) // If the session has not started yet, start it.
    session_start();

if(isset($_SESSION['changePasswordError']) == true){
    $temp = $_SESSION['changePasswordError'];
    $_SESSION['changePasswordError'] = NULL; // Clear out the session variable
    echo "<body> $temp </body>";
}

?>