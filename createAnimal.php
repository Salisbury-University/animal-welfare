<?php
##Initializes forms variable for header
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

          <!--Diet Tracker-->
          <li class="nav-item">
            <a class="nav-link disabled" href="#">Diet Tracker</a>
          </li>

          <!--Search Page-->
          <li class="nav-item">
            <a class="nav-link my-text-info" href="search.php">Search</a>
          </li>

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
        </ul>
        <a class="btn btn-success my-2 my-sm-0 float-left" href="logoutHandler.php" role="button">Logout</a>
      
      </div>
    </nav>
    <hr>
    <!--End Navigation Bar-->


    <!--Only edit main-->
    <main>
      <?php
        include_once("Includes/DatabaseConnection.php");
        include_once("Includes/preventUnauthorizedUse.php");
        include "loginError.php"; //Displays error if something is NULL

        //Displays Sections:
        $sql = 'SELECT DISTINCT `section` FROM `animals` ORDER BY `section` ASC';
        $q = mysqli_query($connection, $sql);

        //Displays Species:
        $sql = 'SELECT DISTINCT `id` FROM `species`';
        $s = mysqli_query($connection, $sql);
  
      ?>
      
        <!--Start HTML-->
        
    <div class = "my-container" style="border:5px solid #000000;"">
        <h1>Create Animal Form:</h1>
        <!--Start form-->       
        <form action='AnimalAction/add.php' method='post'>
            <!--Enter ID--> 
            <div class="form-group">
                <label for="id">ID:</label>
                <input type='text' class="form-control" name='id' placeholder='Enter ID'>
            </div>
            <!----> 

            <!--Select Location--> 
            <div class="form-group">
                <label for="location">Location:</label>
                <br>
                
                <?php 
                  while($sections = mysqli_fetch_array($q)) { 
                ?>
      
                <div class="row">
                  <div class="col">
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="location" id="location"
                    value= "<?php echo $sections['section']; ?>"> <?php echo $sections['section']; ?>
                  </div>

                  <?php if($sections = mysqli_fetch_array($q)) { ?>
                  </div>
                  <div class="col">
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="location" id="location"
                    value= "<?php echo $sections['section']; ?>"> <?php echo $sections['section']; ?>
                    </div>
                  </div>

                  <?php  } if($sections = mysqli_fetch_array($q)) { ?>
        
                  <div class="col">
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="location" id="location"
                    value= "<?php echo $sections['section']; ?>"> <?php echo $sections['section']; ?>
                    </div>
                  </div>
                  <?php } 
                  else {
                  ?>  
                  <div class="col">
                    
                  </div>
                  <?php  }?>

                </div>
              


                <?php } ?>
            </div>
            <!----> 


            <!--Sex Dropdown add values--> 
            <div class="form-group">
              <label for="sex">Select Sex:</label>
              <select class="form-control" name = "Sex">
              <option value ="">Select..</option>
              <option value="M">Male</option>
              <option value="F">Female</option>
            </select>      
            </div>
            <!----> 

            <!--Select Birthdate--> 
            <div class="form-group">
                <label for="bd">Birthdate:</label>
                <input type="text" class="form-control" name='bd' placeholder="Year-Month-Day">
            </div>
            <!----> 

            <!--Select Species--> 
            <div class="form-group">
                <label for="ad">Species:</label>
                <input type="text" class="form-control" name='species' placeholder="Enter Species">
            </div>
            <!----> 

            <!--Form ID--> 
            <div class="form-group">
              <label for="form">Select Form:</label>
              <select class="form-control" name = "form">
                <option value ="">Select..</option>
                <option value="1">Education Bird Welfare Assessment Form</option>
                <option value="2">Education Mammal Welfare Assessment Form</option>
                <option value="3">Education Ectotherm Welfare Assessment Form</option>
                <option value="4">Collection Bird Welfare Assessment Form</option>
                <option value="5">Collection Mammal Welfare Assessment Form</option>
                <option value="6">Collection Ectotherm Welfare Assessment Form</option>
              </select>      
            </div>
            <!----> 

            <!--Select Acquisition Date--> 
            <div class="form-group">
              <label for="bd">Acquisition Date:</label>
              <input type="text" class="form-control" name='ad' placeholder="Year-Month-Day">
            </div>
            <!----> 

            <!--Enter Name--> 
            <div class="form-group">
                <label for="name">House Name:</label>
                <input type="text" class="form-control" name='name' placeholder="Enter Name">
            </div>
            <!----> 

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        </div>
        <!--End HTML-->
      
        

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