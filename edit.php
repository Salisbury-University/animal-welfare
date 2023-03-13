<?php
include 'Includes/DatabaseConnection.php';
include 'Includes/DatabaseFunctions.php';

##initialize forms variable to get data from database
$forms = get($conn);

##Needed for styling
ob_start();
include 'edit.html';
$output = ob_get_clean();
include 'header.php';
?>
