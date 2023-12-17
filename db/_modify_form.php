<?php
require_once "../admin/SessionUser.php";
require_once "../auth/DatabaseManager.php";
SessionUser::sessionStatus();

$user = unserialize($_SESSION['user']);
$user->openDatabase();
$out = "hello";

// Log errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['form']) && isset($_POST['form_id']) && isset($_POST['form_name'])) {
    // Decode the JSON array
    $formData = json_decode($_POST['form'], true);
    $formId = $_POST['form_id'];
    $formName = $_POST['form_name'];

    $newFormId = $formId;
    $usedFormQuery = "SELECT COUNT(*) as count FROM welfaresubmission WHERE fid = ?";
    $result = $user->getDatabase()->runParameterizedQuery($usedFormQuery, "i", array($formId));
    $count = $result->fetch_array(MYSQLI_ASSOC)['count'];

    if ($count > 0) {
        // Archive the old form
        $archiveOldFormQuery = "UPDATE forms SET archived = true WHERE id = ?";
        $user->getDatabase()->runParameterizedQuery($archiveOldFormQuery, "i", array($formId));

        // Create a new form
        $addFormQuery = "INSERT INTO forms (title, archived) VALUES (?, false)";
        $user->getDatabase()->runParameterizedQuery($addFormQuery, "s", array("New Form"));

        // Get the last insert ID (new form ID)
        $newFormId = $user->getDatabase()->getLastInsertId();

        // Get the species using the old form
        $getSpeciesQuery = "SELECT id FROM species WHERE form_id = ?";
        $speciesResults = $user->getDatabase()->runParameterizedQuery($getSpeciesQuery, "i", array($formId));
        $updateSpeciesQuery = "UPDATE species SET form_id = ? WHERE id = ?";
        while ($species = $speciesResults->fetch_array(MYSQLI_ASSOC)) {
            if (is_null($species['id'])) {
                error_log("Error species roles");
            }
            $user->getDatabase()->runParameterizedQuery($updateSpeciesQuery, "ii", array($newFormId, $species['id']));
        }
    } else { //form data is to be deleted
        $updateFormQuery = "UPDATE forms SET title = ? WHERE id = ?";
        $user->getDatabase()->runParameterizedQuery($updateFormQuery, "si", array($formName, $formId));
        $deleteQuestionQuery = "DELETE FROM hasSectionQuestions WHERE form_id = ?";
        $user->getDatabase()->runParameterizedQuery($deleteQuestionQuery, "i", array($formId));
    }

    //Update species with the new form ID
    // Iterate through the form data to add sections and questions
    foreach ($formData as $sectionIndex => $sectionData) {
        if (isset($sectionData['this'])) {
            var_dump($formData);
            // Check if the section exists, if not, add it
            $checkSectionQuery = "SELECT id FROM sections WHERE title = ?";
            $sectionResult = $user->getDatabase()->runParameterizedQuery($checkSectionQuery, "s", array($sectionData['this']['text']));
            $section = $sectionResult->fetch_array(MYSQLI_ASSOC);

            $newSectionId = 0;

            if (is_null($section)) {
                $addSectionQuery = "INSERT INTO sections (title) VALUES (?)";
                $user->getDatabase()->runParameterizedQuery($addSectionQuery, "s", array($sectionData['this']['text']));

                // Get the last insert ID (new section ID)
                $newSectionId = $user->getDatabase()->getLastInsertId();
                $user->getDatabase()->getLastInsertId();
            } else {
                $newSectionId = $section['id'];
            }

            // Iterate through questions in the section
            foreach ($sectionData as $questionIndex => $questionData) {
                if ($questionIndex !== 'this') {
                    // Check if the question data is set and has the required keys
                    if (isset($questionData['text'])) {
                        // Check if the question exists, if not, add it
                        $checkQuestionQuery = "SELECT id FROM questions WHERE question = ?";
                        $questionResult = $user->getDatabase()->runParameterizedQuery($checkQuestionQuery, "s", array($questionData['text']));
                        $question = $questionResult->fetch_array(MYSQLI_ASSOC);
                        echo $questionData['text'] . "\n";
                        $newQuestionId = 0;

                        if (is_null($question)) {
                            // Add the new question
                            echo "in herre" . $questionData['text'] . "\n";
                            $addQuestionQuery = "INSERT INTO questions (question) VALUES (?)";
                            $user->getDatabase()->runParameterizedQuery($addQuestionQuery, "s", array($questionData['text']));

                            // Get the last insert ID (new question ID)
                            $newQuestionId = $user->getDatabase()->getLastInsertId();
                        } else {
                            $newQuestionId = $question['id'];
                        }
                        echo "keys:" . $newSectionId;
                        var_dump(array($newFormId, $newSectionId, $newQuestionId));
                        echo "\n";
                        // Add the question to the section in hasSectionQuestions
                        $addQuestionToSectionQuery = "INSERT INTO hasSectionQuestions (form_id, section_id, question_id) VALUES (?, ?, ?)";
                        $user->getDatabase()->runParameterizedQuery($addQuestionToSectionQuery, "iii", array($newFormId, $newSectionId, $newQuestionId));
                    } else {
                        // Handle the case where question data is not set or does not have the required keys
                        error_log("Error: Question data is not set or does not have the required keys.");
                    }
                }
            }


        }
    }

    $success = "Changes submitted successfully.";
    echo json_encode($success);
}

if (isset($_POST['delete']) && isset($_POST['form_id'])) {
    $formId = $_POST['form_id'];

    $usedFormQuery = "SELECT COUNT(*) as count FROM welfaresubmission WHERE fid = ?";
    $result = $user->getDatabase()->runParameterizedQuery($usedFormQuery, "i", array($formId));
    $subCount = $result->fetch_array(MYSQLI_ASSOC)['count'];
    
    $getSpeciesQuery = "SELECT COUNT(*) as count FROM species WHERE form_id = ?";
    $speciesResults = $user->getDatabase()->runParameterizedQuery($getSpeciesQuery, "i", array($formId));
    $specCount = $speciesResults->fetch_array(MYSQLI_ASSOC)['count'];

    if ($subCount > 0 || $specCount > 0) {
         // Archive the old form
         $archiveOldFormQuery = "UPDATE forms SET archived = true WHERE id = ?";
         $user->getDatabase()->runParameterizedQuery($archiveOldFormQuery, "i", array($formId));
         echo "archived";
    } else {
        $deleteQuestionQuery = "DELETE FROM hasSectionQuestions WHERE form_id = ?";
        $user->getDatabase()->runParameterizedQuery($deleteQuestionQuery, "i", array($formId));
        $deleteFormQuery = "DELETE FROM forms WHERE id = ?";
        $user->getDatabase()->runParameterizedQuery($deleteQuestionQuery, "i", array($formId));
        echo "deleted";
    }
}

session_write_close();
exit();
?>