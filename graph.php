<?php
include "header.php";

// use admin\SessionUser;
//NOTE ::must connect to site

require_once "../admin/SessionUser.php";
require_once "../auth/DatabaseManager.php";
require_once "../db/Animal.php";
SessionUser::sessionStatus();

$user = unserialize($_SESSION['user']);
$user->openDatabase();


$temp = $_POST['selected'];
$arr = implode(',', $temp);
$zArr = explode(',', $arr);
$id = $_POST['id'];
?>

<!doctype html>
<body>
<form method="POST" action="compareHandler.php?zim=<?php echo $id; ?>">
        <button type="submit" class="btn btn-sm btn-success">Back</button>
</form>
</body>

<div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="center-card">

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Select Animal(s)</h5>
                                </div>
                                <div class="card-body">
                                <?php   // Code responsible for the multi-line graph
                                        //create an array of animal objects 
                                        $animals = [];
                                        $checkupDate = array();
                                        $checkup = array();
                                        for ($i = 0; $i < sizeof($zArr); $i++) {
                                            $animal = new Animal($zArr[$i], $user);
                                            $animals[$i] = $animal;

                                            $checkupData = $animal->getAllCheckupAverages();
                                            unset($animal);

                                            $checkup[$i] = array();
                                            $jitr = 0;
                                            foreach ($checkupData as $date => $val) {
                                                array_push($checkupDate, $date);

                                                $checkup[$i][$jitr] = array();
                                                array_push($checkup[$i][$jitr], $date, $val);
                                                $jitr++;
                                            }
                                        }
                                        for ($i = 0; $i < sizeof($zArr); $i++) {

                                            $y = [];
                                            for ($j = 0; $j < sizeof($checkupDate); $j++) {
                                                $null = null;
                                                array_push($y, $null);
                                            }

                                            for ($j = 0; $j < sizeof($checkup[$i]); $j++) {
                                                $num = null;
                                                if ($num = array_search($checkup[$i][$j][0], $checkupDate)) {
                                                    $y[$num] = $checkup[$i][$j][1];
                                                }
                                            }
                                            $data[$i] = $y;
                                        }
                                        ?>
                                        <!-- Content for the graph card -->
                                        <canvas id="wellnessChart" style="width:100%;"></canvas>
                                        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
                                        <script>
                                            const wellnessChart = new Chart("wellnessChart", {
                                                type: "line",
                                                data: {
                                                    labels: <?= json_encode($checkupDate) ?>,
                                                    datasets: [
                                                        <?php 
                                                            for ($i = 0, $lastrgb = 0; $i < sizeof($zArr); ++$i): ?>
                                                                    {
                                                                <?php
                                                                $r=0; $g=0; $b=0;
                                                                
                                                                $r = rand(0, 255);
                                                                $b = rand(0, 255);
                                                                $ng = $lastrgb + (69 * (1/(sizeof($zArr)/2)));
                                                                ($i == 0) ? $g = rand(0, 255) : ($ng > 255)? $g = $ng - 255 : $g = $ng;

                                                                $lastrgb = $g;
                                                                $a = "rgb($r, $g, $b)";
                                                                ?>
                                                                
                                                                label: '<?= $animals[$i]->getName() ?>',
                                                                fill: false,
                                                                backgroundColor: <?= json_encode($a) ?>,
                                                                spanGaps: true,
                                                                borderColor: '<?= json_encode($a) ?>',
                                                                data: <?= json_encode($data[$i]) ?>
                                                            }
                                                                    <?php if ($i + 1 != sizeof($zArr)): ?>
                                                                ,
                                                            <?php endif; ?>
                                            <?php endfor; ?>
                                                    ]
                                                },
                                                options: {
                                                    legend: { display: false },
                                                    scales: {
                                                        y: [{
                                                            ticks: {
                                                                min: 0,
                                                                max: 5,
                                                                stepSize: 0.5,
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <?php /* THIS IS TO BE IMPLEMENTED SOON WILL SHOW STATS OF ANIMALS 
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="center-card">
                            <div class="card" style="width: 950px">
                                <div class="card-header">
                                    <h5 class="card-title">Comparison</h5>
                                </div>
                                <div class="card-body">                                
                              <!-- create a table that displays all the stats compared to the orgin animal -->                     
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
             </div>
             */?>
</html>

