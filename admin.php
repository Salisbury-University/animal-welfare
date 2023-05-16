<?php
include "Includes/preventUnauthorizedUse.php";

    //Redirect to the homepage if theyre not an admin.
$isAdmin = checkIsAdmin();
if($isAdmin == false){
  header('Location: home.php');
}

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
    <link href="CSS/admin.css" rel="stylesheet">

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

          <!--Welfare Forms-->
          <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle my-text-info" href="#" id="navbarDropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Welfare
              </a>
      
              <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <?php while ($form = mysqli_fetch_array($forms, MYSQLI_ASSOC)): ?>
                  <form method="POST" action="Forms/Forms.php?id=<?php echo $form['id']; ?>">
                    <button type="submit" class="dropdown-item btn btn-secondary"><?php echo $form["title"]; ?></button>
                  </form>
                <?php endwhile; ?>
              </div>
            </li>

          <!--Diet Tracker-->
          <li class="nav-item">
            <a class="nav-link disabled" href="#">Diet Tracker</a>
          </li>

          <!--Search Page-->
          <li class="nav-item">
            <a class="nav-link my-text-info" href="search.php">Search</a>
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
        </ul>
        <a class="btn btn-success my-2 my-sm-0 float-left" href="logoutHandler.php" role="button">Logout</a>
      
      </div>
    </nav>
    <hr>
    <!--End Navigation Bar-->


    <!--Only edit main-->
    <main><!-- Main jumbotron for a primary marketing message or call to action -->
    <?php


      // Display table of users
      $sql = "SELECT email, pass, administrator FROM `users`";
      $result = mysqli_query($connection, $sql);
      if(mysqli_num_rows($result) > 0){
    ?>  
  <h1 class = "text-center"><u>All Users:</u></h1>

    <div class = "container">
     <table class="table table-bordered">
        <thead>
              <tr>
                <th> Email </th>
                <th> Pass </th>
                <th> Is_admin </th>
              </tr>
            </thead>
           
            <?php while($row = mysqli_fetch_array($result)){ ?>
          
            <tbody>
              <tr>
                <td><?=htmlspecialchars($row['email'],ENT_QUOTES,'UTF-8')?></td>
                <td><?=htmlspecialchars($row['pass'],ENT_QUOTES,'UTF-8')?></td>
                <td><?=htmlspecialchars($row['administrator'],ENT_QUOTES,'UTF-8')?></td>
                <td>
                  <form action = "admin_modifyUser.php" method = "post">
                    <input type = "hidden" name = "email" value = "<?=$row['email']?>">
                    <button type="submit" class="btn btn-dark">Modify</button></td>
                  </form>
                <td>
                  <form action = "Admin/deleteUser.php" method = "post">
                    <input type = "hidden" name = "email" value = "<?=$row['email']?>">
                    <button type="submit" class="btn btn-danger">Delete</button>
                  </form>
                </td>
              </tr>
            </tbody>
          <?php
              }
          }
          ?>
        </table>
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
                        <li><a href="#">coming soon</a></li>
                        <!-- <li><a href=''></li> -->
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>