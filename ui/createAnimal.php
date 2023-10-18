<?php
include_once("/home/joshb/website/final/slog/animal-welfare/ui/model/header.php");
?>

<link href="/home/joshb/website/final/slog/animal-welfare/style/admin.css" rel="stylesheet">

    <!--Only edit main-->
    <main>
      <?php
        include_once("/home/joshb/website/final/slog/animal-welfare/fnc/auth/loginError.php"); //Displays error if something is NULL

        //Displays Sections:
        $sql = 'SELECT DISTINCT `section` FROM `animals` ORDER BY `section` ASC';
        $q = $database->runQuery_UNSAFE($sql);

        //Displays Species:
        $sql = 'SELECT DISTINCT `id` FROM `species`';
        $s = $database->runQuery_UNSAFE($sql);
  
      ?>
      
        <!--Start HTML-->
        
    <div class = "my-container" style="border:5px solid #000000;"">
        <h1>Create Animal Form:</h1>
        <!--Start form-->       
        <form action='/home/joshb/website/final/slog/animal-welfare/fnc/db/add.php' method='post'>
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

<?php
include_once("/home/joshb/website/final/slog/animal-welfare/ui/model/footer.php");
?>