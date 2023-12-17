<?php
// require_once "../vendor/autoload.php";
// <NOTE> Heavy rework needed (deprec);
include_once "header.php";

// use admin\SessionUser;

require_once "../admin/SessionUser.php";

SessionUser::sessionStatus();

$user = unserialize($_SESSION['user']);
$user->openDatabase();
?>

<!-- Step 1: Get the base form data loaded into the page -->
<?php
$formID = $_GET['id'];
$query = "SELECT title FROM forms WHERE id = ?";
$result = $user->getDatabase()->runParameterizedQuery($query, "i", array($formID));
$formTitle = $result->fetch_array(MYSQLI_ASSOC)['title'];

// QUERY: Gets the sections and the # of questions in each
$query = "SELECT sec.id as 'id', sec.title as 'title', COUNT(*) AS 'count' 
                FROM (SELECT s.id, s.title
                    FROM sections s, hasSectionQuestions hsq 
                    WHERE hsq.form_id=? AND  s.id=hsq.section_id) AS sec
                GROUP BY sec.title
                ORDER BY sec.id";
$sections = $user->getDatabase()->runParameterizedQuery($query, "i", array($formID));
?>
<!-- Step 2: Display the form data as sortable objects with add question button to the end of a section and add section button to the end of the form-->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard</title>
    <!-- Bootstrap core CSS -->

    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script> -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script> -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <link href="../style/forms.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(function () {
            //-------------------------Building sortables START----------------------
            // Creates the sections sortable
            function refreshSortableSections() {
                $(".form-content").sortable({
                    items: ".section",
                    placeholder: "ui-state-highlight",
                    animation: 150,
                });
                $(".form-content").disableSelection();
            }

            // Creates the questions sortable
            function refreshSortableQuestions() {
                $(".section-content").sortable({
                    items: ".question",
                    placeholder: "ui-state-highlight",
                    connectWith: ".section-content", // Allow sorting within the same section
                    animation: 150,
                });
                $(".section-content").disableSelection();
            }

            refreshSortableSections();
            refreshSortableQuestions();
            //-------------------------Building sortables END-----------------------

            //----------------------------------------------------------------------
            //----------------------------------------------------------------------

            //-------------------------Build Form Array START-----------------------
            function constructForm() {
                var content = {};

                $(".section").each(function (sectionIndex) {
                    var text = $(this).find(".section-header .editable").text().trim();
                    var sectionId = $(this).attr('id');
                    var position = sectionIndex + 1; // Position for sections

                    content[sectionId] = {
                        "this": {
                            "id": sectionId,
                            "text": text,
                            "position": position
                        }
                    };

                    $(".question", this).each(function (questionIndex) {
                        var text = $(this).find(".editable").text().trim();
                        var questionId = $(this).attr('id');
                        var positionq = questionIndex + 1; // Position for questions

                        content[sectionId][questionId] = {
                            "id": questionId,
                            "text": text,
                            "position": positionq
                        };
                    });
                });

                return content;
            }

            var form = {};
            var oldForm = {};
            var first = 1;

            if (first == 1) {
                form = constructForm();
                oldForm = constructForm();
                first = 0;
            }

            console.log(form);
            //-------------------------Build Form Array END--------------------------

            //-----------------------------------------------------------------------
            //-----------------------------------------------------------------------

            //-------------------------Text Changes START----------------------------
            var editingElement = null;
            var formContent = $(".form-content");
            // Double-click on section text puts into editing mode
            formContent.on("dblclick", ".section .editable", function (e) {
                e.stopPropagation(); // Prevent sorting when double-clicking

                // Disable sorting for the entire form content
                formContent.sortable("option", "disabled", true);

                // Make the text editable and focus on it
                $(this).prop('contenteditable', true).focus();
            });

            // Click outside to make the section or question non-editable and sortable
            $(document).on("click", function (e) {
                var editableElements = $('.section .editable, .question-title.editable');

                if (!editableElements.is(e.target) && editableElements.has(e.target).length === 0) {
                    editableElements.prop('contenteditable', false);
                    editableElements.removeClass('focused');
                    formContent.sortable("option", "disabled", false);
                    curSec = null;
                    curQuest = null;
                }
            });
            //-------------------------Text Changes END ----------------------------

            //-----------------------------------------------------------------------
            //-----------------------------------------------------------------------

            //-------------------------Add Changes START ----------------------------
            function addQuestion(caller) {
                // current section
                var currentSection = $(caller).closest('.section');

                // current sectionId
                var curSecId = $(currentSection).attr('id');

                // question keys and this key
                var secKeys = form[curSecId];

                // Find the number of questions in the current section
                var numberOfQuestions = Object.keys(secKeys).length;

                var newDelButton = $("<div class='delSectionButton' style='width:5%;margin-left:auto;margin-right: 1.5%;text-align: right;'></div>")
                    .on("click", function () {
                        // Handle the add question logic
                        deleteQuestion(this);
                    });
                $(newDelButton).append("<i class='fas fa-trash'></i>");

                // Create the new question element
                var newQID = "nq" + numberOfQuestions + curSecId;
                var newQuestion = $("<div class='question' style='display: flex;' id='" + newQID + "'>")
                    .append("<div class='qnumber'style='width:5%;margin-right:1%;text-align: center;'>" + numberOfQuestions + "</div>")
                    .append("<div class='question-title editable' style='max-width: 88%;'>New Question</div>")
                    .append(newDelButton);

                // Append the new question to the current section
                $(currentSection).find('.section-content').append(newQuestion);
                form[curSecId][newQID] = { // Update the key to use newQID
                    "id": newQID,
                    "text": "New Question",
                    "position": numberOfQuestions
                };

                refreshSortableQuestions();

                // Move the "Add Question" button below the new Question
                $(currentSection).find(".addQuestionContainer").appendTo($(currentSection));
            }


            function addSection(caller) {
                // Find the number of sections in the form
                var numberOfSections = Object.keys(form).length + 1;

                // Create the new section element
                var newSectionId = "s" + numberOfSections; // New section id
                var newSection = $("<div class='section' style='display: grid;' id='" + newSectionId + "'>")
                    .append("<div class='section-header' style='display: flex;'><div class='editable'>New Section</div><div class='delSectionButton' style='width:5%;margin-left:auto;margin-right: 1.5%;text-align: right;'><i class='fas fa-trash'></i></div></div>")
                    .append("<div class='section-content'></div>");

                // Append the new section to the form content
                $(formContent).append(newSection);

                // Create the "Add Question" button
                var newQuestionButton = $("<button class='addQuestionButton'>Add Question</button>")
                    .on("click", function () {
                        // Handle the add question logic
                        addQuestion(this);
                    });

                // Create a container for the "Add Question" button
                var addQuestionContainer = $("<div class='addQuestionContainer'></div>")
                    .append(newQuestionButton);

                // Append the "Add Question" container within the new section
                $(newSection).find('.section-content').append(addQuestionContainer);

                // Align the container to the right
                addQuestionContainer.css('text-align', 'right');

                refreshSortableSections();

                // Update the form object with the new section
                form[newSectionId] = {
                    "this": {
                        "id": newSectionId,
                        "text": "New Section",
                        "position": numberOfSections
                    }
                }

                // Move the "Add Section" button below the new section
                $(formContent).find("#addSectionContainer").appendTo($(formContent));

                // Add click event for the delete section button
                $(newSection).find(".delSectionButton").on("click", function () {
                    // Call your deleteSection function or logic here
                    deleteSection(this);
                });
            }



            // Add Section button click event
            $("#addSectionButton").on("click", function () {
                // Call your addSection function or logic here
                addSection(this);
            });


            // Add Question button click event
            $(".addQuestionButton").on("click", function () {
                addQuestion(this);
            });

            //-------------------------Add Changes END ----------------------------

            //-----------------------------------------------------------------------
            //-----------------------------------------------------------------------

            //-------------------------Delete Changes START ----------------------------
            function deleteSection(caller) {
                // Get the section ID
                var sectionId = $(caller).closest('.section').attr('id');

                // Remove the section from the screen
                $(caller).closest('.section').remove();

                // Remove the section from the form array
                if (form[sectionId]) {
                    delete form[sectionId];
                }

                console.log("Delete Section: " + sectionId);
                // Add your additional delete section logic if needed
            }


            function deleteQuestion(caller) {
                // Get the section and question IDs
                var sectionId = $(caller).closest('.section').attr('id');
                var questionId = $(caller).closest('.question').attr('id');

                // Remove the question from the screen
                $(caller).closest('.question').remove();

                // Remove the question from the form array
                if (form[sectionId] && form[sectionId][questionId]) {
                    delete form[sectionId][questionId];
                }

                console.log("Delete Question: " + questionId);
                // Add your additional delete question logic if needed
            }


            // Add Section button click event
            $(".delSectionButton").on("click", function () {
                // Call your addSection function or logic here
                deleteSection(this);
            });


            // Add Question button click event
            $(".delQuestionButton").on("click", function () {
                deleteQuestion(this);
                updateFormArray();
            });
            //-------------------------Delete Changes END ----------------------------

            //-----------------------------------------------------------------------
            //-----------------------------------------------------------------------

            //-------------------------Move Changes START ----------------------------
            function updateFormArray() {
                $(".section").each(function (sectionIndex) {
                    var sectionId = $(this).attr('id');
                    form[sectionId].this.position = sectionIndex + 1;

                    $(".question", this).each(function (questionIndex) {
                        var questionId = $(this).attr('id');
                        form[sectionId][questionId].position = questionIndex + 1;
                        $(this).find('.qnumber').text(questionIndex + 1);
                    });
                });
            }

            // Use event delegation for sortstop on .form-content
            $(".form-content").on("sortstop", ".section", function (event, ui) {
                updateFormArray();
            });

            // Use event delegation for sortreceive on .section-content
            $(".form-content").on("sortreceive", ".section .section-content", function (event, ui) {
                var oldSection = ui.sender.closest('.section');
                var newSection = $(this).closest('.section');
                var draggedQuestion = ui.item;

                // Update the qnumber in the old section
                $(".question", oldSection).each(function (questionIndex) {
                    $(this).find('.qnumber').text(questionIndex + 1);
                });

                // Update the qnumber in the new section
                $(".question", newSection).each(function (questionIndex) {
                    $(this).find('.qnumber').text(questionIndex + 1);
                });

                // Add the question to the new section in the form array
                var oldSectionId = oldSection.attr('id');
                var newSectionId = newSection.attr('id');
                var questionId = draggedQuestion.attr('id');

                form[newSectionId][questionId] = form[oldSectionId][questionId];
                delete form[oldSectionId][questionId];

                // Optional: You may want to update the position in the form array
                updateFormArray();
            });

            //-------------------------Move Changes END ----------------------------

            //-----------------------------------------------------------------------
            //-----------------------------------------------------------------------

            //-------------------------Submit Changes START --------------------------
            $("#submitChangesButton").on("click", function () {
                // Check if there are changes between oldForm and newForm
                var newForm = constructForm();

                if (!areFormsEqual(oldForm, newForm)) {
                    // Run AJAX to submit the changes
                    $.ajax({
                        type: "POST",
                        url: "../db/_modify_form.php",
                        data: {
                            form: JSON.stringify(newForm),
                            form_id: <?php echo $formID ?>,
                            form_name: $("#formTitle").text()
                            // You can include additional data if needed
                        },
                        success: function (response) {
                            console.log(response);
                            console.log("Changes submitted successfully");
                            // You can handle the response from modifyForm.php here
                        },
                        error: function (error) {
                            console.error("Error submitting changes:", error);
                            // Handle the error
                        }
                    });
                } else {
                    console.log("No changes to submit");
                }
            });

            // Function to check if two form objects are equal
            function areFormsEqual(form1, form2) {
                return JSON.stringify(form1) === JSON.stringify(form2);
            }
            //-------------------------Submit Changes END ----------------------------
        });

        // Event delegation for the #editFormButton
        $(document).on("click", "#editFormButton", function () {
            // Find the #formTitle element
            console.log("formTitleElement");
            var formTitleElement = $("#formTitle");

            // Make the text editable and focus on it
            formTitleElement.prop('contenteditable', true).focus();

            // Disable sorting for the entire form content
            $(".form-content").sortable("option", "disabled", true);
        });
        // Event delegation for the #editFormButton
        $(document).on("click", "#deleteFormButton", function () {
            // Use the confirm function to show a confirmation popup
            var confirmation = confirm("Are you sure you want to delete this form?");

            // If the user clicks OK in the confirmation popup
            if (confirmation) {
                $.ajax({
                    type: "POST",
                    url: "../db/_modify_form.php",
                    data: {
                        delete: "true",
                        form_id: <?php echo $formID ?>
                        
                        // You can include additional data if needed
                    },
                    success: function (response) {
                        alert("Form "+response+"!"); // Replace this with your actual deletion logic
                        // You can handle the response from modifyForm.php here
                    },
                    error: function (error) {
                        console.error("Error submitting changes:", error);
                        // Handle the error
                    }
                });
                
            } else {
                // If the user clicks Cancel, do nothing or provide feedback
                alert("Deletion canceled"); // Replace this with your desired feedback
            }
        });
    </script>
</head>

<body>
    <!-- Column to hold the form display grid-->
    <div class="card" , style="width: 50%;margin: 0 auto;">
        <!-- Row to hold back button and form title -->
        <div style="display: flex; text-align: center;">
            <div style="display: flex; align-items: center;">
                <button style="height: fit-content;" onclick="history.go(-1);">Back</button>
            </div>    
            <div id="formTitle">
                <?php echo $formTitle ?>
            </div>
            <div style="display: flex; align-items: center;">
                <!-- Pencil (Edit) Button -->
                <div id="editFormButton" style="margin-right: 10px;">
                    <i class="fas fa-pencil-alt"></i>
                </div>
                <!-- Trash Can (Delete) Button -->
                <div id="deleteFormButton">
                    <i class="fas fa-trash"></i>
                </div>
            </div>
        </div>

        <div class="form-content" , style="display: grid">
            <?php
            $i = 1;
            while ($section = $sections->fetch_array(MYSQLI_ASSOC)) {
                ?>
                <div class="section" , id="<?php echo "s" . $section['id']; ?>" , style="display: grid">
                    <div class="section-header" style="display: flex;">
                        <div class="editable" style="max-width: 94%;">
                            <?php echo $section['title']; ?>
                        </div>
                        <div class="delSectionButton"
                            style="width:5%;margin-left:auto;margin-right: 1.5%;text-align: right;">
                            <i class="fas fa-trash"></i>
                        </div>
                    </div>
                    <div class="section-content">
                        <?php
                        $query = "SELECT q.question AS 'text', q.id AS 'id', hsq.id AS idt
                            FROM questions q
                            JOIN hasSectionQuestions hsq ON q.id = hsq.question_id
                            WHERE hsq.section_id=? AND hsq.form_id=?
                            ORDER BY hsq.id";
                        $questions = $user->getDatabase()->runParameterizedQuery($query, "ii", array($section['id'], $formID));
                        $j = 1;
                        while ($question = $questions->fetch_array(MYSQLI_ASSOC)) {
                            ?>
                            <div class="question" , style="display: flex;" , id=<?php echo "q" . $question['id'] . "i" . $question['idt'] . "s" . $section['id']; ?>>
                                <div class="qnumber" style="width:5%;margin-right:1%;text-align: center;">
                                    <?php echo $j; ?>
                                </div>
                                <div class="question-title editable" , style="max-width: 88%;">
                                    <?php echo $question["text"]; ?>
                                </div>
                                <div class="delQuestionButton"
                                    style="width:5%;margin-left:auto;margin-right: 1.5%;text-align: right;">
                                    <i class="fas fa-trash"></i>
                                </div>
                            </div>
                            <?php
                            $j++;
                        }
                        ?>
                        <div class="addQuestionContainer" style="text-align: right;">
                            <button class="addQuestionButton">+</button>
                        </div>
                    </div>
                </div>
                <?php
                $i++;
            }
            ?>
            <!-- Add Section Button -->
            <div id="addSectionContainer" style="text-align: center;">
                <button id="addSectionButton">Add Section</button>
            </div>
        </div>
        <div style='display: flex;justify-content: flex-end;'>
            <button id="submitChangesButton">Submit Changes</button>
        </div>
    </div>
</body>
</html>
<?php
include_once "footer.php";