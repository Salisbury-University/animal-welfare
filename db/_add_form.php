<?php
// WARNING: NOT WORKING
// require_once "../vendor/autoload.php";
// use admin\SessionUser;

require_once "../admin/SessionUser.php";
SessionUser::sessionStatus();

$user = unserialize($_SESSION['user']);
$user->openDatabase();

// CHECKS THAT THE PROPER POST VALUES ARE SET
if (isset($_POST['text']) && isset($_POST['formid']) && isset($_POST['secid'])) {
    // Sets the queries used in update

    $checkDup = "SELECT id FROM questions WHERE question=?";
    $addQuest = "INSERT INTO questions (question) VALUES (?)";
    $getLastID = "SELECT LAST_INSERT_ID()";
    $updateQuest = "INSERT INTO hasSectionQuestions (form_id, section_id, question_id) VALUES (?, ?, ?)";

    // Sets the values from post
    $NEW_TEXT = $_POST['text'];
    $FORM_ID = $_POST['formid'];
    $SEC_ID = $_POST['secid'];
    $QUEST_ID = $_POST['questid'];

    $result = $user->getDatabase()->runParameterizedQuery($checkDup, "s", array(strip_tags($NEW_TEXT, '<br>')));
    $id = $result->fetch_array(MYSQLI_ASSOC);

    if (is_null($id['id'])) { // This is a new question
        $result = $user->getDatabase()->runParameterizedQuery($addQuest, "s", array(strip_tags($NEW_TEXT, '<br>')));
        $result = $user->getDatabase()->runQuery_UNSAFE($getLastID);
        $NEW_ID = $result->fetch_array(MYSQLI_ASSOC);

    } else { // This is a different but not new question
        $NEW_ID = $id;
    }

    $result = $user->getDatabase()->runParameterizedQuery($updateQuest, "iii", array($FORM_ID, $SEC_ID, $NEW_ID));

    $success = "Question successfully added!";
    echo json_encode($success);
}

session_write_close();
exit();
?>