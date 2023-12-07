<?php
// require_once "../vendor/autoload.php";
// use admin\SessionUser;

require_once "../admin/SessionUser.php";
SessionUser::sessionStatus();

$user = unserialize($_SESSION['user']);
$user->openDatabase();

$submittedID = $_POST['id'];
$submittedSection = $_POST['location'];
$submittedSex = $_POST['Sex'];
$submittedBirth = $_POST['bd'];
$submittedSpecies = $_POST['species'];
$submittedAc = $_POST['ad'];
$submittedName = $_POST['name'];
$submittedForm = $_POST['form'];

echo $submittedID . '<br>';
echo $submittedSection . '<br>';
echo $submittedSex . '<br>';
echo $submittedBirth . '<br>';
echo $submittedSpecies . '<br>';
echo $submittedAc . '<br>';
echo $submittedName . '<br>';
echo $submittedForm . '<br>';

//checks duplicate entry
$query = "SELECT * FROM `animals` WHERE `id` = ?";
$checks = $user->getDatabase()->runParameterizedQuery($query,"i",array($submittedID));
$check = $checks->fetch_array(MYSQLI_ASSOC);

if ($check != NULL) {
    echo 'Error';
    SessionUser::redirectUser("../ui/createAnimal.php");
}

if ($submittedID == NULL || $submittedSection == NULL || $submittedSpecies == NULL) { //if any of these are NULL, kicks them back
    echo 'Error';
    SessionUser::redirectUser("../ui/createAnimal.php");
} else {
    $query = "SELECT `id` FROM `species` WHERE `id` = ?";
    $speciesP = $user->getDatabase()->runParameterizedQuery($query,"s",array($submittedSpecies));
    $species = $speciesP->fetch_array(MYSQLI_ASSOC);

    if ($species != NULL) { //If species exists already
        $query = "INSERT INTO `animals` (`id`, `section`, `sex`, `birth_date`, `acquisition_date`, `species_id`, `name`)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

        $user->getDatabase()->runParameterizedQuery($query,"issssss", array($submittedID, $submittedSection, $submittedSex, $submittedBirth, $submittedAc, $submittedSpecies, $submittedName));
    } else { //Insert species first
        if ($submittedForm != NULL) { //Make sure they selected a form 
            $query = "INSERT INTO `species` (`id`, `form_id`) VALUES (?, ?)";
            $speciesR = $user->getDatabase()->runParameterizedQuery($query,"si",array($submittedSpecies, $submittedForm));

            $query = "INSERT INTO `animals` (`id`, `section`, `sex`, `birth_date`, `acquisition_date`, `species_id`, `name`) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $user->getDatabase()->runParameterizedQuery($query,"issssss", array($submittedID, $submittedSection, $submittedSex, $submittedBirth, $submittedAc, $submittedSpecies, $submittedName));
        }
    }

}

// Redirect to home directory
SessionUser::redirectUser("../ui/search.php");


session_write_close();
exit();
?>
