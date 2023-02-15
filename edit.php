<?php
include 'includes/DatabaseConnection.php';
include 'includes/DatabaseFunctions.php';
try {
    if(isset($_POST['text'])) {
        updateQ($pdo, $_POST['questionid'], $_POST['text']);
        header('location: publicDisplay.php');
    }else{
        $question = getQ($pdo, $_GET['id']);
        $title = 'Edit question';

        ob_start();
        include 'templates/edit.html.php';
        $output = ob_get_clean();
    }
}catch (PDOException $e) {
    $title = 'An error has occured';
    $output = 'Error editing question: ' . $e->getMessage();
}
include 'templates/layout.html.php';