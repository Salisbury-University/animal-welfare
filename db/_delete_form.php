<?php
// require_once "../vendor/autoload.php";
// use admin\SessionUser;

require_once "../admin/SessionUser.php";
SessionUser::sessionStatus();

$user = unserialize($_SESSION['user']);
$user->openDatabase();

if (isset($_POST['formid']) && isset($_POST['secid']) && isset($_POST['questid'])) {
    $formID = $_POST['formid'];
    $sectionID = $_POST['secid'];
    $questionID = $_POST['questid'];

    $query =
        $result = $user->getDatabase()->runParameterizedQuery($query, "iii", $formID, $sectionID, $questionID);

    $success = "Deleted question successfully";
    echo json_encode($success);
}
session_write_close();
exit();
?>
