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

$query = "DELETE FROM animals WHERE animals.id = ?;";
$result = $user->getDatabase()->runParameterizedQuery($query, "i", array($animal));

// Redirect to home directory
//SessionUser::redirectUser("../ui/search.php");
session_write_close();
exit();
?>