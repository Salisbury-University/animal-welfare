<?php
include "../Includes/preventUnauthorizedUse.php";

##Initializes forms variable
$sql = "SELECT * FROM `forms`;";
$forms = mysqli_query($connection, $sql);
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
          <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle my-text-info" href="#" id="navbarDropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Welfare
              </a>
      
              <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <?php while ($form = mysqli_fetch_array($forms, MYSQLI_ASSOC)): ?>
                  <form method="POST" action="Forms.php?id=<?php echo $form['id']; ?>">
                    <button type="submit" class="dropdown-item btn btn-secondary"><?php echo $form["title"]; ?></button>
                  </form>
                <?php endwhile; ?>
              </div>
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
            <a class="nav-link dropdown-toggle my-text-info" href="#" id="navbarDropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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


    <!--Only edit main-->
    <main><!-- Main jumbotron for a primary marketing message or call to action -->
    <main>
        <?php
        //gets id
        $formID = $_GET['id'];
        
        //gets sections
        $sql = "SELECT title FROM `sections`";
        $sections = mysqli_query($connection, $sql);
        ?>

        <!--Back button-->
        <div class="back">
            <form method="POST" action="../welfare.php">
                <input type="submit" value="Back" />
            </form>
        </div>

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

        <!--Submit-->
        <!-- This button will not set any data and return the user to the previous page -->
        <button type="submit" class="btn1 btn-success">Submit</button>

        <!--Export to CSV-->

    </main>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#myTable").on('click', '.update', function() {
                // get the current row
                var currentRow = $(this).closest("tr");

                var col1 = currentRow.find("td:eq(0)").html(); //Test Data: Example Question
                var colid = $(this).data('id'); //Test Data: 66

                $.ajax({
                    url: 'updateData.php',
                    type: 'post',
                    data: {'text' : col1, 'id' : colid},
                    success: function (response) {
                        console.log(response);
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