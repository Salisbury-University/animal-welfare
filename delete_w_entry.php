<?php
include "Includes/preventUnauthorizedUse.php";

    function debug_to_console($data){
        $output = $data;
        if(is_array($output))
            $output = implode(",", $output);

        echo "<script>console.log('Debug Objects: " . $output ."');</script>";
    }

    $wid = $_POST['wid'];
    $zims = $_POST['zims'];
    debug_to_console($wid);
    debug_to_console($zims);

    $sql = "DELETE FROM welfaresubmission WHERE wid = ? AND zim = ?";

    $stmt = $connection->prepare($sql);
    
    if($stmt){
        $stmt->bind_param("ii", $wid, $zims);
        
        if($stmt->execute()){
            echo "<br> Records deleted";
        }
        else{
            echo "<br> Error deleting records. Error: ". $stmt->error;
        }

        $stmt->close();
    }else{
        echo "<br> Error preparing statement: " . $connection->error; 
    }




  
    
?>