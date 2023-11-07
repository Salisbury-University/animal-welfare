<?php
include_once "header.php";
require_once "../admin/SessionUser.php";

SessionUser::sessionStatus();
?>

<link href="../style/search.css" rel="stylesheet">
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
                <div class="search">
                    <h4><span class="fw-semi-bold">Search:</span></h4>
                    <form class="form-inline mr-auto" method="POST">
                        <input class="form-control" type="text" placeholder="Enter a keyword..." name="search">
                        <button class="btn btn-success btn-sm my-0 ml-sm-2" type="submit">Search</button>
                    </form>
                </div>
                <!--End Search-->

                <!--Display Animals-->
                <?php
                // require_once "../vendor/autoload.php";
                // use admin\SessionUser;

                // SessionUser::sessionStatus();

                $user = unserialize($_SESSION['user']);

                $user->openDatabase();

                if (isset($_POST['search'])) {
                    $search = $_POST['search'];

                    // <NOTE>: Can we not have a safe query here?
                    $query = "SELECT * FROM `animals` WHERE name LIKE '%$search%' OR `species_id` LIKE '%$search%' OR `section` LIKE '%$search%' OR `id` LIKE '%$search%'";
                    $result = $user->getDatabase()->runQuery_UNSAFE($query);  

                    // Check if there arent any results
                    if ($result->num_rows == 0) {
                        echo '<section class="search-result-item"><h1>No results for "' . $search . '"</h1></section>';
                    }


                    while (($row = $result->fetch_array(MYSQLI_ASSOC)) != NULL) { //Individual animals
                
                        ?>
                        <section class="search-result-item">
                            <a class="image-link" href="#"><img class="image"
                                    src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSq7mcOBANGbETdX_Tuo8mOjvFCNSKUq8E3D7D9MoMBBsaW6conIE60hTXxROSx2rynEVc&usqp=CAU">
                            </a>
                            <div class="search-result-item-body">
                                <div class="row">
                                    <div class="col-sm-9">
                                        <h4 class="search-result-item-heading"><a
                                                href="animalProfile.php?id=<?php echo $row['id']; ?>">
                                                <?php echo $row["id"]; ?>
                                            </a></h4>
                                        <p><strong>House Name:</strong>
                                            <?php echo $row["name"]; ?>
                                        </p>
                                        <p><strong>Species:</strong>
                                            <?php echo $row["species_id"]; ?>
                                        </p>
                                        <p><strong>Location:</strong>
                                            <?php echo $row["section"]; ?>
                                        </p>
                                    </div>
                                    <div class="col-sm-3 text-align-center">
                                        <div class="buttons">
                                            <a class='btn btn-primary btn-sm'
                                                href="animalProfile.php?id=<?php echo $row['id']; ?>" role='button'>View
                                                &raquo;</a>
                                            <p></p>
                                            <a class='btn btn-success btn-sm'
                                                href="modifyAnimal.php?id=<?php echo $row['id']; ?>" role='button'>Update
                                                &raquo;</a>
                                            <p></p>
                                            <td>
                                                <form onsubmit="return validate()"> <!-- ? -->
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete &raquo;</button>
                                                </form>

                                                <script>
                                                    function validate() {
                                                        var confirmed = confirm("Are you sure you want to delete?");

                                                        if (confirmed) {
                                                            var url = "../db/_delete_animal.php";
                                                            var formData = new FormData();
                                                            formData.append("id", <?php echo $row['id']; ?>);

                                                            // Send an AJAX request to delete the entry
                                                            var xhr = new XMLHttpRequest();
                                                            xhr.open("POST", url, true);
                                                            xhr.onreadystatechange = function () {
                                                                if (xhr.readyState === 4 && xhr.status === 200) {
                                                                    // Handle the response here, e.g., show a success message
                                                                    alert("Item deleted successfully.");
                                                                }
                                                            };

                                                            xhr.send(formData);

                                                            location.reload();

                                                            // Prevent the form from submitting as we're handling the submission via AJAX
                                                            return false;
                                                        }
                                                        // If not confirmed, prevent form submission
                                                        return false;

                                                    }

                                                </script>
                                            </td>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section> <!--Close Box-->

                        <?php
                    }
                } else {
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
include_once "footer.php";
?>