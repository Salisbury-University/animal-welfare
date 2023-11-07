<?php
// require_once "../vendor/autoload.php";
// use admin\SessionUser;

require_once "../admin/SessionUser.php";

if (isset($_POST['subUser']) && isset($_POST['subPass'])) {
    $sessionUser = new SessionUser($_POST['subUser'], $_POST['subPass']);

    $_SESSION['user'] = serialize($sessionUser);

    if ($sessionUser->checkIsLoggedIn()){
        header("Location: ../ui/home.php");
    }
}

session_write_close();
exit();
?>