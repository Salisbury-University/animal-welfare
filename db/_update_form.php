<?php
//WARNING: ALOT OF SPAGETTI HERE
// require_once "../vendor/autoload.php";
// use admin\SessionUser;

require_once "../admin/SessionUser.php";
SessionUser::sessionStatus();

$user = unserialize($_SESSION['user']);
$user->openDatabase();

// CHECKS THAT THE PROPER POST VALUES ARE SET
if (isset($_POST['text']) && isset($_POST['formid']) && isset($_POST['secid']) && isset($_POST['questid'])) {
    // Sets the queries used in update
    $checkDup = "SELECT id FROM questions WHERE question=?";
    $addQuest = "INSERT INTO questions (question) VALUES (?)";
    $getLastID = "SELECT LAST_INSERT_ID()";
    $updateQuest = "UPDATE hasSectionQuestions SET question_id=? WHERE form_id=? AND section_id=? AND question_id=?";

    // Sets the values from post
    $NEW_TEXT = $_POST['text'];
    $FORM_ID = $_POST['formid'];
    $SEC_ID = $_POST['secid'];
    $QUEST_ID = $_POST['questid'];

    $result = $user->getDatabase()->runParameterizedQuery($query, "s", array(strip_tags($formName, '<br>')));
    $id = $result->fetch_array(MYSQLI_ASSOC);

    if ($id['id'] == $QUEST_ID) { // This is the same question
        $success = "No data changed.";
        echo json_encode($success);
    } else if (is_null($id)) { // This is a new question
        $success = "";
        $result = $user->getDatabase()->runParameterizedQuery($addQuest, "s", array(strip_tags($formName, "<br>")));
        $result = $user->getDatabase()->runQuery_UNSAFE($getLastID);

        $lastID = $result->fetch_array(MYSQLI_ASSOC);
    } else { // This is a different but not new question
        $NEW_ID = $id['id'];
    }

    $result = $user->getDatabase()->runParameterizedQuery($updateQuest, "iiii", $NEW_ID, $FORM_ID, $SEC_ID, $QUEST_ID);

    $success = "Question successfully changed!";
    echo json_encode($success);
}

session_write_close();
exit();
?>