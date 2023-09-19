<!--Start PHP-->
<?php
include "../Includes/DatabaseConnection.php";
include "../Includes/preventUnauthorizedUse.php";
        
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

if ($submittedID == NULL || $submittedSection == NULL || $submittedSpecies == NULL ) {//if any of these are NULL, kicks them back
    echo 'Error';
    header("Location: ../createAnimal.php");

}
//check if species exists already otherwise insert that first
else{ 
        $sql = "SELECT `id` FROM `species` WHERE `id` = '$submittedSpecies'";
        $species = mysqli_query($connection, $sql);
        $species = mysqli_fetch_array($species);
        
        if ($species !=NULL) { //If species exists already
                $sql = "INSERT INTO `animals` (`id`, `section`, `sex`, `birth_date`, `acquisition_date`, `species_id`, `name`)
                VALUES ('$submittedID', '$submittedSection', '$submittedSex', '$submittedBirth', '$submittedAc', '$submittedSpecies', '$submittedName')";
        }
        else { //Insert species first
                if ($submittedForm != NULL) { //Make sure they selected a form 
                $sql = "INSERT INTO `species` (`id`, `form_id`) VALUES ('$submittedSpecies', '$submittedForm')";
                $species = mysqli_query($connection, $sql); //Inserts species

                $sql = "INSERT INTO `animals` (`id`, `section`, `sex`, `birth_date`, `acquisition_date`, `species_id`, `name`)
                VALUES ('$submittedID', '$submittedSection', '$submittedSex', '$submittedBirth', '$submittedAc', '$submittedSpecies', '$submittedName')";
                }        
        }
}

$result = mysqli_query($connection, $sql);

// Redirect to home directory
header("Location: ../search.php");

        
?>