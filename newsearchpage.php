<?php
include "Includes/preventUnauthorizedUse.php";
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
    <link href="CSS/home.css" rel="stylesheet">
    <link href="CSS/search.css" rel="stylesheet">

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
            <a class="nav-link my-text-info" href="#">Home</a>
          </li>

          <!--Welfare Forms-->
          <li class="nav-item">
            <a class="nav-link my-text-info" href="#">Welfare Forms</a>
          </li>

          <!--Diet Tracker-->
          <li class="nav-item">
            <a class="nav-link disabled" href="#">Diet Tracker</a>
          </li>

          <!--Search Page-->
          <li class="nav-item">
            <a class="nav-link my-text-info" href="#">Search</a>
          </li>

          <!--Dropdown menu-->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle my-text-info" href="#" id="navbarDropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Admin
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="#">Action</a>
              <a class="dropdown-item" href="#">Another action</a>
              <a class="dropdown-item" href="#">Something else here</a>
            </div>
          </li>
        </ul>
        <a class="btn btn-success my-2 my-sm-0 float-left" href="logoutHandler.php" role="button">Logout</a>
      
      </div>
    </nav>
    <hr>
    <!--End Navigation Bar-->

    <!--Only edit main-->
    <main><!-- Main jumbotron for a primary marketing message or call to action -->
  <sidebar>
    <div class="actions">
      <p> Quick Animal Actions </p>
      <form>
        <input type="submit" formaction="addanimal.php" value="Add" style="margin-bottom: 20px; width: 100px; height: 40px;">
        <input type="submit" formaction="" value="Update" style="margin-bottom: 20px; width: 100px; height: 40px;">
        <input type="submit" formaction="" value="Delete" style="width: 100px; height: 40px;">
        
      </form>  
    </div>
</sidebar>
  <article>
    <div class="content">
    <div>
      <form method="POST">
        <div class="search-container">
          <input type="text" placeholder="Enter a keyword..." name="search" />
          <input type="submit" value="Search" />
        </div>
      </form>
    </div>
    <div class="box">
        <!-- This is where the search results will be displayed -->
        <?php 
          if(isset($_POST['search'])){
            $search = $_POST['search'];
            
            if($conn = mysqli_connect("localhost:3306","georgef","3DxJjqHlC1XJkHQT", "zooDB")){
              echo "<p1>  <p1>";
            }
            
            $query = "SELECT * FROM searchpage WHERE name LIKE '%$search%' OR species LIKE '%$search%' OR animal_type LIKE '%$search%' OR zims LIKE '%$search%'"; 
                                                  
            $r = mysqli_query($conn, $query);

              if($search == '' || $search == ' '){
                $search = "all";
              }
              $count = 0;
              while($row = mysqli_fetch_array($r)){
                echo "<div class='search-result-box'>";
                echo "<h2><a href='animalprofile.php?id=" . $row['zims'] . "'>" . $row['name'] . "</a></h2>";
                echo "<p><strong>Species:</strong> " . $row['species'] . "</p>";
                echo "<p><strong>Animal Type:</strong> " . $row['animal_type'] . "</p>";
                echo "<p><strong>ZIMS:</strong> " . $row['zims'] . "</p>";
                echo "</div>";
                ++$count;
              }
              echo "</div>";
              
              
              mysqli_close($conn);
              
          }
        ?>
      </div>
        </article>
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
                        <li><a href="#">coming soon</a></li>
                        <!-- <li><a href=''></li> -->
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>