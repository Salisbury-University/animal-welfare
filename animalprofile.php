
<?php 

$zims= $_GET['id'];

if($conn = mysqli_connect("localhost:3306","georgef"," ", "zooDB")){
   
  }


  //Change the table 
  $query = "SELECT * FROM searchpage WHERE zims= '$zims' "; 
  $r = mysqli_query($conn, $query);

  while($row = mysqli_fetch_array($r)){
  $name = $row['name'];
  $species = $row['species'];
  $location = "N/A";
  $avg = "N/A";
  $lastcheckup = "N/A";
  }

  mysqli_close($conn);

  ?>

<!DOCTYPE html>
<html>
<head>
<link href="CSS/profile.css" rel="stylesheet">
<form action="newsearchpage.php">
		<div class="btn">
			<input type="submit" value="Back">
		</div>
	</form>
	<title>Animal Profile Page</title>
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
	<div class="container">
		<div class="sidebar">
			<div class="animal-image" ></div>
			<ul>
				<li><strong>Name:</strong> <?php echo $name; ?></li>
       			<li><strong>ZIMS:</strong> <?php echo $zims; ?></li>
				<li><strong>Section:</strong> <?php echo " "; ?></li>
				<li><strong>House:</strong> <?php echo " "; ?></li>
				<li><strong>Species:</strong> <?php echo $species; ?></li>
				<li><strong>Sex: </strong> <?php echo ""; ?></li>
				<li><strong>Acq. Date: </strong> <?php echo "" ; ?></li>
				<li><strong>Frequency </strong> <?php echo ""; ?></li>
        		<li><strong>Last Entry:</strong> <?php echo $lastcheckup; ?></li>
			</ul>
		</div>
		<div class="content">
			<div class="box"> <?php /*add an include php file that will calculate the averages and display them*/?>
				Avg. Overall:<?php echo $avg; ?>Section1 Avg.<?php echo $avg;?> Section2 Avg. <?php echo $avg;?>
			</div>
		

      <div class="notebox"> <?php /* change this to the same way you created the search results */ ?>

		<a href url= " " >Entry ID - mm/dd/yyyy @ HH:MM:SS</a>, reason for checkup - Score - @username <br><br>
	  </div>
		</div>
		</div>
	<form method="POST">
		<div class="btn1">
			<input type="submit" value="New Entry" onClick = "newEntry()">
			<input type="submit" action="" value="Search Entry" onClick = "searchEntry()">
			<input type="submit" action="" value="Delete Entry" onClick = "deleteEntry()">
		</div>
	</form>
</body>
</html>
