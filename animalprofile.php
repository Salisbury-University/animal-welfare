<?php
include_once("Includes/DatabaseConnection.php");
include_once("Includes/preventUnauthorizedUse.php");

##Initializes forms variable for header
$sql = "SELECT * FROM `forms`;";
$forms = mysqli_query($connection, $sql);



//Custom for animal profile page
//Get ID:
$zims= $_GET['id'];

  //Change the table 
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

	<!--For search page-->
	<script> 
		function searchEntry() {
		var datestart = prompt("Enter the start date to search from", "Enter date as mm/dd/yyyy");
		var dateend = prompt("Enter the end date to search from", "Enter date as mm/dd/yyyy")
		var textInput = document.createElement("searchinput"); //html id
		textInput.type = "text";
		document.body.appendChild(textInput);
		}

		function newEntry(){
		var popupUrl = "popup.php?id= <?php echo $zims ?>";
		var popupWidth = 450;
		var popupHeight = 600;

		// Open the popup window using JavaScript's window.open() method
		window.open(popupUrl, "Complete Animal Welfare Form", "width=" + popupWidth + ",height=" + popupHeight);
		}

		function deleteEntry(){
			var entryID = prompt("Enter the 'Entry ID' to delete");
			confirm("Are you sure?");
			var textInput = document.createElement("deleteinput");
			textInput.type = "text";
			document.body.appendChild(textInput);
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
					<input type="submit" value="Back">
				</div>
			</form>
			<br>
			<div class="display">
				<ul class="list-group">
					<li class="list-group-item"><strong>Name:</strong> <?php echo $name; ?></li>
					<li class="list-group-item"><strong>ISIS:</strong> <?php echo $zims; ?></li>
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
				<div class="box"> <?php /*add an include php file that will calculate the averages and display them*/?>
					Avg. Overall:<?php echo $avg; ?>Section1 Avg.<?php echo $avg;?> Section2 Avg. <?php echo $avg;?>
				</div>
			
				<div class="notebox"> <?php /* change this to the same way you created the search results */ ?>
					<a href url= " " >Entry ID - mm/dd/yyyy @ HH:MM:SS</a><br>
					Reason for checkup - Score - @username <br><br>
				</div>
			</div>
			
			<div class="btn-group">
				<form method="POST" action="popup.php">
					<input type="hidden" name="form" value="<?php echo $formID; ?>">
					<input type="hidden" name="zims" value="<?php echo $zims; ?>">
					<input type="submit" value="New Entry">
				</form>
				<input type="submit" action="" value="Search Entry" onClick = "searchEntry()">
				<input type="submit" action="" value="Delete Entry" onClick = "deleteEntry()">
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