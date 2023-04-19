<?php
session_start();

include "../Includes/loggedInUserHelper.php";
echo "Email: $_SESSION[email] <br>";

echo "Admin Flag: ";
$returnVar = checkIsAdmin();
if($returnVar == true){
    echo "true";
} else{
    echo "false";
}
echo "<br>";

$returnVar = checkIsLoggedIn();
echo "Is logged in: ";
if($returnVar == true){
    echo "true";
} else{
    echo "false";
}
echo "<br>";

?>