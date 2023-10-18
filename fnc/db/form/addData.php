<?php
// WARNING: NOT WORKING
include '/home/joshb/website/final/slog/animal-welfare/fnc/db/auth/dbConnection.php';

// CHECKS THAT THE PROPER POST VALUES ARE SET
if(isset($_POST['text']) && isset($_POST['formid']) && isset($_POST['secid'])){
    // Sets the queries used in update
    $checkDup = $connection->prepare("SELECT id FROM questions WHERE question=?");
    $addQuest = $connection->prepare("INSERT INTO questions (question) VALUES (?)");
    $getLastID = $connection->prepare("SELECT LAST_INSERT_ID()");
    $updateQuest = $connection->prepare("INSERT INTO hasSectionQuestions (form_id, section_id, question_id) VALUES (?, ?, ?)");

    // Sets the values from post
    $NEW_TEXT =$_POST['text'];
    $FORM_ID= $_POST['formid'];
    $SEC_ID = $_POST['secid'];
    $QUEST_ID = $_POST['questid'];
    
    $checkDup->bind_param("s",strip_tags($NEW_TEXT, '<br>'));

    $checkDup->execute();
    $checkDup->bind_result($id);
    $checkDup->fetch();
    $checkDup->close();
    
    if(is_null($id)){ // This is a new question
        $addQuest->bind_param("s",strip_tags($NEW_TEXT));
        $addQuest->execute();
        $addQuest->close();

        $getLastID->execute();
        $getLastID->bind_result($NEW_ID);
        $getLastID->fetch();
        $getLastID->close();
    } else { // This is a different but not new question
        $NEW_ID = $id;
    }

    $updateQuest->bind_param("iiii", $FORM_ID, $SEC_ID, $NEW_ID);
    $updateQuest->execute();
    $updateQuest->close();

    $success = "Question successfully added!";
    echo json_encode($success);
}