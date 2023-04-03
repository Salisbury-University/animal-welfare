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
##Remove

/*functionality:
try {
    ##If text is changed for the question
    if(isset($_POST['text'])) {
        ##Calls update function
        updateQ($connection, $_POST['id'], $_POST['text']);

        ##header sends user to same page so they can continue to edit and see their changes
        header('location: Forms.php');
    }else{
        $question = getQ($connection, $_GET['id']);
        $title = 'Edit question';

        ##Including HTML
        ob_start();
        include 'Forms.html';
        $output = ob_get_clean();
    }
}catch (PDOException $e) {
    $title = 'An error has occured';
    $output = 'Error editing question: ' . $e->getMessage();
} */
##Needed for styling
include 'FormsFrame.php';
?>