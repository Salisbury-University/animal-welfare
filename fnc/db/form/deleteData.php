<?php
include '../auth/dbConnection.php';

if(isset($_POST['formid']) && isset($_POST['secid']) && isset($_POST['questid'])){
    $FORM_ID = $_POST['formid'];
    $SEC_ID = $_POST['secid'];
    $QUEST_ID = $_POST['questid'];
    
    $delQuest = $connection->prepare("DELETE FROM hasSectionQuestions WHERE form_id=? AND section_id=? AND question_id=?");
    $delQuest->bind_param("iii", $FORM_ID, $SEC_ID, $QUEST_ID);
    $delQuest->execute();
    $delQuest->close();

    $success = "Deleted question successfully";
    echo json_encode($success);
}