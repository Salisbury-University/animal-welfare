<?php
// require_once "../vendor/autoload.php";
include_once "header.php";
// use admin\SessionUser;

require_once "../admin/SessionUser.php";
SessionUser::sessionStatus();

$user = unserialize($_SESSION['user']);
$user->openDatabase();

//gets zims and form id from ajax
$formID = $_GET['form'];
$zims = $_GET['zims'];
$reason = $_GET['reason'];

    //gets sections
$sql = "SELECT title FROM `sections`";
$sections = $user->getDatabase()->runQuery_UNSAFE($sql);

    // QUERY: Gets the sections and the # of questions in each
$query = "SELECT
sec.id, COUNT(*) AS 'num'
FROM (SELECT s.id, s.title
    FROM sections s, hasSectionQuestions hsq 
    WHERE hsq.form_id = ? AND  s.id = hsq.section_id) 
    AS sec
GROUP BY sec.title
ORDER BY sec.id";

$result = $user->getDatabase()->runParameterizedQuery($query, "i", array($formID));

$qArr = [];
$index = 0;

if ($result) {
        // Loop through the result set and access the columns
    while ($row = mysqli_fetch_assoc($result)) {
        $num = $row['num']; // Access the 'num' column which is number of questions per section
        $qArr[$index++]=$num;
    }
}

$numofsec = mysqli_num_rows($result);
                                                 
?>
                

<!doctype html>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Complete a Form</Form></title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="../style/main.css" rel="stylesheet">
    <link href="../style/forms.css" rel="stylesheet">

    <!--Boostrap javascript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

                
  </head>
  <form method="POST" action="animalProfile.php?id=<?php echo $zims; ?>">
    	<button type="submit" class="btn btn-sm btn-success">Back</button>
        
        <input type="hidden" name="numofsec" value="<?php echo $numofsec; ?>">
        <input type="hidden" name="qArr" value="<?php echo implode(',', $qArr); ?>">
        <input type="hidden" name="zims" value="<?php echo $zims; ?>">
    </form>
        <!--Only edit main-->
        
<body>
<main><!-- Main jumbotron for a primary marketing message or call to action -->
        

<?php
        //Display Active Form Name
    
    $sql = "SELECT * FROM `forms` WHERE id = ?";
    $title = $user->getDatabase()->runParameterizedQuery($sql, "i", array($formID));
    $title = mysqli_fetch_array($title);
    echo "<h2 class = 'text-center'>" . $title['title'] . "</h2>";
?>

<div class="container">
    <form method="POST" class = "text-center"> <!-- had to move this here so we can access the array in post. The table has to be within the form -->
    <table class="table table-bordered" id="myTable">

        <tbody>
            <?php
                //display
                $count = 1;           
                for ($secNum = 1; $secNum <= mysqli_num_rows($sections); $secNum++) {
                    $sql = "SELECT q.question, q.id, hsq.id
                            FROM questions q
                            JOIN hasSectionQuestions hsq ON q.id = hsq.question_id
                            WHERE hsq.section_id = ? and hsq.form_id = ? ";

                    $questions = $user->getDatabase()->runParameterizedQuery($sql, "ii", array($secNum, $formID));

                    while ($quest = mysqli_fetch_array($questions)) {
                        if ($count == 1) {
                            $sec = mysqli_fetch_array($sections); ?>
                            <tr>
                                <th class="text-center" colspan="3">
                                    <?= htmlspecialchars($sec["title"], ENT_QUOTES, 'UTF-8') ?>
                                </th>
                            </tr>
                            <?php } ?>
                            
                            
                            <tr>
                                <th><?= htmlspecialchars($count, ENT_QUOTES, 'UTF-8') ?></th>
                                <td><?= htmlspecialchars($quest["question"], ENT_QUOTES, 'UTF-8') ?></td>
                                <td contenteditable="false" > 
                                    <input type="text" name="values[]" placeholder = "Enter Score 1 - 5">
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

    <!--Submit-->
<form method="POST" action="animalProfile.php?id=<?php echo $zims; ?>">
    <input type="hidden" name="numofsec" value="<?php echo $numofsec; ?>">
    <input type="hidden" name="qArr" value="<?php echo implode(',', $qArr); ?>">
    <input type="hidden" name="zims" value="<?php echo $zims; ?>">
    <input type="hidden" name="reason" value="<?php echo $reason; ?>" >
    <div style = "text-align: center">
      <button type="submit" class="btn btn-success" name="subbtn">Submit</button>
    </div>
</form>

</form>




<?php

    // Runs when a welfare form was submitted.
if (($_SERVER['REQUEST_METHOD'] === 'POST') && (isset($_POST['subbtn']))) {

    echo "<br>";
    echo "Form was submitted!";
    echo "<br>";
                            
    $numofsec = $_POST['numofsec'];
    $qArr = explode(',', $_POST['qArr']);
    $values = $_POST['values']; 
    $zims = $_POST['zims'];
    $reason = $_POST['reason'];
    

    $counter = 0;
    $tempcount = 0;
    $averages = array();

    $valstr = " ";

        // Calculate averages of all the sections
    for($i = 0; $i < $numofsec; $i++ ){
        $total = 0;
        $counter = $tempcount;
        
        for($j = 0; $j < $qArr[$i]; $j++){
            $responses.=strval($values[$counter]);
            $total += $values[$counter]; 

            $counter += 1;
        }
        $tempcount = $counter;
        $averages[$i] = $total/$qArr[$i]; 
    }
    
    $date = date('Y-m-d');
    $avg_health = $averages[0];
    $avg_nutrition = $averages[1];
    $avg_pse = $averages[2];
    $avg_behavior = $averages[3];
    $avg_mental = $averages[4];

        // Construct the SQL query as a string
    $query = "INSERT INTO welfaresubmission (zim, dates, reason, avg_health, avg_nutrition, avg_pse, avg_behavior, avg_mental, responses) VALUES (?,?,?,?,?,?,?,?,?)";
    $result = $user->getDatabase()->runParameterizedQuery($query, "issddddds", array($zims, $date, $reason, $avg_health, $avg_nutrition, $avg_pse, $avg_behavior, $avg_mental, $responses));

    ?>
    <script>
    window.location.href = "animalProfile.php?id=" + <?php echo $zims; ?>;
    </script>

    <?php
}//end of block
?>
    <!--Export to CSV-->

   <script> getReason();</script>
</main>
</body>


<?php
include_once("footer.php");
?>