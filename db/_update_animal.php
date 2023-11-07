<!--Start PHP-->
<?php
require_once "../vendor/autoload.php";

use admin\SessionUser;

SessionUser::sessionStatus();

$user = unserialize($_SESSION['user']);

$id = $_POST['id'];
$submittedSection = $_POST['location'];
$submittedName = $_POST['name'];

echo $id . '<br>';
echo $submittedSection . '<br>';
echo $submittedName . '<br>';


if ($submittedSection != NULL && $submittedName != NULL) { //modify location and name
    $query = "UPDATE `animals` SET `section`=?, `name`=? WHERE `animals`.`id`=?;";
    $result = $user->getDatabase()->runParameterizedQuery($query, "ssi", array($submittedSection, $submittedName, $id));
} elseif ($submittedName != NULL) { //modify name
    $query = "UPDATE `animals` SET `name`=? WHERE `animals`.`id`=?;";
    $result = $user->getDatabase()->runParameterizedQuery($query, "si", array($submittedName, $id));
} elseif ($submittedSection != NULL) { // modify location
    $query = "UPDATE `animals` SET `section`=? WHERE `animals`.`id`=?;";
    $result = $user->getDatabase()->runParameterizedQuery($query, "si", array($submittedSection, $id));
} elseif ($submittedName == NULL && $submittedSection == NULL) {
    header("Location: ../search.php"); // Do nothing and return to the search page if the form is left blank
} else { // Should never arrive here.
    $query = NULL;
    echo "Section: $submittedSection - Name: $submittedName";
    die("An unknown error occurred");
}

// Redirect to home directory
header("Location: ../search.php");
session_write_close();
exit();
?>