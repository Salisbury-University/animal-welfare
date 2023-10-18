<?php
include_once("/home/joshb/website/final/slog/animal-welfare/fnc/auth/preventUnauthorizedUse.php");
include_once("/home/joshb/website/final/slog/animal-welfare/fnc/db/auth/databaseManager.php");

$database = new DatabaseManager;

$isAdmin = checkIsAdmin();
if($isAdmin == false){
    header('Location: /home/joshb/website/final/slog/animal-welfare/ui/model/home.php');
}

$submittedEmail = $_POST['email'];

$sql="DELETE FROM users WHERE users.email = ?;";
$result = $database->runParameterizedQuery($sql, "s", array($submittedEmail));

    // Commenting this debug code out in case its needed in the future.
// If the query fails, leave the user on the page.
/*if($result == false){
    echo "MYSQL query failed <br>";
    exit;
} else{
    echo "MSQL query successfull <br>";
}*/

// Redirect to home directory
header("Location: /home/joshb/website/final/slog/animal-welfare/admin/ui/admin.php");

?>