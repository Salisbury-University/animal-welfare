<?php
// require_once "../vendor/autoload.php";
// use admin\SessionUser;

require_once "../admin/SessionUser.php";
SessionUser::sessionStatus();

$user = unserialize($_SESSION['user']);
$user->openDatabase();

/*
    Redirects do not work with this script.

    My assumption is that its because search.php sends an AJAX request to run this script 
    with the given data as opposed to redirecting the user to this page.
    Instead of redirection we run die() to stop the script immediately if they dont
    have permission to do delete an animal.
*/
if ($user->checkIsAdmin() == false) {
    //SessionUser::redirectUser("../ui/home.php");
    die();
}

$animal = $_POST['id'];
echo "$animal";

$query = "DELETE FROM animals WHERE animals.id = ?;";
echo "$sql";
$result = $user->getDatabase()->runParameterizedQuery($query, "i", array($animal));

// Commenting this debug code out in case its needed in the future.
// If the query fails, leave the user on the page.
/*if($result == false){
    echo "MYSQL query failed <br>";
    exit;
} else{
    echo "MSQL query successfull <br>";
}*/

// Redirect to home directory
//SessionUser::redirectUser("../ui/search.php");
session_write_close();
exit();
?>