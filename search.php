<?php
include_once("Includes/DatabaseConnection.php");
include_once("Includes/preventUnauthorizedUse.php");

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

    <div class="container">
        <div class="row ng-scope">
        <!--Filter box-->
        <div class="col-md-3 col-md-push-9">
            <h4><span class="fw-semi-bold">Quick Actions</span></h4>
                <p class="text-muted fs-mini">Click the button to register an animal:</p>
                <a class="btn btn-success" href="createAnimal.php" role="button">Add &raquo;</a>
        </div>
        <!--Close box-->

        <!--Script-->
        <script>
            let cachedFilterData = JSON.parse(sessionStorage.getItem("searchFilter"));
            if (cachedFilterData.length > 0) {
               $.ajax({
               type: 'POST',
               data: 'search='cachedFilterData''
                    
                });
                sessionStorage.removeItem("searchFilter");
            }
        </script>

        <script>
            sessionStorage.setItem("searchFilter", JSON.stringify($_POST['search']));
        </script>
        <!--End Script-->

        <!--Result boxes-->
        <div class="col-md-9 col-md-pull-3">

            <!--Search Box-->
            <div class = "search">
                <h4><span class="fw-semi-bold">Search:</span></h4>
                <form class="form-inline mr-auto" method = "POST">
                    <input class="form-control" type="text" placeholder="Enter a keyword..." name="search">
                    <button class="btn btn-success btn-sm my-0 ml-sm-2" type="submit">Search</button>
                </form>
            </div>
            <!--End Search--> 
                
            <!--Display Animals-->
            <?php
            if(isset($_POST['search'])){
                    $search = $_POST['search'];
                    $query = "SELECT * FROM `animals` WHERE name LIKE '%$search%' OR `species_id` LIKE '%$search%' OR `section` LIKE '%$search%' OR `id` LIKE '%$search%'";          
                            $r = mysqli_query($connection, $query);
                            if ($check = mysqli_fetch_array($r) == NULL) { //If no results
                              echo '<section class="search-result-item"><h1>No results for "' . $search . '"</h1></section>';
                            }
                            
                            $r = mysqli_query($connection, $query);
            
                            if($search == '' || $search == ' '){
                                $search = "all";
                            }
                
                    while($row = mysqli_fetch_array($r)){ //Individual animals
                    
            ?>
                    <section class="search-result-item">
                        <a class="image-link" href="#"><img class="image" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSq7mcOBANGbETdX_Tuo8mOjvFCNSKUq8E3D7D9MoMBBsaW6conIE60hTXxROSx2rynEVc&usqp=CAU">
                        </a>
                        <div class="search-result-item-body">
                            <div class="row">
                                <div class="col-sm-9">
                                    <h4 class="search-result-item-heading"><a href="animalprofile.php?id=<?php echo $row['id']; ?>"><?php echo $row["id"]; ?></a></h4>
                                    <p><strong>House Name:</strong> <?php echo $row["name"]; ?></p> 
                                    <p><strong>Species:</strong> <?php echo $row["species_id"]; ?></p>
                                    <p><strong>Location:</strong> <?php echo $row["section"]; ?></p>
                                </div>
                                <div class="col-sm-3 text-align-center">                            
                                    <div class="buttons">
                                        <a class='btn btn-primary btn-sm' href="animalprofile.php?id=<?php echo $row['id']; ?>" role='button'>View '   ' &raquo;</a>
                                        <p></p>
                                        <a class='btn btn-success btn-sm' href="modifyAnimal.php?id=<?php echo $row['id']; ?>" role='button'>Update &raquo;</a>
                                        <p></p>
                                        <td>
                                          <form action = "AnimalAction/delete.php" method = "post">
                                            <input type = "hidden" name = "id" value = "<?=$row['id']?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Delete &raquo;</button>
                                          </form>
                                        </td>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </section> <!--Close Box-->   
     
            <?php
                  }
                  mysqli_close($conn);
                  
              }

              else {
                echo '<section class="search-result-item">';
                echo '<p>&nbsp</p>';
                echo '<p>.....Results will display here</p>';
                echo '<p>&nbsp</p>';
                echo '</section>';
              }
            ?>          
            </div>
        </div>
    </div>

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
