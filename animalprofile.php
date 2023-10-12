<?php
include_once("Templates/header.php");
include_once("Includes/preventUnauthorizedUse.php");
include_once("Includes/DatabaseConnection.php");
include "getData.php";

##Initializes forms variable
$sql = "SELECT * FROM `forms`;";
$forms = mysqli_query($connection, $sql);



//Custom for animal profile page
//Get ID:
$zims= $_GET['id'];

  
$query = 'SELECT * FROM animals WHERE id= ' . $zims; 
$r = mysqli_query($connection, $query);
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

$query = 'SELECT form_id FROM species WHERE id= \'' . $species . '\'';
$formID = mysqli_query($connection, $query);
$formID = mysqli_fetch_assoc($formID);
$formID = $formID['form_id'];

$sql = "SELECT MAX(dates) AS lastcheckup FROM welfaresubmission";
        $result = mysqli_query($connection, $sql);
        $row = mysqli_fetch_assoc($result);
        $lastcheckup = $row['lastcheckup'];

$sql = "SELECT MAX(dates) as lastfed FROM diet";
        $result = mysqli_query($connection, $sql);
        $row = mysqli_fetch_assoc($result);
        $lastfed = $row['lastfed'];
?>

<link href="CSS/display.css" rel="stylesheet">
<style>
  .scores{
    height:40%;
    overflow-y:auto;
    
  }
  .notebox{
    height: 45%;
    overflow-y:auto;
    border:2px solid black;
  }
</style>

	<!--For search page // this function is not in use-->
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
            //something with getting the zims and wid is not working 
            //because hard coding these in works successfully
            formData.append("wid", wid);
            formData.append("zims", <?php echo $zims; ?>);
            

            // Send an AJAX request to delete the entry
            var xhr = new XMLHttpRequest();
            xhr.open("POST", url, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        
                        alert("Entry with ID " + wid + " deleted successfully.");
                    } else {
                        
                        alert("Error deleting entry with ID " + wid);
                    }
                }
            };
            xhr.send(formData);
        }
      }
    }
  </script>
</head>


<style>
    .scroll {
    max-height: 150px;
    overflow-y: auto;
    border:1px solid black;
}

</style>

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
                            WHERE zim = $zims
                            ORDER BY wid DESC"; 

                            $result = mysqli_query($connection, $stmt);
                            
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
                            $total = $total/$count;
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

                                    $result = mysqli_query($connection, $stmt);

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
            <div class="col-12 col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Welfare Submissions</h5>
                    </div>
                    <div class="card-body">
                        <!-- Content for the graph card -->
                        <canvas id="wellnessChart" style="width:100%;max-width:700px"></canvas>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
                        <script>

                            const xValues = [];
                            const yValues = [];

                            <?php
                            $averages = getData($zims, null, null, null, "avg-yat");
                            foreach ($averages as $checkupDate => $value) {
                                ?>
                                xValues.push("<?php echo $checkupDate; ?>");
                                yValues.push("<?php echo $value; ?>");
                                <?php
                            }
                            ?>

                            const wellnessChart = new Chart("wellnessChart", {
                                type: "line",
                                data: {
                                    labels: xValues,
                                    datasets: [{
                                        fill: false,
                                        lineTension: 0,
                                        backgroundColor: "rgba(0,0,255,1.0)",
                                        borderColor: "rgba(0,0,255,0.1)",
                                        data: yValues
                                    }]
                                },
                                options: {
                                    legend: { display: false },
                                    scales: {
                                        y: [{
                                            ticks: {
                                                min: 0,
                                                max: 5,
                                                stepSize: .5,
                                                beginAtZero: true

                                            }
                                        }],
                                        x: [{
                                            type: 'time',
                                            time: {
                                                parsing: 'YYYY-MM-DD',
                                                unit: 'month',
                                                displayFormats: {
                                                    'day': 'MM/DD/YYYY'
                                                }
                                            }
                                        }],
                                    }
                                }
                            });



                        </script>
                
                        <!-- End of graph card-->
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Average By Section</h5>
                    </div>
                    <!--Start of Polar Chart-->
                    <div class="card-body" style="width:90%;max-width:600px">
                      <!--Initializing Variables-->
                      <?php
                      $sql = "SELECT AVG(`avg_health`), AVG(`avg_nutrition`), AVG(`avg_pse`), AVG(`avg_behavior`), AVG(`avg_mental`) 
                      FROM `welfaresubmission` WHERE `zim` = $zims";
                      $averages = mysqli_query($connection, $sql);
                      $averages = mysqli_fetch_array($averages);

                      $health = $averages['AVG(`avg_health`)'];
                      $health = round($health, 2);

                      $nut = $averages['AVG(`avg_nutrition`)'];
                      $nut = round($nut, 2);
                      
                      $pse = $averages['AVG(`avg_pse`)'];
                      $pse = round($pse, 2);

                      $behavior = $averages['AVG(`avg_behavior`)'];
                      $behavior = round($behavior, 2);

                      $mental = $averages['AVG(`avg_mental`)'];
                      $mental = round($mental, 2);

    
                      ?>
                      <!--End of values-->
                      
                      <!--Start Display-->
                      <canvas id="health" style="width:70%;max-width:600px"></canvas>
                      <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.1.1/chart.min.js"></script>
                      <script>


                      const polar = [];
                      polar.push("<?php echo $health; ?>");
                      polar.push("<?php echo $nut; ?>");
                      polar.push("<?php echo $pse; ?>");
                      polar.push("<?php echo $behavior; ?>");
                      polar.push("<?php echo $mental; ?>");

                      var chrt = document.getElementById("health").getContext("2d");
                      var chartId = new Chart(chrt, {
                          type: 'polarArea',
                          data: {
                              labels: ["Health", "Nutrition", "Mental", "Behavior", "PSE"],
                              datasets: [{
                                
                                  data: polar,
                                  backgroundColor: ['yellow', 'pink', 'lightgreen', 'gold', 'lightblue'],
                              }],
                          },
                          options: {
                              responsive: false,
                                plugins: {
                                        legend: {
                                        position: "right",
                                        align: "middle"
                                        }
                                    }
                              },
                      });

                      </script>
                    </div>
                    <!--End of Polar chart-->
                </div>
            </div>
        </div>
    </main>
    
<?php
include_once("Templates/footer.php");
?>vscode-remote://ssh-remote%2B15.204.175.56/home/rachelp/website/need/getData.php