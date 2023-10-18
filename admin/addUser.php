<!--Start PHP-->
<?php
include_once("/home/joshb/website/final/slog/animal-welfare/fnc/auth/preventUnauthorizedUse.php");
include_once("/home/joshb/website/final/slog/animal-welfare/fnc/db/auth/databaseManager.php");

$database = new DatabaseManager;

$submittedEmail = $_POST['email'];
$submittedPassword = $_POST['password'];
$submittedAdminFlag = $_POST['admin'];

$hashedPassword = password_hash($submittedPassword, PASSWORD_DEFAULT);

$valueArr = array($submittedEmail, $hashedPassword, $submittedAdminFlag);
$statement = "INSERT INTO users(email, pass, administrator) VALUES (?, ?, ?);";
$database->runParameterizedQuery($statement, "ssi", $valueArr);

       // Redirect to home directory
header("Location: /home/joshb/website/final/slog/animal-welfare/admin/ui/admin.php");

        
?>

