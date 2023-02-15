<?php
if(isset($_POST['text'])) {
    try{
        include 'includes/DatabaseConnection.php';
        include 'includes/DatabaseFunctions.php';
        insertC($pdo, $_POST['text'], $_POST['email'], $_POST['authors'], $_POST['categories']);
        header('location : publicDisplay.php');
    }catch (PDOException $e) {
        $title = 'An error has occured';
        $output = 'Unable to open form: ' . $e->getMessage();
    }
}else {
    include 'includes/DatabaseConnection.php';
    include 'includes/DatabaseFunctions.php';
    $title = 'Contact Form';
    $authors = allAuthors($pdo);
    $categories = allCategories($pdo);
    ob_start();
    include 'templates/contact.html.php';
    $output = ob_get_clean();
}

include 'templates/layout.html.php';