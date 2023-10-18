<?php
session_start();
$_SESSION = array(); // Clear session data

// Delete the cookies from the users browser
if(ini_get("session.use_cookies")){
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

    // Destroy the session
session_destroy();

    // Redirect to the login page.
header('Location: /home/joshb/website/final/slog/animal-welfare/index.php');
exit;
?>