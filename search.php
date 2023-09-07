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
    <main>
      <div class = "container">
      <div class = "row">


      <!--Main Search Box--> 
          <div class = "col">

            <p class="text-white"> Search: </p>
              <form class="form-inline mr-auto" method = "POST">
                <input class="form-control" type="text" placeholder="Enter a keyword..." name="search">
                <button class="btn btn-success btn-sm my-0 ml-sm-2" type="submit">Search</button>
              </form>
            
           </div>
       <!--End Search--> 

       <p>&nbsp</p> <!--Space between col-->
          
         <!--Animal Actions-->
          <div class="col">
            <p class="text-white"> Quick Animal Actions </p>
                <a class="btn btn-success" href="createAnimal.php" role="button">Add &raquo;</a>

          </div>
        


          </div> <!--Close Row-->
        </div> <!--Close Container-->

          <!--Result Box-->
          <div class = "my-container">
            <div class="box">
            <!-- This is where the search results will be displayed -->
            <?php 
              if(isset($_POST['search'])){
                $search = $_POST['search'];
                    
                $query = "SELECT * FROM `animals` WHERE name LIKE '%$search%' OR `species_id` LIKE '%$search%' OR `section` LIKE '%$search%' OR `id` LIKE '%$search%'"; 
                            
                $r = mysqli_query($connection, $query);
                
                if ($check = mysqli_fetch_array($r) == NULL) { //If no results
                  echo '<h1 class="text-white">No results for "' . $search . '"</h1>';
                }
                $r = mysqli_query($connection, $query);


                  if($search == '' || $search == ' '){
                    $search = "all";
                  }
                 

                  while($row = mysqli_fetch_array($r)){ //Individual animals
                    echo "<div class='search-result-box'>";
                    echo "<h2><a href='animalprofile.php?id=" . $row['id'] . "'>" . $row['id'] . "</a></h2>";
                    echo "<p><strong>House Name:</strong> " . $row['name'] . "</p>";
                    echo "<p><strong>Species:</strong> " . $row['species_id'] . "</p>";
                    echo "<p><strong>Location:</strong> " . $row['section'] . "</p>";
                    
                    ?>

                    <div class = 'col'>
                    <a class='btn btn-primary' href="animalprofile.php?id=<?php echo $row['id']; ?>" role='button'>View &raquo;</a>
                    <a class='btn btn-success' href="modifyAnimal.php?id=<?php echo $row['id']; ?>" role='button'>Update &raquo;</a>
                    <a class='btn btn-danger' href ="AnimalAction/delete.php?id=<?php echo $row['id'];?>" role='button'>Delete &raquo;</a>
  
 
                    </div>
                    
                    <?php
                    echo "</div>";
                  
                  }
                  echo "</div>";
                  
                  
                  mysqli_close($conn);
                  
              }

              else {
                echo '<p>&nbsp</p>';
                echo '<p>&nbsp</p>';
                echo '<p>&nbsp</p>';
              }
            ?>
          </div>
          </div> <!--Close Box-->
          
       

      </div> <!--Close Jumbotron-->
    
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