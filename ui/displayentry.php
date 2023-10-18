<?php 
include "/home/joshb/website/final/slog/animal-welfare/fnc/auth/preventUnauthorizedUse.php";


$wid = $_GET['id'];

$sql = "SELECT zims FROM welfaresubmission WHERE wid = ?";

$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $wid);
$stmt->execute();

$stmt->close();
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
    <link href="/home/joshb/website/final/slog/animal-welfare/style/main.css" rel="stylesheet">
    <link href="/home/joshb/website/final/slog/animal-welfare/style/display.css" rel="stylesheet">

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
      <a href="/home/joshb/website/final/slog/animal-welfare/ui/model/home.php">
        <img src=/home/joshb/website/final/slog/animal-welfare/img/Header/logo.png alt="Logo">
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
                  <form method="POST" action="forms.php?id=<?php echo $form['id']; ?>">
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
            <a class="dropdown-item" href="/home/joshb/website/final/slog/animal-welfare/admin/ui/admin.php">Manage admin</a>
              <a class="dropdown-item" href="/home/joshb/website/final/slog/animal-welfare/admin/ui/admin_createUser.php">Create User</a>
            </div>
          </li>

          <?php } ?> <!--End admin only-->
        </ul>
        <a class="btn btn-success my-2 my-sm-0 float-left" href="/home/joshb/website/final/slog/animal-welfare/fnc/auth/logoutHandler.php" role="button">Logout</a>
      
      </div>
    </nav>
    <hr>
    <!--End Navigation Bar-->


    <!--Only edit main-->
    <main>
      <?php /*
    <form action="animalprofile.php?=">
				<div class="back">
					<input type="submit" value="Back">
				</div>
			</form>
         echo "<p> $wid </p>"; 
        */?>
    </main>
