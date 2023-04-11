<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="public.css">
    <style>
      /* Center the content */
      main {
        display: flex;
        flex-direction: column;
        align-items: center;
	      justify-content: center;
    	}
      .box {
        border: 6px solid black;
        width: 700px;
        height: 400px;
	      padding: 10px;
	      background-color: #cbdea6;
        opacity: .9;
        
        border-radius: 10px;

        /* Center the box */
        display: flex;
        justify-content: center;
        /*align-items: center; this centered the text into the mid of box*/

        margin-top: 10px;
        margin-bottom: 20px;

        vertical-align: top;
      }

      /* Center the search bar and submit button */
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
      	border-radius:10px;
	      border: solid black 2px;
      }    
</style>
  </head>
  <body>
    <?php include 'header.php'; include 'databaselibv2.php';?>
    <main>
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
            
            if($conn = mysqli_connect("localhost:3306"," "," ", "zooDB")){
              echo "<p1> Connection succesful <p1>";
            }
            
            $query = "SELECT * FROM searchpage WHERE name LIKE '%$search%' OR species LIKE '%$search%' OR animal_type LIKE '%$search%' OR zims LIKE '%$search%'"; 
                                                  
            $r = mysqli_query($conn, $query);
            
              echo "<p>Showing results for '$search' ... </p> " ;
              echo "<table border = '1'> 
                      <thead>
                          <tr>
                            <th> name </th>
                            <th> species </th>
                            <th> animal type </th>
                            <th> zims </th>
                          </tr> 
                      </thead>";
            
              
              while($row = mysqli_fetch_array($r)){
                echo "<tr>";
                echo "<td> <a href='animalprofile.php?id= " . $row['zims'] . "'>" . $row['name'] . "</a></td>";
                echo "<td>" . $row['species'] . "</td>";
                echo "<td>" . $row['animal_type'] . "</td>";
                echo "<td>" . $row['zims'] . "</td>";
                echo "</tr>";
              }
              
              echo "</table>";
              mysqli_close($conn);
              echo "<p> connection closed <p>";
          }
        ?>
      </div>
    </main>
  </body>
	<?php // <footer>&copy; GRDJ 2023</footer> ?>
</html>
