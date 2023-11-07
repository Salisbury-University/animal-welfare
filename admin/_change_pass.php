<?php
// use admin\SessionUser;

require_once "../admin/SessionUser.php";

SessionUser::sessionStatus();
$user = unserialize($_SESSION['user']);

if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password2'])){
    $user->changePassword($_POST['email'],$_POST['password'],$_POST['password2']);
}

session_write_close();
exit();
?>