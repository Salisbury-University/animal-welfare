<?php
include_once("Templates/header.php");

//Custom for animal profile page
//Get ID:
$zims= $_GET['id'];
  
$query = 'SELECT * FROM animals WHERE id=?'; 
$r = $database->runParameterizedQuery($query, "i", array($zims));
$row = mysqli_fetch_array($r);
$name = $row['name'];
$species = $row['species_id'];
$location = $row['section'];
$sex = $row['sex'];
$adate=$row['acquisition_date'];
$birth=$row['birth_date'];
$avg = "N/A";
$lastcheckup = "N/A";

$query = 'SELECT form_id FROM species WHERE id=?';
$formID = $database->runParameterizedQuery($query, "s", array($species));
$formID = mysqli_fetch_assoc($formID);
$formID = $formID['form_id'];

?>

<link href="CSS/display.css" rel="stylesheet">
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
        $result = $database->runQuery_UNSAFE($sql);
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
                    WHERE zim = ?"; 

          $result = $database->runParameterizedQuery($stmt, "d", array($zims));
          
          
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
<?php
include_once("Templates/footer.php");
?>