<?php
include "../Includes/preventUnauthorizedUse.php";


$animal = $_GET['id'];

echo $animal;

$sql="DELETE FROM animals WHERE animals.id = '$animal';";
$result = mysqli_query($connection, $sql);

// If the query fails, leave the user on the page.
if($result == false){
    echo "MYSQL query failed <br>";
    exit;
} else{
    echo "MSQL query successfull <br>";
}

// Redirect to home directory
header("Location: ../search.php");

?>
