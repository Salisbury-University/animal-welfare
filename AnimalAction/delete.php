<?php
    include_once("../Includes/preventUnauthorizedUse.php");
    include_once("../Includes/databaseManipulation.php");
    
    $database = new databaseManipulation;
    
    $isAdmin = checkIsAdmin();
    if($isAdmin == false){
        header('Location: ../home.php');
    }
    
    $animal = $_POST['id'];
    echo "$animal";
    
    $sql="DELETE FROM animals WHERE animals.id = ?;";
    echo "$sql";
    $result = $database->runParameterizedQuery($sql, "s", array($animal));
    
    // Commenting this debug code out in case its needed in the future.
    // If the query fails, leave the user on the page.
    /*if($result == false){
        echo "MYSQL query failed <br>";
        exit;
    } else{
        echo "MSQL query successfull <br>";
    }*/
    
    // Redirect to home directory
    header("Location: ../search.php");
    
    ?>
