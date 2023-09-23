<?php
include_once("Templates/header.php");
?>

<link href="CSS/help.css" rel="stylesheet">


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

<?php
include_once("Templates/footer.php");
?>