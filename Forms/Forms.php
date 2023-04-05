<?php
include '../Includes/DatabaseConnection.php';
include 'FormFunctions.php';

##Initializes sql variable
$sql = "SELECT * FROM `questions`;";
##Initializes questions variable with all questions and ids
$questions = mysqli_query($connection, $sql);


##Remove
ob_start();
include 'Forms.html';
$output = ob_get_clean();

##Needed for styling
include 'FormsFrame.php';
?>