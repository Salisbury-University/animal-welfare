<?php
// require_once "../vendor/autoload.php";
// GENERAL NOTE: most of these queries can be run using the animal, section, and species class
//               Will need to update these when org is complete and SessionUser is working.
include "header.php";

// use admin\SessionUser;

require_once "../admin/SessionUser.php";
SessionUser::sessionStatus();

$user = unserialize($_SESSION['user']);
$user->openDatabase();

$zim = $_GET['id'];

$query = "SELECT * FROM animals WHERE id=?";
$result = $user->getDatabase()->runParameterizedQuery($query, "i", array($zim));
$row = $result->fetch_array(MYSQLI_ASSOC);

$animal = array(
    'zim' => $zim,
    'name' => $row['name'],
    'species' => $row['species_id'],
    'section' => $row['section'],
    'sex' => $row['sex'],
    'acqDate' => $row['acquisition_date'],
    'bthDate' => $row['birth_date'],
    'avg' => "N/A",
    'lastCheckup' => "N/A",
    'lastfed' => "N/A"
);


$query = "SELECT form_id FROM species WHERE id=?";
$result = $user->getDatabase()->runParameterizedQuery($query, "s", array($animal['species']));
$form = $result->fetch_array(MYSQLI_ASSOC);
$animal['formID'] = $form['form_id'];

$query = "SELECT MAX(dates) AS lastCheckup FROM welfaresubmission WHERE zim = $zim";
$result = $user->getDatabase()->runQuery_UNSAFE($query);
if (($row = $result->fetch_array(MYSQLI_ASSOC)) != NULL) {
    $animal['lastCheckup'] = $row['lastCheckup'];
}

$query = "SELECT MAX(dates) as lastFed FROM diet WHERE zim = $zim";
$result = $user->getDatabase()->runQuery_UNSAFE($query);
if (($row = $result->fetch_array(MYSQLI_ASSOC)) != NULL) {
    $animal['lastFed'] = $row['lastFed'];
}
?>


<!doctype html>
<html lang="en">

<head>
    <link href="../style/display.css" rel="stylesheet">
    <script>
        var reload_ = false

        function getReason() {
            if (!reload_) {

                var reason = prompt("Please enter the reason for the welfare submission");
                var confirmed = confirm("Is this correct? '" + reason + "'");

                if (!confirmed) {
                    getReason();
                }
                else {
                    document.getElementById('REASON').value = reason;
                    reload_ = true;
                }
            }
        }

        function deleteEntry() {
            var wid = prompt("Enter the 'Entry ID' to delete");

            if (wid !== null && wid !== "") {
                var confirmed = confirm("Are you sure you want to delete entry with ID " + wid + "?");

                if (confirmed) {

                    var url = "../db/_delete_welfare_entry.php";
                    var formData = new FormData();
                    formData.append("wid", wid);
                    formData.append("zims", <?php echo $animal['zim']; ?>);


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
            max-height: 150px;
            overflow-y: auto;
            border: 1px solid black;
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
                            <li class="list-group-item"><strong>Name:</strong>
                                <?php echo $animal['name']; ?>
                            </li>
                            <li class="list-group-item"><strong>ZIM:</strong>
                                <?php echo $animal['zim']; ?>
                            </li>
                            <li class="list-group-item"><strong>Section:</strong>
                                <?php echo $animal['section']; ?>
                            </li>
                            <li class="list-group-item"><strong>Species:</strong>
                                <?php echo $animal['species']; ?>
                            </li>
                            <li class="list-group-item"><strong>Sex:</strong>
                                <?php echo $animal['sex']; ?>
                            </li>
                            <li class="list-group-item"><strong>Acquisition Date:</strong>
                                <?php echo $animal['acqDate']; ?>
                            </li>
                            <li class="list-group-item"><strong>Birthdate:</strong>
                                <?php echo $animal['bthDate']; ?>
                            </li>
                            <li class="list-group-item"><strong>Last W. Entry (YYYY-MM-DD):</strong>
                                <?php echo $animal['lastCheckup']; ?>
                            </li>
                            <li class="list-group-item"><strong>Last Fed (YYYY-MM-DD):</strong>
                                <?php echo $animal['lastFed']; ?>
                            </li>
                            <!-- ... (other animal info) ... -->
                        </ul>
                    </div>
                    <div class="card-footer">
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
                            function generateColorGradient($value, $minValue, $maxValue)
                            {
                                $value = max($minValue, min($maxValue, $value));
                                $hue = (1 - ($value - $minValue) / ($maxValue - $minValue)) * 60; // Interpolate between 0° (red) and 60° (yellow)
                                $r = max(0, min(255, 255 * (1 - abs($hue % 120 - 60) / 60)));
                                $g = max(0, min(255, 255 * (1 - abs($hue % 120 - 60) / 60)));
                                $b = 0;
                                return sprintf("#%02X%02X%02X", $r, $g, $b);
                            }

                            $query = "SELECT wid, dates, reason, avg_health, avg_nutrition, avg_pse, avg_behavior, avg_mental FROM welfaresubmission WHERE zim = ? ORDER BY wid DESC";
                            $result = $user->getDatabase()->runParameterizedQuery($query, "i", array($animal['zim']));

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
                            
                                    //$colorCode = generateColorGradient($average, 0, 5);
                                    //list($r, $g, $b) = sscanf($colorCode, "#%02x%02x%02x");
                            
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

                                    echo '<div style="border:1px solid black; background:rgba(' . $r . ',' . $g . ',' . $b . ', .6);">';
                                    echo '<p> &rarr; |[<a href="displayentry.php?zim=' . $animal['zim'] . '&formID=' . $animal['formID'] . '&wid=' . $row['wid'] . '">' . $row['dates'] . '</a> ]|[ W. Entry ID ' . $row['wid'] . ']|[ ' . $row['reason'] . ' ]|[ Entry Average: ' . number_format($average, 2) . ']</tr><br>';
                                    echo '</div>';

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
                            <form method="POST" action="popup.php?id=<?php echo $animal['zim']; ?>"
                                onClick="getReason()">
                                <input type="hidden" name="form" value="<?php echo $animal['formID']; ?>">
                                <input type="hidden" name="zims" value="<?php echo $animal['zim']; ?>">
                                <input type="hidden" name="reason" id="REASON">
                                <input type="submit" value="Add Entry" class="btn btn-success">
                            </form>
                            <div class="spacer" stlye="border: 1px solid black">&nbsp</div>
                            <input type="submit" class="btn btn-danger" action="" value="Delete Entry"
                                onClick="deleteEntry()">
                        </div>
                    </div>


                    <!-- Diet Tracking Card -->
                    <div class="spacer" style="border: 1px solid black;"> </div>
                    <div class="card-header">
                        <h5 class="card-title">Diet Tracking</h5>
                    </div>
                    <div class="card-body">
                        <div class="scroll">
                            <!-- Display diet tracking information here -->
                            <?php
                            $query = "SELECT * FROM diet WHERE zim = ? ORDER BY dates DESC";
                            $result = $user->getDatabase()->runParameterizedQuery($query, "i", array($animal['zim']));

                            $r = 201;
                            $g = 215;
                            $b = 200;

                            //add color coding. if difference is 35% of total red, <35% & >65% yellow, >65% Green
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
                                echo '<p> &rarr; |[<a href="displayate.php?zim=' . $animal['zim'] . '&did=' . $row['did'] . '">' . $row['dates'] . '</a> ]|[ D. Entry ID ' . $row['did'] . ']|[ ' . $quantitygiven . ' ' . $row['units'] . ' of ' . $row['food'] . ']|[ ' . $percent . '% Consumed ]</tr><br>';
                                echo '</div>';
                            }

                            ?>
                        </div>
                    </div>

                    <div class="card-footer text-center">
                        <div class="btn-group">
                            <form method="POST" action="mealRecord.php?id=<?php echo $animal['zim']; ?>">
                                <input type="submit" value="Animal Ate Today" class="btn btn-success">
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
                        <h5 class="card-title"> All Time Welfare Submissions Graph
                            <?php for ($i = 0; $i < 40; ++$i)
                                echo "&nbsp"; ?>

                            <form method="POST" action="compare.php?zim=<?php echo $animal['zim']; ?>">
                                <input type="submit" value="Compare" class="btn btn-success">
                            </form>
                        </h5>

                    </div>
                    <div class="card-body">
                        <!-- Content for the graph card -->
                        <canvas id="wellnessChart" style="width:100%;max-width:700px"></canvas>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
                        <script>

                            const xValues = [];
                            const yValues = [];

                            <?php
                            // <NOTE>: This file needs to be updated using the Animal class functions.
                            //          It originally used getData but then stopped halfway through implementation.
                            // use db\Animal;

                            require_once "../db/Animal.php";

                            $animalO = new Animal($zim, $user->getDatabase());
                            $averages = $animalO->getAllCheckupAverages();
                            unset($animalO);
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
                        <h5 class="card-title"> All Time Average By Section</h5>
                    </div>
                    <!--Start of Polar Chart-->
                    <div class="card-body" style="width:90%;max-width:600px">
                        <!--Initializing Variables-->
                        <?php
                        $query = "SELECT AVG(`avg_health`), AVG(`avg_nutrition`), AVG(`avg_pse`), AVG(`avg_behavior`), AVG(`avg_mental`) FROM `welfaresubmission` WHERE `zim` = ?";
                        $result = $user->getDatabase()->runParameterizedQuery($sql, "i", array($animal['zim']));
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

        <!--Diet tracker-->
        <div class="col-12 col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"> Diet Tracker Display</h5>
                </div>
                <!--Start of bar Chart-->
                <div class="card-body" style="width:90%;max-width:600px">
                    <!--Initializing Variables-->
                    <?php
                    $labels = array();
                    $data = array();
                    $line = array();

                    $query = 'SELECT DISTINCT `food` FROM `diet` WHERE zim = ?'; //returns search values for food
                    $result = $database->runParameterizedQuery($query, "i", array($animal['zim']));
                    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                        $a = array(); // array to push averages to data (eaten)
                        $a2 = array(); //array to push averages to data (given)
                        $var = $row['food'];

                        array_push($labels, $var); //push to labels
                    
                        $query2 = "SELECT * FROM `diet` WHERE `food` = '" . $var . "'"; //select all entries where food = var
                        $result2 = $database->runQuery_UNSAFE($query2);
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
                    <canvas id="diet" style="width:100%;max-width:700px"></canvas>
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
        <!--End of bar chart-->


    </main>

    <hr>

</body>

</html>
<?php include_once "footer.php"; ?>