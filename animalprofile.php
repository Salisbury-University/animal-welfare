<?php
include "Includes/preventUnauthorizedUse.php";
include "Includes/databaseManipulation.php";

include "Templates/header.php";

$database = new databaseManipulation;

//Custom for animal profile page
//Get ID:
$zims= $_GET['id'];

  
$query = 'SELECT * FROM animals WHERE id= ?'; // zims query
$r = $database->runParameterizedQuery($query, "i", array($zims));
$row = mysqli_fetch_array($r);
$name = $row['name'];
$species = $row['species_id'];
$location = $row['section'];
$sex = $row['sex'];
$adate=$row['acquisition_date'];
$birth=$row['birth_date'];
$avg = "N/A";
$lastcheckup = "N/A";
$lastfed = "N/A";

$query = 'SELECT form_id FROM species WHERE id= ?'; // species query
$formID = $database->runParameterizedQuery($query, "s", array($species));
$formID = mysqli_fetch_assoc($formID);
$formID = $formID['form_id'];

$sql = "SELECT MAX(dates) AS lastcheckup FROM welfaresubmission";
        $result = $database->runQuery_UNSAFE($sql);
        $row = mysqli_fetch_assoc($result);
        $lastcheckup = $row['lastcheckup'];

$sql = "SELECT MAX(dates) as lastfed FROM diet";
        $result = $database->runQuery_UNSAFE($sql);
        $row = mysqli_fetch_assoc($result);
        $lastfed = $row['lastfed'];
?>


<!doctype html>
<html lang="en">
<head>
    <link href="CSS/display.css" rel="stylesheet">
    <script> 

            var reload_ = false

            function getReason(){
                if(!reload_){

                var reason = prompt("Please enter the reason for the welfare submission");
                var confirmed = confirm("Is this correct? '" + reason + "'");
                
                
                if(!confirmed){
                    getReason();
                }
                else{
                    document.getElementById('REASON').value = reason;
                    reload_=true;
                    }
                }
            }

            function deleteEntry() {
            var wid = prompt("Enter the 'Entry ID' to delete");

            if (wid !== null && wid !== "") {
                var confirmed = confirm("Are you sure you want to delete entry with ID " + wid + "?");

                if (confirmed) {
                    
                    var url = "delete_w_entry.php";
                    var formData = new FormData();
                    formData.append("wid", wid);
                    formData.append("zims", <?php echo $zims; ?>);
                    

                    // Send an AJAX request to delete the entry
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", url, true);    
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                            alert("Entry with ID " + wid + " deleted successfully.");
                            location.reload();
                                
                            } else {
                                
                                alert("Error deleting entry with ID " + wid);
                            }
                        }
                    };
                    xhr.send(formData); 
                
                }
            }
            location.reload();
            }
</script>
<style>
    .scroll {
    max-height: 150px;
    overflow-y: auto;
    border:1px solid black;
}

</style>
</head>
<body>
    <!-- Main Content -->
    <main class="container-fluid">
        <div class="row">
            <!-- Back Button -->
            <div class="col-12">
                <form action="search.php">
                    <div class="back float-left">
                        <input type="submit" value="Back" class="btn btn-info">
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Animal Information Card -->
        <div class="row">
            <div class="col-12 col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Animal Information</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <!-- Display animal information here -->
                            <li class="list-group-item"><strong>Name:</strong> <?php echo $name; ?></li>
                            <li class="list-group-item"><strong>ZIM:</strong> <?php echo $zims; ?></li>
                            <li class="list-group-item"><strong>Section:</strong> <?php echo $location; ?></li>
                            <li class="list-group-item"><strong>Species:</strong> <?php echo $species; ?></li>
                            <li class="list-group-item"><strong>Sex:</strong> <?php echo $sex; ?></li>
                            <li class="list-group-item"><strong>Acquisition Date:</strong> <?php echo $adate; ?></li>
                            <li class="list-group-item"><strong>Birthdate:</strong> <?php echo $birth; ?></li>
                            <li class="list-group-item"><strong>Last W. Entry (YYYY-MM-DD):</strong> <?php echo $lastcheckup; ?></li>
                            <li class="list-group-item"><strong>Last Fed (YYYY-MM-DD):</strong> <?php echo $lastfed; ?></li>
                            <!-- ... (other animal info) ... -->
                        </ul>
                    </div>
                    <div class ="card-footer">
                        <br>
                </div>
                </div>
            </div>
            
            <!-- Notes Card -->
            <div class="col-12 col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Past Welfare Entries</h5>
                    </div>
                    <div class="card-body">
                        <div class="scroll">
                        <!-- Display notes from past entries here -->
                        <?php 
                            $stmt = "SELECT wid, dates, reason, avg_health, avg_nutrition, avg_pse, avg_behavior, avg_mental 
                            FROM welfaresubmission 
                            WHERE zim = ?
                            ORDER BY wid DESC"; 

                            $result = $database->runParameterizedQuery($stmt, "i", array($zims));
                            
                            //$r = 226;//201+25=226
                            //$g = 240;//215+25=240
                            //$b = 205;//200+5=205
                            
                            $precision = 2; //number of digits after decimal
                            $count = 0;
                            $total = 0;
                            while($row = mysqli_fetch_array($result)){
                                
                                $average = 0;
                                $avg_health = $row['avg_health'];
                                $avg_nutrition = $row['avg_nutrition'];
                                $avg_pse = $row['avg_pse'];
                                $avg_behavior = $row['avg_behavior'];
                                $avg_mental = $row['avg_mental'];
                                
                                $average = ($avg_health + $avg_behavior + $avg_nutrition + $avg_pse + $avg_mental)/5;
                                
                                if($average > 2.5){
                                    $r = 181;
                                    $g = 209;
                                    $b = 140;
                                }
                                elseif($average <= 2.5 && $average >1.5){
                                    $r = 201;
                                    $g = 210;
                                    $b = 45;
                                }
                                elseif($average <= 1.5){
                                    $r = 209;
                                    $g = 143;
                                    $b = 140;
                                }

                                round($average, $precision);

                                echo '<div style="border:1px solid black; background:rgba('.$r.','.$g.','.$b. ', .6);">';
                                echo '<p> &rarr; |[<a href="displayentry.php?zim='.$zims.'&formID='.$formID. '&wid=' . $row['wid'] . '">' . $row['dates'] . '</a> ]|[ W. Entry ID ' . $row['wid'] . ']|[ ' . $row['reason'] . ' ]|[ Entry Average: ' . number_format($average, 2) . ']</tr><br>';
                                echo '</div>';
                            
                                $count++;
                                $total += $average;
                            }
                                // Fix division by zero error.
                            if($count != 0)
                                $total = $total/$count;
                            else
                                $total = 0;

                            $total = round($total, $precision);

                        ?>
                        </div>
                            <?php echo "All time overall: $total";?> 
                    </div>
                    <div class="card-footer text-center">
                        <!-- Add and Delete buttons -->
                        <div class="btn-group">
                            <form method="POST" action="popup.php?id=<?php echo $zims; ?>" onClick="getReason()">
                                <input type="hidden" name="form" value="<?php echo $formID; ?>">
                                <input type="hidden" name="zims" value="<?php echo $zims; ?>">
                                <input type="hidden" name="reason" id="REASON">
                                <input type="submit" value="Add Entry" class="btn btn-success">
                            </form>
                            <div class = "spacer" stlye="border: 1px solid black">&nbsp</div>
                            <input type="submit" class="btn btn-danger" action="" value="Delete Entry" onClick="deleteEntry()">
                        </div>
                    </div>
               
                
                <!-- Diet Tracking Card -->
                            <div class = "spacer" style="border: 1px solid black;"> </div>
                            <div class="card-header">
                                <h5 class="card-title">Diet Tracking</h5>
                            </div>
                            <div class="card-body">
                                <div class = "scroll">
                                <!-- Display diet tracking information here -->
                                <?php
                                    $stmt = "SELECT * FROM diet ORDER BY dates DESC";

                                    $result = $database->runQuery_UNSAFE($stmt);

                                    $r = 201;
                                    $g = 215;
                                    $b = 200;

                                    while($row=mysqli_fetch_array($result)){
                                        echo '<div style="border:1px solid black; background:rgba('.$r.','.$g.','.$b. ', .6);">';
                                        echo '<p> &rarr; |[<a href=".php?zim='.$zims.'">' . $row['dates'] . '</a> ]|[ D. Entry ID ' . $row['did'] . ']|[ ' . $row['food'] .']|[ '.$row['quantity']. " ".$row['units']. ']</tr><br>';
                                        echo '</div>';
                                    }
                                    
                                ?>
                                </div>
                            </div>
                                    
                            <div class = "card-footer text-center">
                            <div class = "btn-group">
                                <form method = "POST"  action="ate.php?id=<?php echo $zims; ?>"> 
                                <input type = "submit" value = "Aniaml Ate Today" class = "btn btn-success">
                                </form>
                            </div>
                        </div>                        
                        </div>
            </div>
            </div>
        <!-- Graph Card -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Graph</h5>
                    </div>
                    <div class="card-body">
                        <!-- Content for the graph card -->
                        <!-- You can implement graph display here -->
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <hr>

</body>
</html>
<?php include_once("Templates/footer.php"); ?>