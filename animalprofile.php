
<?php 

include 'animalclass.php';

$zims= $_GET['id'];

if($conn = mysqli_connect("localhost:3306","georgef","3DxJjqHlC1XJkHQT", "zooDB")){
    echo "<p> Connection succesful <p>";
  }


  //Change the table 
  $query = "SELECT * FROM searchpage WHERE zims= $zims "; 
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
<form action="searchv2.php">
		<div class="btn">
			<input type="submit" value="Back">
		</div>
	</form>
	<title>Animal Profile Page</title>
	<style>
		body {
			margin: 0;
			padding: 0;
			font-family: Arial, sans-serif;
			background-color: #f2f2f2;
		}
		.container {
			display: flex;
			flex-direction: row;
			max-width: 1200px;
			margin: 0 auto;
			padding: 20px;
			box-sizing: border-box;
		}
		.sidebar {
			width: 25%;
			background-color: #fff;
			padding: 20px;
			box-sizing: border-box;
			box-shadow: 0 0 10px rgba(0,0,0,0.1);
		}
		.content {
			width: 75%;
			background-color: #fff;
			padding: 20px;
			box-sizing: border-box;
			box-shadow: 0 0 10px rgba(0,0,0,0.1);
		}
		.animal-image {
			margin-bottom: 20px;
			border-radius: 50%;
			width: 200px;
			height: 200px;
			background-color: #ccc;
			background-image: url('animal-image.jpg');
			background-size: cover;
			background-position: center;
		}
		.box {
			background-color: #fff;
			padding: 20px;
			box-sizing: border-box;
			box-shadow: 0 0 10px rgba(0,0,0,0.1);
			margin-bottom: 20px;
		}
    .notebox {
			background-color: #fff;
			padding: 20px;
			box-sizing: content-box;
			height: 200px;
			box-shadow: 0 0 10px rgba(0,0,0,0.1);
			margin-bottom: 20px;
      		border: solid black 1px;
			overflow-y:scroll;
		}
	
    input[type="submit"] {
	margin: 5 100;
	border-radius:10px;
	border: solid black 2px;
	background-color: white;
	}
	.btn1 {
		display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
	}
	
	
	</style>
</head>
<body>
	<div class="container">
		<div class="sidebar">
			<div class="animal-image"></div>
			<ul>
				<li><strong>Name:</strong> <?php echo $name; ?></li>
       			<li><strong>ZIMS:</strong> <?php echo $zims; ?></li>
				<li><strong>Species:</strong> <?php echo $species; ?></li>
				<li><strong>Location:</strong> <?php echo $location; ?></li>
        		<li><strong>Last Entry:</strong> <?php echo $lastcheckup; ?></li>
			</ul>
		</div>
		<div class="content">
			<div class="box">
				Avg. Overall:<?php echo $avg; ?>Section1 Avg.<?php echo $avg;?> Section2 Avg. <?php echo $avg;?>
			</div>
			
      <div class="notebox"> <?php /* change this to the same way you created the search results */ ?>
			<a href url= " " > mm/dd/yyyy @ HH:MM:SS</a>, reason for checkup - Score - @username <br><br>
			<a href url= " " > mm/dd/yyyy @ HH:MM:SS,</a> reason for checkup - Score - @username <br><br>
			<a href url= " " > mm/dd/yyyy @ HH:MM:SS,</a> reason for checkup - Score - @username <br><br>
			<a href url= " " > mm/dd/yyyy @ HH:MM:SS,</a> reason for checkup - Score - @username <br><br>
			<a href url= " " > mm/dd/yyyy @ HH:MM:SS,</a> reason for checkup - Score - @username <br><br>
			<a href url= " " > mm/dd/yyyy @ HH:MM:SS,</a> reason for checkup - Score - @username <br><br>
			<a href url= " " > mm/dd/yyyy @ HH:MM:SS,</a> reason for checkup - Score - @username <br><br>
			<a href url= " " > mm/dd/yyyy @ HH:MM:SS,</a> reason for checkup - Score - @username <br><br>
			<a href url= " " > mm/dd/yyyy @ HH:MM:SS,</a> reason for checkup - Score - @username <br><br>
	  </div>
		</div>
		</div>
	<form method="POST">
		<div class="btn1">
			<input type="submit" action="" value="New Entry">
			<input type="submit" action="" value="Search Entry">
			<input type="submit" action="" value="Delete Entry">
		</div>
	</form>
</body>
</html>
