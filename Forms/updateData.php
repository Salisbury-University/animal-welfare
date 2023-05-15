<?php
include '../Includes/DatabaseConnection.php';

if ($connection->connect_error) {
    $database = "Database Connection Error.";
    echo json_encode($database);
}

if(isset($_POST['text']) && isset($_POST['id'])){
    $text = mysqli_real_escape_string($connection,$_POST['text']);
    $id = mysqli_real_escape_string($connection,$_POST['id']);
    
    if(mysqli_query($connection,"UPDATE questions SET question='$text'WHERE id='$id'")){
        $success = "Data inserted successfully. #Text= " . $text ." #ID= " . $id;
        echo json_encode($success);
    } else {
        $error = "Error somewhere during insert. #Text= " . $text ." #ID= " . $id;
        echo json_encode($error);
    }
}
