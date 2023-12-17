<?php
include "header.php";

// use admin\SessionUser;

require_once "../admin/SessionUser.php";
require_once "../db/Animal.php";
SessionUser::sessionStatus();

$user = unserialize($_SESSION['user']);
$user->openDatabase();

$zims = $_GET['zim'];

$query = "SELECT id FROM animals;";
$result = $user->getDatabase()->runQuery_UNSAFE($query);
?>

<html>
<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
</head>
<body>
<form method="POST" action="animalProfile.php?id=<?php echo $zims; ?>">
        <button type="submit" class="btn btn-sm btn-success">Back</button>
</form>

<div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="center-card">
                            <div class="card" style="width: 950px">
                                <div class="card-header">
                                    <h5 class="card-title">Select Animal(s)</h5>
                                </div>
                                <div class="card-body">                                
                                <form method="POST" action = "graph.php?">
                                <?php
                                echo "<input type = 'radio' name = 'selected[0]' value='".$zims."' checked = 'checked'> ".$zims." ";
                                $i = 1;
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                                        if( $row['id'] == $zims)
                                            continue;
                                        echo "<input type='radio' name ='selected[" .$i ."]' value='". $row['id']." '  > ". $row['id'];   
                                        ++$i;
                                        //
                                    }
                                }
                                ?>
                                    <div class = "card-footer text-center">
                                    <button type="submit" name = "sub" class="btn btn-primary mt-3">Submit</button>
                                    <input type="hidden" name = "id" value = "<?php echo $zims; ?> ">
                                    </form>
                                    <div class="spacer">
                                        <?php for ($i = 0; $i < 40; ++$i)
                                        echo "&nbsp"; ?>
                                    </div>
                                    <form>
                                    <button type="submit" name = "clear">Clear</button>
                                    <input type="hidden" name = "zim" value = "<?php echo $zims; ?> ">
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            </div>

            <div class="spacer">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        <?php echo "&nbsp";?>
                    </div>
                </div>
            </div>


</body>
</html>

<?php if (isset($_POST['clear'])){header("Refresh:0");}?>



<?php include_once("footer.php"); ?>
