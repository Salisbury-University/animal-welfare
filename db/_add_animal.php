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

if ($submittedID == NULL || $submittedSection == NULL || $submittedSpecies == NULL) { //if any of these are NULL, kicks them back
    echo 'Error';
    header("Location: ../ui/createAnimal.php");
} else {
    $query = "SELECT `id` FROM `species` WHERE `id` = ?";
    $speciesP = $user->getDatabase()->runParameterizedQuery($query,"s",array($submittedSpecies));
    $species = $speciesP->fetch_array(MYSQLI_ASSOC);

    if ($species != NULL) { //If species exists already
        $query = "INSERT INTO `animals` (`id`, `section`, `sex`, `birth_date`, `acquisition_date`, `species_id`, `name`)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    } else { //Insert species first
        if ($submittedForm != NULL) { //Make sure they selected a form 
            $query = "INSERT INTO `species` (`id`, `form_id`) VALUES (?, ?)";
            $speciesR = $user->getDatabase()->runParameterizedQuery($query,"si",array($submittedSpecies, $submittedForm));

            $sql = "INSERT INTO `animals` (`id`, `section`, `sex`, `birth_date`, `acquisition_date`, `species_id`, `name`)
            VALUES ('$submittedID', '$submittedSection', '$submittedSex', '$submittedBirth', '$submittedAc', '$submittedSpecies', '$submittedName')";
        }
    }
}

$result = $user->getDatabase()->runParameterizedQuery($query,"issssss",array($submittedSpecies, $submittedForm));

// Redirect to home directory
header("Location: ../ui/search.php");

session_write_close();
exit();
?>