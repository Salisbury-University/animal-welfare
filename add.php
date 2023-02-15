<?php
if(isset($_POST['text'])) {
    try{
        include 'includes/DatabaseConnection.php';
        include 'includes/DatabaseFunctions.php';
        insertQ($pdo, $_POST['text'], $_POST['authors'], $_POST['categories']);
        header('location : publicDisplay.php');
    }catch (PDOException $e) {
        $title = 'An error has occured';
        $output = 'Unable to connect to add question: ' . $e->getMessage();
    }
}else {
    include 'includes/DatabaseConnection.php';
    include 'includes/DatabaseFunctions.php';
    $title = 'Submit a new question';
    $authors = allAuthors($pdo);
    $categories = allCategories($pdo);
    ob_start();
    include 'templates/add.html.php';
    $output = ob_get_clean();
}

include 'templates/layout.html.php';