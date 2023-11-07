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

<main>
    <?php
    // GET: Gets the form id
    $formID = $_GET['id'];

    // QUERY: Gets the sections and the # of questions in each
    $query = "SELECT sec.id, sec.title, COUNT(*) AS 'num'
              FROM (SELECT s.id, s.title
                    FROM sections s, hasSectionQuestions hsq 
                    WHERE hsq.form_id=? AND  s.id=hsq.section_id) AS sec
              GROUP BY sec.title
              ORDER BY sec.id";
    $sections = $user->getDatabase()->runParameterizedQuery($query, "i", array($formID));

    ?>

    <!-- DISPLAYS THE BACK BUTTON ON THE SCREEN -->
    <div class="back">
        <form method="POST" action="welfare.php">
            <input type="submit" value="Back" />
        </form>
    </div>

    <div class="container">
        <table class="table table-bordered" id="myTable" <tbody>
            <?php
            while ($section = $sections->fetch_array(MYSQLI_ASSOC)) {
                // NOTE: DELETE THIS LATER - FOR ORGANIZATIONAL PURPOSES
                $sectionID = $section['id'];
                $sectionTitle = $section['title'];
                $sectionQuestionCount = $sections['num'];
                // END NOTE.
                ?>
                <!-- DISPLAYS THE SECTIONS IN THE FORM -->
                <tr>
                    <th class="text-center" colspan="3">
                        <?= htmlspecialchars($SEC_TITLE, ENT_QUOTES, 'UTF-8') ?>
                    </th>
                </tr>
                <?php
                // QUERY: Gets the questions in the this section of the form
                $query = "SELECT q.question AS 'question', q.id AS 'id', hsq.id AS idt
                        FROM questions q
                        JOIN hasSectionQuestions hsq ON q.id = hsq.question_id
                        WHERE hsq.section_id=? AND hsq.form_id=?
                        ORDER BY hsq.id";
                $questions = $user->getDatabase()->runParameterizedQuery($query, "ii", array($sectionID, $formID));
                for ($questionNum = 1; $questionNum <= $sectionQuestionCount; $questionNum++) {
                    // NOTE: DELETE THIS LATER - FOR ORGANIZATIONAL PURPOSES
                    $question = $questions->fetch_array(MYSQLI_ASSOC);
                    $questionID = $question['id'];
                    $questionText = $question['question'];
                    // END NOTE.
                    ?>
                    <!-- DISPLAYS THE QUESTIONS IN EACH SECTION AND BUTTONS-->
                    <tr>
                        <th>
                            <?= htmlspecialchars($questionNum, ENT_QUOTES, 'UTF-8') ?>
                        </th>
                        <td contenteditable="true" class='input'>
                            <?= htmlspecialchars($questionText, ENT_QUOTES, 'UTF-8') ?>
                        </td>
                        <td>
                            <input data-fid='<?php echo $formID ?>' data-sid='<?php echo $sectionID ?>'
                                data-qid='<?php echo $questionID ?>' type='button' class="update" value="Update">
                            <input data-fid='<?php echo $formID ?>' data-sid='<?php echo $sectionID ?>'
                                data-qid='<?php echo $questionID ?>' type='button' class="delete" value="Delete">
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <tr id=<?php echo "newrow_$sectionID" ?> style="display:none">
                    <th>
                        <?= htmlspecialchars($questionNum, ENT_QUOTES, 'UTF-8') ?>
                    </th>
                    <td contenteditable="true" class='input'>
                        <?= htmlspecialchars("Enter a new question", ENT_QUOTES, 'UTF-8') ?>
                    </td>
                    <td>
                        <input data-fid='<?php echo $formID ?>' data-sid='<?php echo $sectionID ?>' type='button'
                            class="submit" value="Submit">
                    </td>
                </tr>
                <!-- DISPLAYS THE ADD QUESTION BUTTON -->
                <th class="text-center" colspan="3">
                    <input data-fid='<?php echo $formID ?>' data-sid='<?php echo $sectionID ?>' type='button' class="add"
                        value="Add Question">
                </th>

                <?php
            }
            ?>
            </tbody>
        </table>
    </div>

    <!-- SUBMIT BUTTON - NOTE: Changes are already made when user presses update or delete -->
    <button type="submit" class="btn1 btn-success">Submit</button>

    <!-- EXPORT BUTTON - NOTE: For forms, the database is having trouble recognizing the ' character-->
    <!--    NEEDS IMPLEMENTATION -->
</main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $("#myTable").on('click', '.update', function () {
            // Gets the current row
            var currentRow = $(this).closest("tr");

            // Gets new text and database values
            var newText = currentRow.find("td:eq(0)").html();

            var formID = $(this).data('fid');
            var sectionID = $(this).data('sid');
            var questionID = $(this).data('qid');

            $.ajax({
                url: '../db/update_data.php',
                type: 'post',
                data: { 'text': newText, 'formid': formID, 'secid': sectionID, 'questid': questionID },
                success: function (response) {
                    console.log(response);
                    location.reload();
                }
            });
        });
        $("#myTable").on('click', '.delete', function () {
            // Gets database values of row
            var formID = $(this).data('fid');
            var sectionID = $(this).data('sid');
            var questionID = $(this).data('qid');

            $.ajax({
                url: '../db/_delete_data.php',
                type: 'post',
                data: { 'formid': formID, 'secid': sectionID, 'questid': questionID },
                success: function (response) {
                    console.log(response);
                    location.reload();
                }
            });
        });

        // WARNING: THIS BUTTON NEEDS EXTENSIVE WORK
        $("#myTable").on('click', '.add', function () {
            var sectionID = $(this).data('sid');
            var rowID = "#newrow_" + sectionID;

            $(rowID).toggle();
            if ($(this).val() == "Cancel") {
                $(this).prop("value", "Add Question");
            } else {
                $(this).prop("value", "Cancel");
            }
        });

        $("#myTable").on('click', '.submit', function () {
            // Gets the current row
            var currentRow = $(this).closest("tr");

            // Gets new text and database values
            var newText = currentRow.find("td:eq(0)").html();

            var formID = $(this).data('fid');
            var sectionID = $(this).data('sid');

            $.ajax({
                url: '../db/_add_form.php',
                type: 'post',
                data: { 'text': newText, 'formid': formID, 'secid': sectionID },
                success: function (response) {
                    console.log(response);
                    location.reload();
                }
            });
        });
    });
</script>

<hr>
<footer class="container-fluid">
    <div class="f-top">
        <div class="row">
            <div class="col">
                <h4>welfare</h4>
                <ul>
                    <li><a href="#">animals</a></li>
                    <li><a href="#">species</a></li>
                    <li><a href="#">sections</a></li>
                    <li><a href="#">checkups</a></li>
                    <li><a href="#">forms</a></li>
                </ul>
            </div>
            <div class="col">
                <h4>diets</h4>
                <ul>
                    <li><a href="#">coming soon</a></li>
                    <!-- <li><a href=''></li> -->
                </ul>
            </div>
            <div class="col">
                <h4>data</h4>
                <ul>
                    <li><a href="#">compare animals</a></li>
                    <li><a href="#">view all</a></li>
                    <li><a href="#">export data</a></li>
                    <!-- <li><a href="#">interactive map</a></li> -->
                    <!-- <li><a href=''></li> -->
                </ul>
            </div>
            <div class="col">
                <h4>help</h4>
                <ul>
                    <li><a href="#">coming soon</a></li>
                    <!-- <li><a href=''></li> -->
                </ul>
            </div>
        </div>
    </div>
</footer>
</body>

<?php
include_once "footer.php";
?>