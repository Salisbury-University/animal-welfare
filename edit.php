<?php
include 'Includes/DatabaseConnection.php';

##Initializes forms variable
$sql = "SELECT * FROM `Forms`;";
$forms = mysqli_query($connection, $sql);

##Needed for styling
ob_start();
include 'edit.html';
$output = ob_get_clean();
include 'page_frame.php';
?>
