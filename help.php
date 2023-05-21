<?php
include "Includes/preventUnauthorizedUse.php";

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
    <link href="CSS/main.css" rel="stylesheet">
    <link href="CSS/help.css" rel="stylesheet">

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
      <a href="home.php">
        <img src=Images/Header/logo.png alt="Logo">
      </a>
    </div>

      <div class="collapse navbar-collapse" id="navbar">
        <ul class="navbar-nav mr-auto">
          <!--Home-->
          <li class="nav-item">
            <a class="nav-link my-text-info" href="home.php">Home</a>
          </li>

          <!--Diet Tracker-->
          <li class="nav-item">
            <a class="nav-link disabled" href="#">Diet Tracker</a>
          </li>

          <!--Search Page-->
          <li class="nav-item">
            <a class="nav-link my-text-info" href="search.php">Search</a>
          </li>
          
          <!--Start Admin Only-->
          <?php
            $isAdmin = checkIsAdmin();
            if($isAdmin == true){ ?>
          
                    <!--Welfare Forms-->
                    <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle my-text-info" href="#" id="navbarDropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Edit Forms
              </a>
      
              <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <?php while ($form = mysqli_fetch_array($forms, MYSQLI_ASSOC)): ?>
                  <form method="POST" action="Forms/Forms.php?id=<?php echo $form['id']; ?>">
                    <button type="submit" class="dropdown-item btn btn-secondary"><?php echo $form["title"]; ?></button>
                  </form>
                <?php endwhile; ?>
              </div>
            </li>
          

          <!--Dropdown menu-->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle my-text-info" href="#" id="navbarDropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Admin
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="admin.php">Manage admin</a>
              <a class="dropdown-item" href="admin_createUser.php">Create User</a>
            </div>
          </li>

          <?php } ?> <!--End admin only-->

        </ul>
        <a class="btn btn-success my-2 my-sm-0 float-left" href="logoutHandler.php" role="button">Logout</a>
      
      </div>
    </nav>
    <hr>
    <!--End Navigation Bar-->


    <!--Only edit main-->
    <main><!-- Main jumbotron for a primary marketing message or call to action -->
      <div class = "jumbotron">  
       <div class="container">
        <h1 class="display-4 font-weight-bold text-white">Welcome! <small>Review the following sections for help:</small></h1>
        <div class = "row">
           <div class = "col">  
           <h4 class = "font-weight-bold text-white">Using the Search Page:</h4>
                <p class = "text-white font-weight-bold">Search Box:</p>

                <p class = "text-white">
                Animals can be looked up by name, species, location, or ISIS. All animals matching the search will appear in the results box. 
                <br><br> Admin can add, update, and delete animals from this page.</p>
                <p class = "text-white font-weight-bold">Animal Profile:</p>
                <p class = "text-white">
                Each animal has its own profile displaying their: name, ID, location, species, sex, acquisition date, birthdate, 
                and the date of the last form submitted. Clicking ‘new entry’ brings you to the animals’ assigned welfare form.
                <p><a class="btn btn-success btn-small" href="search.php" role="button">Search Page &raquo;</a></p>
            </div>

            <h1>&nbsp</h1>

            <!--Only display if admin-->
            <?php
            $isAdmin = checkIsAdmin();
            if($isAdmin == true){ ?>
            
            <div class = "col">  
                <h4 class = "font-weight-bold text-white">Using the Admin Pages:</h4>
                <p class = "text-white font-weight-bold">Manage Admin:</p>
                <p class = "text-white">All users and their passwords are displayed. Passwords are hashed meaning you cannot see them for security purposes.
                  Users can be deleted and modified from the table. Deleting is irreversible! Only a users password and admin setting 
                  can be updated.</p>
                <p class = "text-white font-weight-bold">Create User:</p>
                <p class = "text-white"> Creating a user requires an email and password. To make a user an admin,
                  enter 1. To be a regular user, enter 0. Only admin can edit forms and accounts.
              
                </p>

                <p><a class="btn btn-success" href="search.php" role="button">Welfare-Form &raquo;</a></p>
            </div>
            <?php } ?>

            </div> <!--Close row1-->
        </div> <!--Close container-->
      </div>
    </main>

    <hr>
    <footer class="container-fluid">
        <div class="f-top">
            <div class="row">
                <div class="col">
                    <h4>welfare</h4>
                    <ul>
                        <li><a href="search.php">animals</a></li>
                        <li><a href="#">complete form</a></li>
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
                        <li><a href="#">sections</a></li>
                        <li><a href="#">view all</a></li>
                        <li><a href="#">export data</a></li>
                        <!-- <li><a href="#">interactive map</a></li> -->
                        <!-- <li><a href=''></li> -->
                    </ul>
                </div>
                <div class="col">
                    <h4>help</h4>
                    <ul>
                        <li><a href="help.php">help page</a></li>
                        <!-- <li><a href=''></li> -->
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>