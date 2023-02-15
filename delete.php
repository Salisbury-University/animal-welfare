<?php
try{
    include 'includes/DatabaseConnection.php';
    include 'includes/DatabaseFunctions.php';
    deleteQ($pdo, $_POST['id']);
    header('location: publicDisplay.php');

}catch (PDOException $e) {
    $title = 'An error has occured';
    $output = 'Unable to connect to delete question: ' . $e->getMessage();
}
include 'templates/layout.html.php';