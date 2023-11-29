<?php
include "header.php";

// use admin\SessionUser;

require_once "../admin/SessionUser.php";
require_once "../db/Animal.php";
SessionUser::sessionStatus();

$user = unserialize($_SESSION['user']);
$user->openDatabase();


$temp = $_POST['selected'];
$arr = implode(',', $temp);
$zArr = explode(',', $arr);
echo $zArr[0];

$id = $_POST['id'];
echo $id;

for ($i = 0; $i < sizeof($zArr); ++$i){
    echo $zArr[$i];
}
?>

<!doctype html>
    <!-- Graph Card -->
    <div class="row">
            <div class="col-12 col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"> Comparing All Time Welfare Averages
                        </h5>

                    </div>
                    <div class="card-body">
                        <!-- Content for the graph card -->
                        <canvas id="wellnessChart" style="width:100%;max-width:700px"></canvas>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
                        <script>
                            const dataset = [];
                            const xValues = [];
                            const yValues = [];
                            //
                            <?php 
                                //create an array of animal objects 
                                $animals = [];
                                for($i = 0; $i < sizeof($zArr); ++$i){
                                    $animal = new Animal($zArr[$i], $user);
                                    $animals[$i] = $animal;
                                }
                                //now get the averages of each animal
                                $averages = [];
                                for($i = 0; $i < sizeof($zArr); ++$i){
                                    $average = $animals[$i]->getAllCheckUpAverages();
                                    $averages[$i] = $average;
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
                                        data: {
                                            <?php 
                                            echo "
                                            labels: ";
                                            echo "datasets: [ ";
                                            for($i = 0; $i < sizeof($zArr); ++$i){
                                                echo "
                                                    {
                                                        label: '". $animals[$i]->getName() ."',
                                                        fill: false,
                                                        backgroundColor: 'rgba(0,0,255,1.0)',
                                                        borderColor: 'rgba(0,0,255,1.0)',
                                                        data: 
                                                    } 
                                                    ";

                                                    if($i + 1 != sizeof($zArr))
                                                        echo ",";
                                                }
                                                echo "]";
                                            ?>
                                        }
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
</html>
