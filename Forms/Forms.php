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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="../CSS/main.css" rel="stylesheet">
    <link href="../CSS/forms.css" rel="stylesheet">

    <!--Boostrap javascript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

  </head>

  <body>
    <!--Header-->
    <header></header>
    
    <!--Navigation Bar-->
    <hr>
    <nav class="navbar navbar-expand-md my-light">
    
    <!--Logo-->
    <div class = "logo-overlay">
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
            <a class="nav-link my-text-info" href="#">Search</a>
          </li>

          <!--Dropdown menu-->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle my-text-info" href="#" id="navbarDropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Admin
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="#">Action</a>
              <a class="dropdown-item" href="#">Another action</a>
              <a class="dropdown-item" href="#">Something else here</a>
            </div>
          </li>
        </ul>
        <a class="btn btn-success my-2 my-sm-0 float-left" href="../logoutHandler.php" role="button">Logout</a>
      
      </div>
    </nav>
    <hr>
    <!--End Navigation Bar-->


    <!--Only edit main-->
    <main><!-- Main jumbotron for a primary marketing message or call to action -->
        <?php
            include '../Includes/DatabaseConnection.php';

            $formID= $_GET['id']; //gets id from POST

            $sql = "SELECT title FROM `sections`"; //gets sections
            $sections = mysqli_query($connection, $sql);

        ?>

        <!--Back button-->
        <div class="back">
            <form method="POST" action="../welfare.php">  
                <input type="submit" value ="Back"/>  
            </form>
        </div>

        <div class = "container">
          <table class="table table-bordered">
            <tbody>
              <?php
              #displays questions and sections, if statement prints sections
              $count = 1;
              for ($secNum=1; $secNum < mysqli_num_rows($sections); $secNum++) {
                $sql = "SELECT q.question, hsq.id
                from questions q
                join hasSectionQuestions hsq on q.id = hsq.question_id
                where hsq.section_id = ". $secNum ." and hsq.form_id = ". $formID;
                $questions = mysqli_query($connection, $sql);
                while ($quest = mysqli_fetch_array($questions)) {
                    if ($count==1) {
                        #displays sections
                            $sec = mysqli_fetch_array($sections); ?>

                        <tr>
                          <th class="text-center" colspan="3">
                          <?=htmlspecialchars($sec["title"],ENT_QUOTES,'UTF-8')?>
                          </th>
                        </tr>

                <?php
                    }
                ?>
                    <tr>
                        <th><?=htmlspecialchars($count,ENT_QUOTES,'UTF-8')?></th>
                        <td><?=htmlspecialchars($quest["question"],ENT_QUOTES,'UTF-8')?></td>
                        <td><pre>&#9</pre></td>
                    </tr>
                  
                <?php    
                    $count++;
                }
                $count = 1;
            }
            
              ?>
            </tobdy>
          </table>
        </div>

        <!--Submit-->
        
          <button type="submit" class="btn1 btn-success">Submit</button>
        
        <!--Export to CSV-->

    </main>

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