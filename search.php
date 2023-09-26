<?php
include_once("Templates/header.php");
?>

<link href="CSS/search.css" rel="stylesheet">
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
                    $query = "SELECT * FROM `animals` WHERE name LIKE ? OR `species_id` LIKE ? OR `section` LIKE ? OR `id` LIKE ?";
                            $values = array($search, $search, $search, $search);
                            $r = $database->runParameterizedQuery($query, "ssss", $values);

                            if ($check = mysqli_fetch_array($r) == NULL) { //If no results
                              echo '<section class="search-result-item"><h1>No results for "' . $search . '"</h1></section>';
                            }
                            
                            $r = $database->runParameterizedQuery($query, "ssss", $values);
            
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
                                        <a class='btn btn-primary btn-sm' href="animalprofile.php?id=<?php echo $row['id']; ?>" role='button'>View &raquo;</a>
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
                  unset($database);
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

<?php
include_once("Templates/footer.php");
?>