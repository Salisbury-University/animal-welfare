<?php
include "Includes/preventUnauthorizedUse.php";

    //gets id
    $formID = $_POST['form'];
    $zims = $_POST['zims'];

    //gets sections
    $sql = "SELECT title FROM `sections`";
    $sections = mysqli_query($connection, $sql);

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
    <link href="CSS/main.css" rel="stylesheet">
    <link href="CSS/forms.css" rel="stylesheet">

    <!--Boostrap javascript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

  </head>

  
    <!--Only edit main-->
<main><!-- Main jumbotron for a primary marketing message or call to action -->
    <form method="POST" action="animalprofile.php?id=<?php echo $zims; ?>">
    	<button type="submit" class="btn btn-sm btn-success">Back</button>
    </form>

<?php
    //Display Active Form Name
    
    $sql = "SELECT * FROM `forms` WHERE id = " . $formID;
    $title = mysqli_query($connection, $sql);
    $title = mysqli_fetch_array($title);
    echo "<h2 class = 'text-center'>" . $title['title'] . "</h2>";
?>

    <div class="container">
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

                    <tr>
                        <th><?= htmlspecialchars($count, ENT_QUOTES, 'UTF-8') ?></th>
                         <td><?= htmlspecialchars($quest["question"], ENT_QUOTES, 'UTF-8') ?></td>
                         <td contenteditable="true">&nbsp</td>
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
    <form method="POST" class = "text-center" action="animalprofile.php?id=<?php echo $zims; ?>">
    	<button type="submit" class="btn btn-success">Submit</button>
    </form>
        
    <!--Export to CSV-->

</main>
</body>
