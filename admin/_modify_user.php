<?php
// use admin\SessionUser;
require_once "../admin/SessionUser.php";

SessionUser::sessionStatus();
$user = unserialize($_SESSION['user']);

if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['admin'])) {
    $user->modifyUser($_POST['email'], $_POST['password'], $_POST['admin']);
}

session_write_close();
exit();
?>