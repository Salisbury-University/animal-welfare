<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="public.css">
    <style>
  main {
  display: flex;
  width: 90%;
  }

.sidebar {
  border: 2px solid black;
  width: 100px;
  height: 275px;
  padding: 20px;
  background-color: grey;
  border-radius: 10px;
  margin-left: 50px;
  margin-top: 125px;

  text-align: center;
  
}
.sidebar input[type="submit"] {
  font-size: 12px;
  text-align: center;

}

.content {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  flex-grow: 1;
}

.box {
  border: 4px solid black;
  width: 700px;
  height: 400px;
  padding: 10px;
  background-color: #cbdea6;
  opacity: .95;
  border-radius: 10px;
  display: flex;
  justify-content: center;
  margin-top: 10px;
  margin-bottom: 20px;
  vertical-align: top;
  text-align: center;
}

form {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.search-container {
  display: flex;
  align-items: center;
  padding-top: 10px;
  padding-bottom: 5px;
}

input[type="text"],
input[type="submit"] {
  margin: 0 5px;
  border-radius: 10px;
  border: solid black 2px;
}
.search-results-container {
  display: flex;
  flex-wrap: wrap;
}

.search-result-box {
  border: 2px solid black;
  border-radius: 15px;
  padding: 10px;
  margin: 10px;
  width: 500px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background: white;
}

.search-result-box h2 {
  margin-top: 0;
  font-size: 24px;
}

.search-result-box p {
  margin: 0;
  font-size: 16px;
}

</style>
  </head>
  <body>
    <?php include 'header.php';?>
    <main>
  <div class="sidebar"> <p> Quick Actions </p>
  <form method="POST">
    <input type="submit" action="addanimal.php" value="Add Animal" style="margin-bottom: 20px; width: 100px; height: 40px;">
    <input type="submit" action="" value="Update Animal" style="margin-bottom: 20px; width: 100px; height: 40px;">
    <input type="submit" action="" value="Delete Animal" style="width: 100px; height: 40px;">
  </form>
</div>


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
            
            if($conn = mysqli_connect("localhost:3306","georgef","", "zooDB")){
              echo "<p1> Connection succesful <p1>";
            }
            
            $query = "SELECT * FROM searchpage WHERE name LIKE '%$search%' OR species LIKE '%$search%' OR animal_type LIKE '%$search%' OR zims LIKE '%$search%'"; 
                                                  
            $r = mysqli_query($conn, $query);
            
              echo "<p>Showing results for '$search' ... </p> " ;
            
              while($row = mysqli_fetch_array($r)){
                echo "<div class='search-result-box'>";
                echo "<h2><a href='animalprofile.php?id=" . $row['zims'] . "'>" . $row['name'] . "</a></h2>";
                echo "<p><strong>Species:</strong> " . $row['species'] . "</p>";
                echo "<p><strong>Animal Type:</strong> " . $row['animal_type'] . "</p>";
                echo "<p><strong>ZIMS:</strong> " . $row['zims'] . "</p>";
                echo "</div>";
              }
              echo "</div>";

              mysqli_close($conn);
              
          }
        ?>
    </main>
  </body>
	<?php // <footer>&copy; GRDJ 2023</footer> ?>
</html>
