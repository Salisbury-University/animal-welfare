<?php
// require_once "../vendor/autoload.php";
// use admin\SessionUser;

require_once "../admin/SessionUser.php";

if (isset($_POST['subUser']) && isset($_POST['subPass'])) {
    $sessionUser = new SessionUser($_POST['subUser'], $_POST['subPass']);

    if ($sessionUser->checkIsLoggedIn() == true){
        $_SESSION['user'] = serialize($sessionUser);
        SessionUser::redirectUser("../ui/home.php");
    }else if($sessionUser->checkIsLoggedIn() == false){
        unset($sessionUser);
        SessionUser::redirectUser("../index.php");
    }
}

session_write_close();
exit();
?>