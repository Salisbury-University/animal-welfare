<?php
// require_once "../vendor/autoload.php";
include_once "header.php";
// use admin\SessionUser;

require_once "../admin/SessionUser.php"; 
SessionUser::sessionStatus();

$user = unserialize($_SESSION['user']);
$user->openDatabase();

$formID = $_GET['formID'];
$wid = $_GET['wid'];
$zims = $_GET['zim'];
$i = 0;

// Get sections
$query = "SELECT title FROM `sections`";
$sections = $user->getDatabase()->runQuery_UNSAFE($query);
?>

<?php

// QUERY: Gets the sections and the # of questions in each
$query = "SELECT sec.id, COUNT(*) AS 'num' 
          FROM (SELECT s.id, s.title 
                FROM sections s, hasSectionQuestions hsq 
                WHERE hsq.form_id = ? AND s.id=hsq.section_id) AS sec
          GROUP BY sec.title
          ORDER BY sec.id";
$questionCount = $user->getDatabase()->runParameterizedQuery($query, "s", array($formID));

$qArr = [];
$index = 0;

if ($result) {

    while ($row = $questionCount->fetch_array(MYSQLI_ASSOC)) {
        // Access the 'num' column which is number of questions per section
        $qArr[$index] = $row['num'];
        $index++;
    }
}

$numofsec = $sections->num_rows;
?>


<!doctype html>

<html lang="en">

<head>
    <title>Display Entry</title>
    <link href="CSS/forms.css" rel="stylesheet">

    <style>
        /* Custom CSS for centering the card */
        .center-card {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 25vh;
        }
    </style>

</head>
<form method="POST" action="animalProfile.php?id=<?php echo $zims; ?>">
    <button type="submit" class="btn btn-sm btn-success">Back</button>

    <!--Only edit main-->

    <body>
        <main><!-- Main jumbotron for a primary marketing message or call to action -->


            <?php
            //Display Active Form Name
            $query = "SELECT * FROM `forms` WHERE id = ?";
            $result = $user->getDatabase()->runParameterizedQuery($query, "i", array($formID));
            $form = $result->fetch_array(MYSQLI_ASSOC);
            echo "<h2 class = 'text-center'> Past " . $form['title'] . "</h2>";

            $query = "SELECT * FROM welfaresubmission WHERE wid = ?";
            $result = $user->getDatabase()->runParameterizedQuery($query, "d", array($wid));
            $submission = $result->fetch_array(MYSQLI_ASSOC);

            $precision = 2; //number of digits after decimal
            $zim = $submission['zim'];
            $date = $submission['dates'];
            $reason = $submission['reason'];
            $avg_health = round($submission['avg_health'], $precision);
            $avg_nut = round($submission['avg_nutrition'], $precision);
            $avg_pse = round($submission['avg_pse'], $precision);
            $avg_mental = round($rosubmissionw['avg_mental'], $precision);

            ?>

            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="center-card">

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Welfare Entry #
                                        <?php echo $wid ?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item"><strong>ZIM:</strong>
                                            <?php echo $zim; ?>
                                        </li>
                                        <li class="list-group-item"><strong>Date:</strong>
                                            <?php echo $date; ?>
                                        </li>
                                        <li class="list-group-item"><strong>Reason:</strong>
                                            <?php echo $reason; ?>
                                        </li>
                                        <li class="list-group-item"><strong>Health Average:</strong>
                                            <?php echo $avg_health; ?>
                                        </li>
                                        <li class="list-group-item"><strong>Nutrition Average:</strong>
                                            <?php echo $avg_nut; ?>
                                        </li>
                                        <li class="list-group-item"><strong>Average PSE:</strong>
                                            <?php echo $avg_pse; ?>
                                        </li>
                                        <li class="list-group-item"><strong>Average Mental:</strong>
                                            <?php echo $avg_mental; ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            </div>

            <div class="container">
                <form method="POST" class="text-center">
                    <!-- had to move this here so we can access the array in post. The table has to be within the form -->
                    <table class="table table-bordered" id="myTable">

                        <tbody>
                            <?php
                            //display
                            $count = 1;
                            for ($secNum = 1; $secNum <= $sections->num_rows; $secNum++) {
                                $query = "SELECT q.question, q.id, hsq.id
                                        FROM questions q
                                        JOIN hasSectionQuestions hsq ON q.id = hsq.question_id
                                        WHERE hsq.section_id = ? and hsq.form_id = ?";
                                $result = $user->getDatabase()->runParameterizedQuery($sql, "ii", array($secNum, $formID));
                                while ($question = $result->fetch_array(MYSQLI_ASSOC)) {
                                    if ($count == 1) {
                                        $section = $sections->fetch_array(MYSQLI_ASSOC); ?>
                                        <tr>
                                            <th class="text-center" colspan="3">
                                                <?= htmlspecialchars($section["title"], ENT_QUOTES, 'UTF-8') ?>
                                            </th>
                                        </tr>
                                        <?php
                                    } ?>

                                    <?php
                                    $query = "SELECT responses FROM welfaresubmission WHERE wid = ?";
                                    $responses = $user->getDatabase()->runparameterizedQuery($query, "d", array($wid));
                                    $row = $responses->fetch_array(MYSQLI_ASSOC);
                                    $str = $row['responses'];
                                    ?>

                                    <tr>
                                        <th>
                                            <?= htmlspecialchars($count, ENT_QUOTES, 'UTF-8') ?>
                                        </th>
                                        <td>
                                            <?= htmlspecialchars($question["question"], ENT_QUOTES, 'UTF-8') ?>
                                        </td>
                                        <td>
                                            <?php
                                            if (isset($str[$i]) == true) {
                                                if ($str[$i] !== null) {
                                                    echo htmlspecialchars($str[$i], ENT_QUOTES, 'UTF-8');
                                                    $i++;
                                                }
                                            } ?>
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
            <!--Export to CSV-->
        </main>
    </body>

    <?php
    include_once "footer.php";
    ?>