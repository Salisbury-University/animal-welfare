<?php
// require_once "../vendor/autoload.php";
// GENERAL NOTE: most of these queries can be run using the animal, section, and species class
//               Will need to update these when org is complete and SessionUser is working.
include "header.php";

// use admin\SessionUser;

require_once "../admin/SessionUser.php";
require_once "../db/Animal.php";
SessionUser::sessionStatus();

$user = unserialize($_SESSION['user']);
$user->openDatabase();

$animal = new Animal($_GET['id'], $user);

?>


<!doctype html>
<html lang="en">
<meta content='width=device-width, initial-scale=1' name='viewport'/>

<head>
    <link href="../style/display.css" rel="stylesheet">
    <script>
        //works
        function getReason(){
                var reason = prompt("Please enter the reason for the welfare submission");

                if (reason !== null && reason !== "") { //first check
                    var confirmed = confirm("Is this correct? '" + reason + "'");
                
                    if(confirmed) //second check
                    window.location.href = "addWelfare.php?form=" + <?php echo $animal->getFormID(); ?> +
                    "&zims=" + <?php echo $animal->getID(); ?> + "&reason=" +reason;
                }                           
        }

        //works
        function deleteEntry() {
            var wid = prompt("Enter the 'Entry ID' to delete");

            if (wid !== null && wid !== "") {
                var confirmed = confirm("Are you sure you want to delete entry with ID " + wid + "?");

                if (confirmed) {

                    var url = "../db/_delete_welfare_entry.php";
                    var formData = new FormData();
                    formData.append("wid", wid);
                    formData.append("zims", <?php echo $animal->getID(); ?>);


                    // Send an AJAX request to delete the entry
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", url, true);
                    xhr.onreadystatechange = function () {
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

        //works but doesnt refresh?
        function deleteDiet() {
            var did = prompt("Enter the 'Entry ID' to delete");

            if (did !== null && did !== "") {
                var confirmed = confirm("Are you sure you want to delete entry with ID " + did + "?");
            
                if (confirmed) {
                    
                    //var ig = confirm("Did: " + did + "Zim: " + $animal['zim']);

                    var url = "../db/_delete_diet_entry.php";
                    var formData = new FormData();
                    formData.append("did", did);
                    formData.append("zims", <?php echo $animal->getID(); ?>);
                    
                    // Send an AJAX request to delete the entry
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", url, true);
                    xhr.onreadystatechange = function () {
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
            max-height: 300px;
            overflow-y: auto;
            
        }
    </style>
</head>

<body>
    <!-- Main Content -->
    <main class="container-fluid">
        <div class="row">
            <!-- Back Button -->
            <div class=" col-12">
                <form action="search.php">
                    <div class="back float-left">
                        <input type="submit" value="Back" class="btn btn-info">
                    </div>
                </form>
            </div>
        </div>

        
        <div class="container">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        <div class="center-card">
<!-- the main div -->
        <div class="card" style="width:1000px;max-width:100%">
                                <div class="card-header">
                                    <h5 class="card-title">Animal Information</h5>
                                </div>
                                    <div class="card-body" style="width:100%">
                                        <ul class="list-group">
                                        <!-- Display animal information here -->
                                        <li class="list-group-item"><strong>Name:</strong>
                                            <?php echo $animal->getName(); ?>
                                        </li>
                                        <li class="list-group-item"><strong>ZIM:</strong>
                                            <?php echo $animal->getID(); ?>
                                        </li>
                                        <li class="list-group-item"><strong>Section:</strong>
                                            <?php echo $animal->getSection(); ?>
                                        </li>
                                        <li class="list-group-item"><strong>Species:</strong>
                                            <?php echo $animal->getSpecies(); ?>
                                        </li>
                                        <li class="list-group-item"><strong>Sex:</strong>
                                            <?php echo $animal->getSex(); ?>
                                        </li>
                                        <li class="list-group-item"><strong>Acquisition Date:</strong>
                                            <?php echo $animal->getAcqDay(); ?>
                                        </li>
                                        <li class="list-group-item"><strong>Birthdate:</strong>
                                            <?php echo $animal->getBirthday(); ?>
                                        </li>
                                        <li class="list-group-item"><strong>Last W. Entry (YYYY-MM-DD):</strong>
                                            <?php echo $animal->getLatestCheckupDate(); ?>
                                        </li>
                                        <li class="list-group-item"><strong>Last Fed (YYYY-MM-DD):</strong>
                                            <?php echo $animal->getLastFed(); ?>
                                        </li>
                                        <!-- ... (other animal info) ... -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
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



         <div class="container">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        <div class="center-card">

        <div class="card" style="width:1000px;max-width:1000px">
                                <div class="card-header">
                                    <h5 class="card-title"><?php echo $animal->getName(); ?>'s Past Welfare Entries</h5> 
                                </div>
                                    <div class="card-body">
                                  <!-- main card content -->
                                  <div class="scroll">
                            <!-- Display notes from past entries here -->
                            <?php
                            function generateColorGradient($value, $minValue, $maxValue)
                            {
                                $value = max($minValue, min($maxValue, $value));
                                $hue = (1 - ($value - $minValue) / ($maxValue - $minValue)) * 60; // Interpolate between 0° (red) and 60° (yellow)
                                $r = max(0, min(255, 255 * (1 - abs($hue % 120 - 60) / 60)));
                                $g = max(0, min(255, 255 * (1 - abs($hue % 120 - 60) / 60)));
                                $b = 0;
                                return sprintf("#%02X%02X%02X", $r, $g, $b);
                            }
                            

                            //NEEDS SWITCHING
                            $query = "SELECT wid, dates, reason, avg_health, avg_nutrition, avg_pse, avg_behavior, avg_mental FROM welfaresubmission WHERE zim = ? ORDER BY wid DESC";
                            $result = $user->getDatabase()->runParameterizedQuery($query, "i", array($animal->getID()));

                            //$r = 226;//201+25=226
                            //$g = 240;//215+25=240
                            //$b = 205;//200+5=205
                            
                            $precision = 2; //number of digits after decimal
                            $count = 0;
                            $total = 0;

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                                    $average = 0;
                                    $avg_health = $row['avg_health'];
                                    $avg_nutrition = $row['avg_nutrition'];
                                    $avg_pse = $row['avg_pse'];
                                    $avg_behavior = $row['avg_behavior'];
                                    $avg_mental = $row['avg_mental'];

                                    $average = ($avg_health + $avg_behavior + $avg_nutrition + $avg_pse + $avg_mental) / 5;


                                    // <NOTE>: Some testing is needed with this function and code, but it should practically 
                                    //         create a perfect gradient
                            
                                    //  ::::   $colorCode = generateColorGradient($average, 0, 5);
                                    //  ::::   list($r, $g, $b) = sscanf($colorCode, "#%02x%02x%02x");
                            
                                    // --- START REPLACE: --- 
                                    // Use color vex form
                                    if ($average > 2.5) {
                                        $r = 181;
                                        $g = 209;
                                        $b = 140;
                                    } elseif ($average <= 2.5 && $average > 1.5) {
                                        $r = 201;
                                        $g = 210;
                                        $b = 45;
                                    } elseif ($average <= 1.5) {
                                        $r = 209;
                                        $g = 143;
                                        $b = 140;
                                    }
                                    // --- END REPLACE ---
                            
                                    round($average, $precision);

                                    echo '<div style="max-width:100%;border:1px solid black; background:rgba(' . $r . ',' . $g . ',' . $b . ', .6);">';
                                    echo '<p> &rarr; |[<a href="displayEntry.php?zim=' . $animal->getID() . '&formID=' . $animal->getFormID() . '&wid=' . $row['wid'] . '">' . $row['dates'] . '</a> ]|[ W. Entry ID ' . $row['wid'] . ']|[ ' . $row['reason'] . ' ]|[ Entry Average: ' . number_format($average, 2) . ']</tr><br>';
                                    echo '</div>';
                                    echo "<div class='spacer'>&nbsp&nbsp</div>";

                                    $count++;
                                    $total += $average;
                                }
                                // Fix division by zero error.
                                if ($count != 0)
                                    $total = $total / $count;
                                else
                                    $total = 0;

                                $total = round($total, $precision);
                            } else {
                                echo "<br>&nbsp No previous entries <br><br><br><br>";
                                $total = "N/A";
                            }
                            ?>
                        </div>
                        <?php echo "All time overall: $total"; ?>
                    </div>
                        <div class="card-footer text-center">
                                <!-- Add and Delete buttons -->
                                    <div class="btn-group">
                                            <input type="submit" class="btn btn-success" action="" value="Add Entry" onClick="getReason()">
                                            <div class="spacer" stlye="border: 1px solid black">&nbsp</div>
                                            <input type="submit" class="btn btn-danger" action="" value="Delete Entry" onClick="deleteEntry()">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
         

            <div class="container">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        <div class="center-card">
         <div class="card" style="width:1000px;max-width:1000px">
                                <div class="card-header">
                                    <h5 class="card-title"> <?php echo $animal->getName(); ?>'s All Time Welfare Submissions Graph</h5> 
                                </div>
                                            <div class="card-body">
                                            <!-- main card content -->

                                             <canvas id="wellnessChart" style="width:100%;max-width:1000px"></canvas>
                                            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
                                            <script>

                                                const xValues = [];
                                                const yValues = [];

                                                <?php
                                                // <NOTE>: This file needs to be updated using the Animal class functions.
                                                //          It originally used getData but then stopped halfway through implementation.
                                                // use db\Animal;

                                                // require_once "../db/Animal.php";

                                                // $animalO = new Animal($zim, $user->getDatabase());
                                                
                                                $averages = $animal->getAllCheckupAverages();
                                                // unset($animalO);
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
                                        <div class="card-footer text-center">
                                        <form method="POST" action="compareHandler.php?zim=<?php echo $animal->getID(); ?>">
                                                        <input type="submit" value="Compare" class="btn btn-success">
                                                </div>
                                        </div>
                                            </form>
                                </div>
                            </div>
                        </div>
                    </div>
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
         



         <div class="container">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        <div class="center-card">
                        
         <div class="card" style="width:1000px;max-width:1000px">
                                <div class="card-header">
                                    <h5 class="card-title"><?php echo $animal->getName(); ?>'s Diet Tracking</h5> 
                                </div>
                                    <div class="card-body">
                                        <!-- main card content -->
                                        <div class="scroll">
                                            <!-- Display diet tracking information here -->
                                            <?php
                                            $query = "SELECT * FROM diet WHERE zim = ? ORDER BY dates DESC";                                            
                                            $result = $user->getDatabase()->runParameterizedQuery($query, "i", array($animal->getID()));

                                            $r = 201;
                                            $g = 215;
                                            $b = 200;

                                            if ($result->num_rows > 0) {
                                            //add color coding. if difference is 35% of total red, <35% & >65% yellow, >65% Green
                                            echo "<div class='spacer'>&nbsp&nbsp</div>";
                                            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                                                $quantitygiven = $row['quantitygiven'];
                                                $quantityeaten = $row['quantityeaten'];
                                                $percent = ($quantityeaten / $quantitygiven) * 100;

                                                // <NOTE>: Some testing is needed with this function and code, but it should practically 
                                                //         create a perfect gradient
                                            
                                                //$colorCode = generateColorGradient($percent, 0, 100);
                                                //list($r, $g, $b) = sscanf($colorCode, "#%02x%02x%02x");
                                            
                                                // --- START REPLACE: --- 
                                                if ($percent > 65) {
                                                    $r = 181;
                                                    $g = 209;
                                                    $b = 140;
                                                } elseif ($percent <= 65 && $percent > 35) {
                                                    $r = 201;
                                                    $g = 210;
                                                    $b = 45;
                                                } elseif ($percent <= 35) {
                                                    $r = 209;
                                                    $g = 143;
                                                    $b = 140;
                                                }
                                                // --- END REPLACE --- 
                                                
                                                echo '<div style="border:1px solid black; background:rgba(' . $r . ',' . $g . ',' . $b . ', .6);">';
                                                echo '<p> &rarr; |[<a href="displayMeal.php?zim=' . $animal->getID() . '&did=' . $row['did'] . '">' . $row['dates'] . '</a> ]|[ D. Entry ID ' . $row['did'] . ']|[ ' . $quantitygiven . ' ' . $row['units'] . ' of ' . $row['food'] . ']|[ ' . round($percent, $precision) . '% Consumed ]</tr><br>';
                                                echo '</div>';
                                                echo "<div class='spacer'>&nbsp&nbsp</div>";
                                            }
                                        }else{
                                            echo "<br>&nbsp No previous entries <br><br><br><br>";
                                        }

                                            ?>
                                        </div>
                                        <?php echo "Favorite food:" //find the lowest percent difference and that is the favorite?>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="btn-group">
                                            <form method="POST" action="mealRecord.php?id=<?php echo $animal->getID(); ?>">
                                                <input type="submit" value="Animal Ate Today" class="btn btn-success">
                                            </form>
                                            <div class="spacer" stlye="border: 1px solid black">&nbsp</div>
                                            <input type="submit" class="btn btn-danger" action="" value="Delete Entry" onClick="deleteDiet()">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         </div>
         </div>         
<?php /*
         <div class="spacer">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        <?php echo "&nbsp";?>
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
         <div class="container">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        <div class="center-card">
         <div class="card" stlye="width: 1000px;max-width:1000px">
                                <div class="card-header">
                                    <h5 class="card-title"><?php echo $animal->getName(); ?>'s All Time Average By Section</h5> 
                                </div>
                                    <div class="card-body">
                                  <!-- main card content -->
                                            <!--Start of Polar Chart-->
                                            <div class="card-body">
                                                <!--Initializing Variables-->
                                                <?php
                                                $query = "SELECT AVG(`avg_health`), AVG(`avg_nutrition`), AVG(`avg_pse`), AVG(`avg_behavior`), AVG(`avg_mental`) FROM `welfaresubmission` WHERE `zim` = ?";
                                                $result = $user->getDatabase()->runParameterizedQuery($query, "i", array($animal->getID()));
                                                $row = $result->fetch_array(MYSQLI_ASSOC);

                                                $health = $row['AVG(`avg_health`)'];
                                                $health = round($health, 2);

                                                $nut = $row['AVG(`avg_nutrition`)'];
                                                $nut = round($nut, 2);

                                                $pse = $row['AVG(`avg_pse`)'];
                                                $pse = round($pse, 2);

                                                $behavior = $row['AVG(`avg_behavior`)'];
                                                $behavior = round($behavior, 2);

                                                $mental = $row['AVG(`avg_mental`)'];
                                                $mental = round($mental, 2);


                                                ?>
                                                <!--End of values-->

                                                <!--Start Display-->
                                                <canvas id="health" style="width:900px;max-width:950px"></canvas>
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
                    </div>
                </div>
            </div>
         </div>
         </div>
        </div>
        </div>
         </div>
*/?>
         <div class="spacer">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        <?php echo "&nbsp";?>
                    </div>
                </div>
            </div>

         <div class="container">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        <div class="center-card">
         <div class="card" style="width:1000px;">
                                <div class="card-header">
                                    <h5 class="card-title"><?php echo $animal->getName(); ?>'s Diet Tracker Display</h5> 
                                </div>
                                    <div class="card-body">
                                  <!-- main card content -->
                                              <!--Initializing Variables-->
                                                <?php
                                                $labels = array();
                                                $data = array();
                                                $line = array();

                                                $query = 'SELECT DISTINCT `food` FROM `diet` WHERE zim = ?'; //returns search values for food
                                                $result = $user->getDatabase()->runParameterizedQuery($query, "i", array($animal->getID()));
                                                while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                                                    $a = array(); // array to push averages to data (eaten)
                                                    $a2 = array(); //array to push averages to data (given)
                                                    $var = $row['food'];

                                                    array_push($labels, $var); //push to labels
                                                
                                                    $query2 = "SELECT * FROM `diet` WHERE `food` = '" . $var . "'"; //select all entries where food = var
                                                    $result2 = $user->getDatabase()->runQuery_UNSAFE($query2);
                                                    while ($row2 = $result2->fetch_array(MYSQLI_ASSOC)) { //gets all entries for each food
                                                        $var2 = $row2['quantityeaten']; //get total amount eaten
                                                        array_push($a, $var2); //push into temp array
                                                        $var3 = $row2['quantitygiven']; //get total amount given
                                                        array_push($a2, $var3); //push into another temp array
                                                    }
                                                    $given = 0;
                                                    $eaten = 0;
                                                    $count = 0;
                                                    foreach ($a as $temp) { //push avg of all entries into data
                                                        $count = $count + 1;
                                                        $eaten = $eaten + $temp;
                                                    }
                                                    foreach ($a2 as $temp2) {
                                                        $given = $given + $temp2;
                                                    }

                                                    $eaten = $eaten / $count;
                                                    $given = $given / $count;
                                                    array_push($data, $eaten);
                                                    array_push($line, $given);
                                                    unset($a);
                                                    unset($a2);

                                                }


                                                // What is this?
                                                /*echo "Data: ";
                                                print_r($data);
                                                echo "<br>";


                                                echo "Labels: ";
                                                print_r($labels);
                                                echo "<br>";  */
                                                ?>
                                                <!--End of values-->

                                                <script>

                                                    //initialize data
                                                    const data = [];
                                                    <?php //loop through php array and push into java array
                                                    foreach ($data as $temp) { //push avg of all entries into data
                                                        ?>

                                                        data.push("<?php echo $temp; ?>");

                                                        <?php
                                                    }

                                                    ?>
                                                    //end

                                                    //initialize labels
                                                    const labels = [];
                                                    <?php //loop through php array and push into java array
                                                    foreach ($labels as $temp) { //push avg of all entries into data
                                                        ?>

                                                        labels.push("<?php echo $temp; ?>");

                                                        <?php
                                                    }

                                                    ?>
                                                    //end

                                                    //initalize line
                                                    const line = [];
                                                    <?php //loop through php array and push into java array
                                                    foreach ($line as $temp) { //push avg of all entries into data
                                                        ?>

                                                        line.push("<?php echo $temp; ?>");

                                                        <?php
                                                    }

                                                    ?>
                                                    //end

                                                </script>

                                                <!--Start Display-->
                                                <canvas id="diet" style="width:100%;max-width:900px"></canvas>
                                                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.1.1/chart.min.js"></script>
                                                <script>
                                                    const ctx = document.getElementById('diet');

                                                    const chartData = {
                                                        labels: labels,
                                                        datasets: [
                                                            {
                                                                label: "Food Eaten",
                                                                backgroundColor: "green",
                                                                data: data,
                                                                backgroundColor: [
                                                                    'rgba(54, 162, 235, 0.2)',
                                                                    'rgba(255, 99, 132, 0.2)',
                                                                    'rgba(255, 206, 86, 0.2)',
                                                                    'rgba(75, 192, 192, 0.2)',
                                                                    'rgba(153, 102, 255, 0.2)',
                                                                    'rgba(255, 159, 64, 0.2)'
                                                                ],

                                                                borderColor: [
                                                                    'rgba(54, 162, 235, 1)',
                                                                    'rgba(255,99,132,1)',
                                                                    'rgba(255, 206, 86, 1)',
                                                                    'rgba(75, 192, 192, 1)',
                                                                    'rgba(153, 102, 255, 1)',
                                                                    'rgba(255, 159, 64, 1)'
                                                                ],
                                                                borderWidth: 1,
                                                                stack: 'combined',
                                                                type: 'bar'
                                                            },
                                                        ],
                                                    }

                                                    var lineData = {
                                                        label: "Food Given",
                                                        backgroundColor: "blue",
                                                        data: line,
                                                        borderColor: "blue",
                                                        // stack: 'combined',
                                                        type: 'line',
                                                        tension: 0,
                                                    }
                                                    chartData.datasets.unshift(lineData)

                                                    new Chart(ctx, {
                                                        type: 'bar',
                                                        data: chartData,
                                                        options: {
                                                            responsive: true,
                                                            maintainAspectRatio: true,
                                                            scales: {
                                                                x: {
                                                                    stacked: true,
                                                                },
                                                                y: {
                                                                    stacked: true,
                                                                    title: {
                                                                        display: true,
                                                                        text: 'Ounces'
                                                                    }
                                                                },
                                                            },
                                                        },
                                                    });
                                                </script>
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

            <div class="container">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        <div class="center-card">
         <div class="card" stlye="width: 1000px;max-width:1000px;">
                                <div class="card-header">
                                    <h5 class="card-title"><?php echo $animal->getName(); ?>'s All Time Average By Section</h5> 
                                </div>
                                    <div class="card-body">
                                  <!-- main card content -->
                                            <!--Start of Polar Chart-->
                                            <div class="card-body">
                                                <!--Initializing Variables-->
                                                <?php
                                                $query = "SELECT AVG(`avg_health`), AVG(`avg_nutrition`), AVG(`avg_pse`), AVG(`avg_behavior`), AVG(`avg_mental`) FROM `welfaresubmission` WHERE `zim` = ?";
                                                $result = $user->getDatabase()->runParameterizedQuery($query, "i", array($animal->getID()));
                                                $row = $result->fetch_array(MYSQLI_ASSOC);

                                                $health = $row['AVG(`avg_health`)'];
                                                $health = round($health, 2);

                                                $nut = $row['AVG(`avg_nutrition`)'];
                                                $nut = round($nut, 2);

                                                $pse = $row['AVG(`avg_pse`)'];
                                                $pse = round($pse, 2);

                                                $behavior = $row['AVG(`avg_behavior`)'];
                                                $behavior = round($behavior, 2);

                                                $mental = $row['AVG(`avg_mental`)'];
                                                $mental = round($mental, 2);


                                                ?>
                                                <!--End of values-->

                                                <!--Start Display-->
                                                <canvas id="health" style="width:900px;max-width:950px;"></canvas>
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
                    </div>
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

            <main>
                </body>
                
                </html>
                <?php include_once "footer.php"; ?>
