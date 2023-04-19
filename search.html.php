<html>
    <head>
        <link rel="stylesheet" href="CSS/search.css">
    </head>
   <style>
        body {
            background-image: url('Images/Header/lion_background.jpeg');
            background-position: 53% 53%;
        }
    </style>

<!--Side bar with: add animal, update animal, delete animal options-->
<div class="sidebar"> <p> Quick Actions </p>
    <form method="POST">
      <input type="submit" action="addanimal.php" value="Add Animal" style="margin-bottom: 20px; width: 100px; height: 40px;">
      <input type="submit" action="" value="Update Animal" style="margin-bottom: 20px; width: 100px; height: 40px;">
      <input type="submit" action="" value="Delete Animal" style="width: 100px; height: 40px;">
    </form>
</div>

<!--Main Body of Page-->
<div class="content">
    <!--Search Bar-->
    <div>
      <form method="POST">
        <div class="search-container">
          <input type="text" placeholder="Enter a keyword..." name="search" />
          <input type="submit" value="Search" />
        </div>
      </form>
    </div>
    <!--End Search Bar-->

    <!--Display Box-->
    <div class="box">
    <?php 
      if(isset($_POST['search'])){
        $search = $_POST['search'];
            
        if($conn = mysqli_connect("localhost:3306","georgef","", "zooDB")){
          echo "<p1> Connection succesful <p1>";
        }
            
        $query = "SELECT * FROM searchpage WHERE name LIKE '%$search%' OR species LIKE '%$search%' OR animal_type LIKE '%$search%' OR zims LIKE '%$search%'"; 
                                                  
        $r = mysqli_query($connection, $query);
            
        echo "<p>Showing results for '$search' ... </p> " ;
            
        while($row = mysqli_fetch_array($r)){
          echo "<div class='search-result-box'>";
          echo "<h2><a href='animalprofile.php?id=" . $row['zims'] . "'>" . $row['name'] . "</a></h2>";
          echo "<p><strong>Species:</strong> " . $row['species'] . "</p>";
          echo "<p><strong>Animal Type:</strong> " . $row['animal_type'] . "</p>";
          echo "<p><strong>ZIMS:</strong> " . $row['zims'] . "</p>";
          echo "</div>";
          }
        }
      ?>
    </div>
    <!--End Display Box-->

</div>
<!--End main content-->
 
</html>