<?php
// require_once "../vendor/autoload.php";
include_once "header.php";

?>

<link href="../style/help.css" rel="stylesheet">


<main><!-- Main jumbotron for a primary marketing message or call to action -->
<div class = "jumbotron">  

<link href="CSS/help.css" rel="stylesheet">
<div class = "container"> <!--Use container to center all items together-->
<div class = "row">
  <h1 class="display-4 font-weight-bold text-white">Welcome! <small>Review the following sections for help:</small></h1>

  <!--First card-->
  <div class="col-12 col-lg-6 mb-4">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Using the Search Page</h5>
        </div>
        <div class="card-body" style="width:100%;max-width:700px">
        <p class = "font-weight-bold">Search Box:</p>
        <p>
        <ul class = "pl-4">
          <li>Animals can be looked up by name, species, location, or ISIS. All animals matching the search will appear in the results box. </li>
          <li>Admin can add, update, and delete animals from this page.</li>
          <li>Clicking the ISIS or the "view" button will take you to the animal's profile</li>
        </ul>
        </p>
        
        <p class = "font-weight-bold">Animal Profile:</p>
        <ul class = "pl-4">
          <li>Animal Information: Each animal has its own profile displaying their: name, ID, location, species, sex, acquisition date, birthdate, 
          and the date of the last welfare/diet form submitted.</li>
          <li>Past Welfare Entries: Clicking 'add entry’ brings you to the animals’ assigned welfare form where you
         can complete the form, and the results will show up in the "Past Welfare Entries" section. You can also delete 
          an entry from here.</li>
          <li>Diet Tracking: Displays all previous diet submissions. Clicking "Animal Ate Today" allows you
            to submit a new entry. You can also delete from here.</li>
          <li>Diet Tracking: Displays all previous diet submissions. Clicking "Animal Ate Today" allows you
          to submit a new entry. You can also delete from here.</li>
          <li>All Time Welfare Submissions Graph: displays the average score on each date of submission. Clicking
            compare allows you to compare the animal to any other</li>
          <li>All Time Average By Section: shows the overall average for each section</li>
          <li>Diet Tracker Display: shows a comparison for each food, with the amount given as a solid line
            and the amount eaten as a bar</li>
        </ul>
        </p>
        
        
        <p><a class="btn btn-success btn-small" href="search.php" role="button">Search Page &raquo;</a></p>
        </div>
    </div>
  </div>

  <!--Second card (only if admin)-->
      <div class="col-12 col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Using the Admin Pages</h5>
            </div>
            <div class="card-body" style="width:100%;max-width:700px">
            <p class = "font-weight-bold">Manage Admin:</p>
            <p>All users and their passwords are displayed. Passwords are hashed meaning you cannot see them for security purposes.
              Users can be deleted and modified from the table. Deleting is irreversible! Only a users password and admin setting 
              can be updated.</p>
            <p class = "font-weight-bold">Create User:</p>
            <p> Creating a user requires an email and password. To make a user an admin,
              enter 1. To be a regular user, enter 0. Only admin can edit forms and accounts.
            
            </p>

            <p><a class="btn btn-success" href="search.php" role="button">Welfare-Form &raquo;</a></p>
            </div>
        </div>
      </div>
     


</div> <!--Close row-->
</div><!--Close container-->

<!--Close jumbotron-->
</div>
</main>

<?php
include_once "footer.php";
?>