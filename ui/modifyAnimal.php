<?php
include_once("/home/joshb/website/final/slog/animal-welfare/ui/model/header.php");
?>

<link href="/home/joshb/website/final/slog/animal-welfare/style/admin.css" rel="stylesheet">
    <!--Only edit main-->
    <main>
      <?php
        include_once("/home/joshb/website/final/slog/animal-welfare/fnc/db/auth/dbConnection.php");
        include_once("/home/joshb/website/final/slog/animal-welfare/fnc/auth/preventUnauthorizedUse.php");

        //Get ID:
        $zims= $_GET['id'];
        $sql = 'SELECT * FROM `animals` WHERE `id` = ' . $zims;
        $q = mysqli_query($connection, $sql);
        $animal = mysqli_fetch_array($q);

        $sql = 'SELECT DISTINCT `section` FROM `animals` ORDER BY `section` ASC';
        $q = mysqli_query($connection, $sql);
  
      ?>
      
        <!--Start HTML-->
    <div class = "my-container" style="border:5px solid #000000;"" id = 'myTable'>
        <h1>Modify Animal</h1>
        <!--Start form-->       
        <form action='/home/joshb/website/final/slog/animal-welfare/fnc/db/edit.php' method='post'>
            <!--Enter ID--> 
            <div class="form-group">
                <label for="id">ID:</label>
                <input type='text' class="form-control" name='id' placeholder='<?php echo $zims; ?>' readonly>
                <input type='hidden' value='<?php echo $zims; ?>' name='id'>
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

<?php
include_once("/home/joshb/website/final/slog/animal-welfare/ui/model/footer.php");
?>