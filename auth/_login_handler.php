<?php
// require_once "../vendor/autoload.php";
// use admin\SessionUser;

require_once "../admin/SessionUser.php";
SessionUser::sessionStatus();

if (isset($_POST['subUser']) && isset($_POST['subPass'])) {
    $sessionUser = new SessionUser($_POST['subUser'], $_POST['subPass']);

        // If the login failed then checkIsLoggedIn will return false, due to the username not being set.
    if ($sessionUser->checkIsLoggedIn() == true){
        $_SESSION['user'] = serialize($sessionUser);
        SessionUser::redirectUser("../ui/home.php");
    }else if($sessionUser->checkIsLoggedIn() == false){
        unset($sessionUser);
        SessionUser::redirectUser("../index.php");
    }
}

exit();
?>