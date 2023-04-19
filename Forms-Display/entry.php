<?php
include '../Includes/DatabaseConnection.php';

$formID = 3;

$sql = "SELECT title FROM `sections`";
$sections = mysqli_query($connection, $sql);

$sql = "SELECT title, hsq.id
from sections s
join hasSectionQuestions hsq on s.id =hsq.section_id
WHERE hsq.form_id = 3";
$all = mysqli_query($connection, $sql);

$getQ = $connection->prepare("SELECT q.question, hsq.id
from questions q
join hasSectionQuestions hsq on q.id = hsq.question_id
where hsq.section_id = ? and hsq.form_id = 3");

$getQ->bind_param("s", $qs);   

#Include html
ob_start();
include 'entry.html';
$output = ob_get_clean();

##Needed for styling
include 'entry_frame.php';
include "../Includes/preventUnauthorizedUse.php";
?>
