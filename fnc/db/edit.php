<!--Start PHP-->
<?php
include "auth/dbConnection.php";
include "../auth/preventUnauthorizedUse.php";
        
$id = $_POST['id'];
$submittedSection = $_POST['location'];
$submittedName = $_POST['name'];

echo $id . '<br>';
echo $submittedSection . '<br>';
echo $submittedName . '<br>';

if($submittedSection != NULL && $submittedName != NULL){ //modify location and name
        $sql="UPDATE `animals` SET `section`='$submittedSection', `name`='$submittedName' WHERE `animals`.`id`='$id';";
}elseif($submittedName != NULL){ //modify name
        $sql="UPDATE `animals` SET `name`='$submittedName' WHERE `animals`.`id`='$id';";
}elseif($submittedSection != NULL){ // modify location
        $sql="UPDATE `animals` SET `section`='$submittedSection' WHERE `animals`.`id`='$id';";
}elseif($submittedName == NULL && $submittedSection == NULL){
        header("Location: ../../ui/search.php"); // Do nothing and return to the search page if the form is left blank
}else{ // Should never arrive here.
        $sql = NULL;
        echo "Section: $submittedSection - Name: $submittedName";
        die("An unknown error occurred");
}

$result = mysqli_query($connection, $sql);

        // Redirect to home directory
header("Location: ../../ui/search.php");

        
?>

