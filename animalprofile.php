<?php
include "Includes/preventUnauthorizedUse.php";

##Initializes forms variable
$sql = "SELECT * FROM `forms`;";
$forms = mysqli_query($connection, $sql);



//Custom for animal profile page
//Get ID:
$zims= $_GET['id'];

  
$query = 'SELECT * FROM animals WHERE id= ' . $zims; 
$r = mysqli_query($connection, $query);
$row = mysqli_fetch_array($r);
$name = $row['name'];
$species = $row['species_id'];
$location = $row['section'];
$sex = $row['sex'];
$adate=$row['acquisition_date'];
$birth=$row['birth_date'];
$avg = "N/A";
$lastcheckup = "N/A";

$query = 'SELECT form_id FROM species WHERE id= \'' . $species . '\'';
$formID = mysqli_query($connection, $query);
$formID = mysqli_fetch_assoc($formID);
$formID = $formID['form_id'];



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
    <link href="CSS/display.css" rel="stylesheet">

    <!--Boostrap javascript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

    <style>
      .scores{
        height:40%;
        overflow-y:auto;
        
      }
      .notebox{
        height: 45%;
        overflow-y:auto;
        border:2px solid black;
      }
    </style>

	<!--For search page // this function is not in use-->
	<script> 

    var reload_ = false

    function getReason(){
        if(!reload_){

          var reason = prompt("Please enter the reason for the welfare submission");
          var confirmed = confirm("Is this correct? '" + reason + "'");
          
          
          if(!confirmed){
              getReason();
          }
          else{
              document.getElementById('REASON').value = reason;
              reload_=true;
          }
      }
  }

    function deleteEntry() {
    var wid = prompt("Enter the 'Entry ID' to delete");
    
    if (wid !== null && wid !== "") {
        var confirmed = confirm("Are you sure you want to delete entry with ID " + wid + "?");

        if (confirmed) {
            
            var url = "delete_w_entry.php";
            var formData = new FormData();
            //something with getting the zims and wid is not working 
            //because hard coding these in works successfully
            formData.append("wid", wid);
            formData.append("zims", <?php echo $zims; ?>);
            

            // Send an AJAX request to delete the entry
            var xhr = new XMLHttpRequest();
            xhr.open("POST", url, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        
                        alert("Entry with ID " + wid + " deleted successfully.");
                    } else {
                        
                        alert("Error deleting entry with ID " + wid);
                    }
                }
            };
            xhr.send(formData);
        }
    }
}


	</script>
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
		<div class="container-fluid">
		<div class ="row">

		<div class = "col-4 col1">
			<form action="search.php">
				<div class="back">
					<input type="submit" value="Back" class = "btn btn-info">
				</div>
			</form>
			<br>

      <?php 
        $sql = "SELECT MAX(dates) AS lastcheckup FROM welfaresubmission";
        $result = mysqli_query($connection, $sql);
        $row = mysqli_fetch_assoc($result);
        $lastcheckup = $row['lastcheckup'];
      ?>

			<div class="display">
				<ul class="list-group">
					<li class="list-group-item"><strong>Name:</strong> <?php echo $name; ?></li>
					<li class="list-group-item"><strong>ZIM:</strong> <?php echo $zims; ?></li>
					<li class="list-group-item"><strong>Section:</strong> <?php echo "$location"; ?></li>
					<li class="list-group-item"><strong>Species:</strong> <?php echo $species; ?></li>
					<li class="list-group-item"><strong>Sex: </strong> <?php echo "$sex"; ?></li>
					<li class="list-group-item"><strong>Acquisition Date: </strong> <?php echo "$adate" ; ?></li>
					<li class="list-group-item"><strong>Birthdate: </strong> <?php echo "$birth"; ?></li>
					<li class="list-group-item"><strong>Last Entry:</strong> <?php echo $lastcheckup; ?></li>
				</ul>
			</div>

			<div>&nbsp</div>

			<div class="scores">

				<div class="notebox"> 
          <?php 
          
          $stmt = "SELECT wid, dates, reason, avg_health, avg_nutrition, avg_pse, avg_behavior, avg_mental 
                    FROM welfaresubmission 
                    WHERE zim = $zims"; 
          



          $result = mysqli_query($connection, $stmt);
          
          
          while($row = mysqli_fetch_array($result)){
            
            $average = 0;
            $precision = 2; //number of digits after decimal
            $avg_health = $row['avg_health'];
            $avg_nutrition = $row['avg_nutrition'];
            $avg_pse = $row['avg_pse'];
            $avg_behavior = $row['avg_behavior'];
            $avg_mental = $row['avg_mental'];
            
            $average = ($avg_health + $avg_behavior + $avg_nutrition + $avg_pse + $avg)/5;
            round($average, $precision);

            echo '<a href="displayentry2.php?id=' . $row['wid'] . '">Entry #' . $row['wid'] . '</a> | ' . $row['dates'] . ' | ' . $row['reason'] . ' | Entry Average: ' . number_format($average, 2) . '<br>';

          }
          mysqli_close($connection);
        ?>
					
				</div>
			</div>
			
			<div class="btn-group">
				<form method="POST" action="popup.php?id=<?php echo $zims; ?>" onClick="getReason()">
					<input type="hidden" name="form" value="<?php echo $formID; ?>">
					<input type="hidden" name="zims" value="<?php echo $zims; ?>">
          <input type="hidden" name="reason" id = "REASON">
					<input type="submit" value="New Entry" class = "btn btn-success">
				</form>
        
				<input type="submit" class = "btn btn-danger" action="" value="Delete Entry" onClick = "deleteEntry()">

			</div>
		
		</div> <!--Closes first col-->
	
			
		<div class = "col-8 full-img">
		<h1></h1>	
		</div>
	
		</div> <!--Closes whole row-->
		</div> <!--Closes whole profile container-->
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
                        <li><a href="help.php">help page</a></li>
                        <!-- <li><a href=''></li> -->
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>