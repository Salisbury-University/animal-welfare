<?php
include "../Includes/preventUnauthorizedUse.php";
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
    <link href="../CSS/main.css" rel="stylesheet">
    <link href="../CSS/forms.css" rel="stylesheet">

    <!--Boostrap javascript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

  </head>

  
    <!--Only edit main-->
    <main><!-- Main jumbotron for a primary marketing message or call to action -->


    <?php
        $zims = $_GET['id'];
        $sql = 
        //gets sections
        $sql = "SELECT title FROM `sections`";
        $sections = mysqli_query($connection, $sql);
        
    ?>


            <div class="container">
            <table class="table table-bordered" id="myTable">
                <tbody>
                    <?php
                    //display
                    $count = 1;
                    for ($secNum = 1; $secNum < mysqli_num_rows($sections); $secNum++) {
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

                                <?php
                            }
                            ?>
                            <tr>
                                <th><?= htmlspecialchars($count, ENT_QUOTES, 'UTF-8') ?></th>
                                <td contenteditable="true" class='input'><?= htmlspecialchars($quest["question"], ENT_QUOTES, 'UTF-8') ?></td>
                                <td >
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


        <div class = "container">
          <table class="table table-bordered">
            
          </table>
        </div>

        <!--Submit-->
        
          <button type="submit" class="btn1 btn-success">Submit</button>
        
        <!--Export to CSV-->

    </main>
</body>
