<?php
// require_once "../vendor/autoload.php";
// use admin\SessionUser;

require_once "../admin/SessionUser.php";

SessionUser::sessionStatus();
$user = unserialize($_SESSION['user']);

if ($user->checkIsAdmin() == false) {
    header('Location: ../ui/home.php');
}

$user->openDatabase();

$animal = $_POST['id'];
echo "$animal";

$query = "DELETE FROM animals WHERE animals.id = ?;";
echo "$sql";
$result = $user->getDatabase()->runParameterizedQuery($query, "s", array($animal));

// Commenting this debug code out in case its needed in the future.
// If the query fails, leave the user on the page.
/*if($result == false){
    echo "MYSQL query failed <br>";
    exit;
} else{
    echo "MSQL query successfull <br>";
}*/

// Redirect to home directory
header("Location: ../ui/search.php");
session_write_close();
exit();
?>