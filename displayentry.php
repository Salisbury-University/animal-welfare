<?php
include "Includes/preventUnauthorizedUse.php";

    
    $formID = $_GET['formID'];
    $wid = $_GET['wid'];
    $zims = $_GET['zim'];
    $i = 0;
    //gets sections
    $sql = "SELECT title FROM `sections`";
    $sections = mysqli_query($connection, $sql);

?>

<?php 
     
              // QUERY: Gets the sections and the # of questions in each
                    $query = "SELECT
                    sec.id, COUNT(*) AS 'num'
                    FROM (SELECT s.id, s.title
                        FROM sections s, hasSectionQuestions hsq 
                        WHERE hsq.form_id='$formID' AND  s.id=hsq.section_id) 
                        AS sec
                    GROUP BY sec.title
                    ORDER BY sec.id";
                
                    $result = mysqli_query($connection, $query);
    
                    $qArr = [];
                    $index = 0;


                if ($result) {
                    
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

    <title>Display Entry</Form></title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="CSS/main.css" rel="stylesheet">
    <link href="CSS/forms.css" rel="stylesheet">

    <!--Boostrap javascript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

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
  <form method="POST" action="animalprofile.php?id=<?php echo $zims; ?>">
    	<button type="submit" class="btn btn-sm btn-success">Back</button>
        
        <!--Only edit main-->
        
<body>
<main><!-- Main jumbotron for a primary marketing message or call to action -->
        

<?php
    //Display Active Form Name
    
    $sql = "SELECT * FROM `forms` WHERE id = " . $formID;
    $title = mysqli_query($connection, $sql);
    $title = mysqli_fetch_array($title);
    echo "<h2 class = 'text-center'> Past " . $title['title'] . "</h2>";

    $sql = "SELECT * FROM welfaresubmission WHERE wid = $wid";
    $result = mysqli_query($connection,$sql);
    $row = mysqli_fetch_array($result);

    $precision = 2; //number of digits after decimal
    $zims = $row['zim'];
    $date = $row['dates'];
    $reason = $row['reason'];
    $avg_health = round($row['avg_health'], $precision);
    $avg_nut = round($row['avg_nutrition'], $precision);
    $avg_pse = round($row['avg_pse'], $precision);
    $avg_mental = round($row['avg_mental'], $precision);
    
?>   

<div class="container">
        <div class="row">
            <div class="col-12">
                <div class="center-card">
                    
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Welfare Entry # <?php echo $wid ?> </h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item"><strong>ZIM:</strong> <?php echo $zims; ?></li>
                                    <li class="list-group-item"><strong>Date:</strong> <?php echo $date; ?></li>
                                    <li class="list-group-item"><strong>Reason:</strong> <?php echo $reason; ?></li>
                                    <li class="list-group-item"><strong>Health Average:</strong> <?php echo $avg_health; ?></li>
                                    <li class="list-group-item"><strong>Nutrition Average:</strong> <?php echo $avg_nut; ?></li>
                                    <li class="list-group-item"><strong>Average PSE:</strong> <?php echo $avg_pse; ?></li>
                                    <li class="list-group-item"><strong>Average Mental:</strong> <?php echo $avg_mental; ?></li>
                                </ul>       
                                </div>
                            </div>
                        </div>
                    
                </div>
            </div>
        </div>
    </div>

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
                            WHERE hsq.section_id = " . $secNum . " and hsq.form_id = " . $formID;
                    $questions = mysqli_query($connection, $sql);
                    while ($quest = mysqli_fetch_array($questions)) {
                        if ($count == 1) {
                            $sec = mysqli_fetch_array($sections); ?>
                            <tr>
                                <th class="text-center" colspan="3">
                                    <?= htmlspecialchars($sec["title"], ENT_QUOTES, 'UTF-8') ?>
                                </th>
                            </tr>
                            <?php } ?>
                            
                            <?php 
                                $sql = "SELECT responses FROM welfaresubmission WHERE wid = $wid";
                                $result = mysqli_query($connection,$sql);
                                $row = mysqli_fetch_array($result);
                               
                                $str = $row['responses'];
                                
                            ?>
                            
                            <tr>
                                <th><?= htmlspecialchars($count, ENT_QUOTES, 'UTF-8') ?></th>
                                <td><?= htmlspecialchars($quest["question"], ENT_QUOTES, 'UTF-8') ?></td>
                                <td> <?= htmlspecialchars($str[$i++], ENT_QUOTES,'UTF-8')?></td>
                                
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

    <?php           
        mysqli_close($connection);
    ?>
    <!--Export to CSV-->
</main>
</body>
