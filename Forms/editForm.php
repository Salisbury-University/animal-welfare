<?php

include '../Includes/DatabaseConnection.php';

//If their is user input, add question to the database
if(isset($_POST['text'])) {
    //Updates question
    $text = $_POST['text'];
    $sql = 'UPDATE `questions` SET text = :text WHERE id = :id';
	$parameters = [':text' => $text, ':id' => $questionId];
	mysqli_query($connection, $sql, $parameters);

    //Sends user back to main Form page to see their addition
    header('location : Forms.php');
}

else {
     ##Intitialize question variable with selected question
    $parameters = [':id' => $id];
    $sql = 'SELECT * FROM question WHERE id = :id';
	$question = mysqli_query($connection, $sql, $parameters);

    ##Needed for styling
    ob_start();
    include 'editForm.html';
    $output = ob_get_clean();
}

include 'FormsFrame.php';

?>