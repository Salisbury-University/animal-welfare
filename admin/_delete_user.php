<?php
// use admin\SessionUser;

require_once "../admin/SessionUser.php";

SessionUser::sessionStatus();
$user = unserialize($_SESSION['user']);

if (isset($_POST['email'])) {
    $user->deleteUser($_POST['email']);
}
session_write_close();
exit();
?>