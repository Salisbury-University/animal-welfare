<?php
// require_once "../vendor/autoload.php";
include_once "header.php";
?>

<link href="../style/forms.css" rel="stylesheet">

<!--Only edit main-->
<main><!-- Main jumbotron for a primary marketing message or call to action -->
    <main>
        <?php
        // use admin\SessionUser;
        
        require_once "../admin/SessionUser.php";
        SessionUser::sessionStatus();

        $user = unserialize($_SESSION['user']);
        $user->openDatabase();

        //gets id
        $formID = $_GET['id'];

        //gets sections
        $query = "SELECT title FROM `sections`";
        $sections = $user->getDatabase()->runQuery_UNSAFE($query);
        ?>

        <!--Back button-->
        <div class="back">
            <form method="POST" action="home.php">
                <input type="submit" value="Back" />
            </form>

            <!--Display Active Form Name-->
            <?php
            $query = "SELECT * FROM `forms` WHERE id = ?";
            $titles = $user->getDatabase()->runParameterizedQuery($query, "i", array($formID));
            $title = $titles->fetch_array(MYSQLI_ASSOC);
            echo "<h2 class = 'text-center'> Editing: " . $title['title'] . "</h2>";
            ?>
            <!--End Display-->
        </div>
        <!--End Back Button Div-->


        <div class="container">
            <table class="table table-bordered" id="myTable">
                <tbody>
                    <?php
                    //display
                    $count = 1;
                    for ($secNum = 1; $secNum <= $sections->num_rows; $secNum++) {
                        $query = "SELECT q.question, q.id, hsq.id
                                FROM questions q
                                JOIN hasSectionQuestions hsq ON q.id = hsq.question_id
                                WHERE hsq.section_id = ? and hsq.form_id =?";
                        $questions = $user->getDatabase()->runParameterizedQuery($query, "ii", array($secNum, $formID));
                        while ($quest = $questions->fetch_array(MYSQLI_ASSOC)) {
                            if ($count == 1) {
                                $sec = $sections->fetch_array(MYSQLI_ASSOC); ?>

                                <tr>
                                    <th class="text-center" colspan="3">
                                        <?= htmlspecialchars($sec["title"], ENT_QUOTES, 'UTF-8') ?>
                                    </th>
                                </tr>

                                <?php
                            }
                            ?>
                            <tr>
                                <th>
                                    <?= htmlspecialchars($count, ENT_QUOTES, 'UTF-8') ?>
                                </th>
                                <td contenteditable="true" class='input'>
                                    <?= htmlspecialchars($quest["question"], ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td>
                                    <input data-id='<?php echo $quest[1] ?>' type='button' class="update" value="Update">
                                    <input data-id='<?php echo $quest[1] ?>' type='button' class="delete" value="Delete">
                                </td>
                            </tr>

                            <?php
                            $count++;
                        }
                        $count = 1;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#myTable").on('click', '.update', function () {
                // get the current row
                var currentRow = $(this).closest("tr");

                var col1 = currentRow.find("td:eq(0)").html(); //Test Data: Example Question
                var colid = $(this).data('id'); //Test Data: 66

                $.ajax({
                    url: '../db/updateForms.php',
                    type: 'post',
                    data: { 'text': col1, 'id': colid },
                    success: function (response) {
                        console.log(response);
                    }
                });
            });
        });
    </script>

    <?php
    include_once "footer.php";
    ?>