<?php

include '../Includes/DatabaseConnection.php';

//If their is user input, add question to the database
if(isset($_POST['text'])) {
    //Inserts question
    $text = $_POST['text'];
    $sql = 'INSERT INTO `questions` (question) VALUES (:question)';
	$parameters = [':question' => $text];
	mysqli_query($connection, $sql, $parameters);

    //Sends user back to main Form page to see their addition
    header('location : Forms.php');

}

else {

    ##Needed for styling
    ob_start();
    include 'add.html';
    $output = ob_get_clean();
}

include 'FormsFrame.php';

?>