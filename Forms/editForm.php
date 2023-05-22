<?php
include "../Includes/preventUnauthorizedUse.php";
?>

<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Dashboard</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="../CSS/main.css" rel="stylesheet">
    <link href="../CSS/forms.css" rel="stylesheet">

    <!--Boostrap javascript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js"
        integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT"
        crossorigin="anonymous"></script>

</head>

<body>
    <!--Header-->
    <header></header>

    <!--Navigation Bar-->
    <hr>
    <nav class="navbar navbar-expand-md my-light">

        <!--Logo-->
        <div class="logo-overlay">
            <a href="../home.php">
                <img src=../Images/Header/logo.png alt="Logo">
            </a>
        </div>

        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav mr-auto">
                <!--Home-->
                <li class="nav-item">
                    <a class="nav-link my-text-info" href="../home.php">Home</a>
                </li>

                <!--Welfare Forms-->
                <li class="nav-item">
                    <a class="nav-link my-text-info" href="../welfare.php">Welfare Forms</a>
                </li>

                <!--Diet Tracker-->
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">Diet Tracker</a>
                </li>

                <!--Search Page-->
                <li class="nav-item">
                    <a class="nav-link my-text-info" href="../search.php">Search</a>
                </li>

                <!--Dropdown menu-->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle my-text-info" href="#" id="navbarDropdownMenuLink"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Admin
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="../admin.php">Manage admin</a>
                        <a class="dropdown-item" href="../admin_createUser.php">Create User</a>
                    </div>
                </li>
            </ul>
            <a class="btn btn-success my-2 my-sm-0 float-left" href="../logoutHandler.php" role="button">Logout</a>

        </div>
    </nav>
    <hr>
    <!--End Navigation Bar-->

    <main>
        <?php
        include '../Includes/DatabaseConnection.php';

        // GET: Gets the form id
        $FORM_ID = $_GET['id'];

        // QUERY: Gets the sections and the # of questions in each
        $GET_SECTIONS = "SELECT
                            sec.id, sec.title,
                            COUNT(*) AS 'num'
                            FROM (SELECT s.id, s.title
                                FROM sections s, hasSectionQuestions hsq 
                                WHERE hsq.form_id='$FORM_ID' AND  s.id=hsq.section_id) AS sec
                            GROUP BY sec.title
                            ORDER BY sec.id";
        $SECTIONS = mysqli_query($connection, $GET_SECTIONS);
        ?>

        <!-- DISPLAYS THE BACK BUTTON ON THE SCREEN -->
        <div class="back">
            <form method="POST" action="../welfare.php">
                <input type="submit" value="Back" />
            </form>
        </div>

        <div class="container">
            <table class="table table-bordered" id="myTable"
                <tbody>
                    <?php
                    while (($CUR_SECTION = mysqli_fetch_assoc($SECTIONS)))
                    { 
                        // NOTE: DELETE THIS LATER - FOR ORGANIZATIONAL PURPOSES
                        $SEC_ID=$CUR_SECTION['id'];
                        $SEC_TITLE=$CUR_SECTION['title'];
                        $SEC_QUESTION_COUNT=$CUR_SECTION['num'];
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
                    $GET_QUESTIONS = "SELECT q.question AS 'question', q.id AS 'id', hsq.id AS idt
                        FROM questions q
                        JOIN hasSectionQuestions hsq ON q.id = hsq.question_id
                        WHERE hsq.section_id='$SEC_ID' AND hsq.form_id = '$FORM_ID'
                        ORDER BY hsq.id";
                    $QUESTIONS = mysqli_query($connection, $GET_QUESTIONS);
                    for($QUESTION_NO = 1; $QUESTION_NO <= $SEC_QUESTION_COUNT; $QUESTION_NO++)
                    {
                        // NOTE: DELETE THIS LATER - FOR ORGANIZATIONAL PURPOSES
                        $CUR_QUESTION = mysqli_fetch_assoc($QUESTIONS);
                        $QUEST_ID = $CUR_QUESTION['id'];
                        $QUEST_TXT = $CUR_QUESTION['question'];
                        // END NOTE.
                    ?>
                    <!-- DISPLAYS THE QUESTIONS IN EACH SECTION AND BUTTONS-->
                        <tr>
                            <th>
                                <?= htmlspecialchars($QUESTION_NO, ENT_QUOTES, 'UTF-8') ?>
                            </th>
                            <td contenteditable="true" class='input'><?= htmlspecialchars($QUEST_TXT, ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <input data-fid='<?php echo $FORM_ID ?>' data-sid='<?php echo $SEC_ID ?>' data-qid='<?php echo $QUEST_ID?>' type='button' class="update" value="Update">
                                <input data-fid='<?php echo $FORM_ID ?>' data-sid='<?php echo $SEC_ID ?>' data-qid='<?php echo $QUEST_ID ?>' type='button' class="delete" value="Delete">
                            </td>
                        </tr>
                    <?php
                        }
                    ?>
                        <tr id=<?php echo "newrow_$SEC_ID"?> style="display:none">
                            <th>
                                <?= htmlspecialchars($QUESTION_NO, ENT_QUOTES, 'UTF-8') ?>
                            </th>
                            <td contenteditable="true" class='input'><?= htmlspecialchars("Enter a new question", ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <input data-fid='<?php echo $FORM_ID ?>' data-sid='<?php echo $SEC_ID ?>' type='button' class="submit" value="Submit">
                            </td>
                        </tr>
                    <!-- DISPLAYS THE ADD QUESTION BUTTON -->
                        <th class="text-center" colspan="3">
                            <input data-fid='<?php echo $FORM_ID ?>' data-sid='<?php echo $SEC_ID ?>' type='button' class="add" value="Add Question">
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
        $(document).ready(function(){
            $("#myTable").on('click', '.update', function() {
                // Gets the current row
                var currentRow = $(this).closest("tr");
                
                // Gets new text and database values
                var newText = currentRow.find("td:eq(0)").html();

                console.log("hello");
                var formID = $(this).data('fid');
                var sectionID = $(this).data('sid');
                var questionID = $(this).data('qid');

                $.ajax({
                    url: 'updateData.php',
                    type: 'post',
                    data: {'text' : newText, 'formid' : formID, 'secid' : sectionID, 'questid' : questionID},
                    success: function (response) {
                        console.log(response);
                        location.reload();
                    }
                });
            });
            $("#myTable").on('click', '.delete', function() {
                // Gets database values of row
                var formID = $(this).data('fid');
                var sectionID = $(this).data('sid');
                var questionID = $(this).data('qid');

                $.ajax({
                    url: 'deleteData.php',
                    type: 'post',
                    data: {'formid' : formID,'secid' : sectionID,'questid' : questionID},
                    success: function (response) {
                        console.log(response);
                        location.reload();
                    }
                });
            });

            // WARNING: THIS BUTTON NEEDS EXTENSIVE WORK
            $("#myTable").on('click', '.add', function() {
                var sectionID = $(this).data('sid');
                var rowID = "#newrow_"+sectionID;
        
                $(rowID).toggle();
                if($(this).val() == "Cancel"){
                    $(this).prop("value", "Add Question");
                } else {
                    $(this).prop("value", "Cancel");
                }
            });
            
            $("#myTable").on('click', '.submit', function() {
                // Gets the current row
                var currentRow = $(this).closest("tr");
                
                // Gets new text and database values
                var newText = currentRow.find("td:eq(0)").html();
                
                var formID = $(this).data('fid');
                var sectionID = $(this).data('sid');

                $.ajax({
                    url: 'addData.php',
                    type: 'post',
                    data: {'text' : newText, 'formid' : formID, 'secid' : sectionID},
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