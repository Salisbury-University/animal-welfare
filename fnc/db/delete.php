<?php
    include_once("../auth/preventUnauthorizedUse.php");
    include_once("auth/databaseManager.php");
    
    $database = new DatabaseManager;
    
    $isAdmin = checkIsAdmin();
    if($isAdmin == false){
        header('Location: ../../ui/home.php');
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
    header("Location: ../../ui/search.php");
    
    ?>
