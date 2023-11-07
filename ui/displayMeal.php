<?php
// require_once "../vendor/autoload.php";
include_once "header.php";

// use admin\SessionUser;

require_once "../admin/SessionUser.php";
SessionUser::sessionStatus();

$user = unserialize($_SESSION['user']);
$user->openDatabase();

$zim = $_GET['zim'];
$did = $_GET['did'];

$query = "SELECT * FROM diet WHERE did = ?";
$result = $user->getDatabase()->runParameterizedQuery($query, "i", array($did));
$row = $result->fetch_array(MYSQLI_ASSOC);

$food = $row['food'];
$reason = $row['reason'];
$date = $row['dates'];
$quantitygiven = $row['quantitygiven'];
$quantityate = $row['quantityeaten'];
$difference = $row['difference'];
$units = $row['units'];
$percent = ($quantityate / $quantitygiven) * 100;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta tags and CSS links -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <link href="CSS/display.css" rel="stylesheet">
    <style>
        /* Custom CSS for centering the card */
        .center-card {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
    </style>
</head>

<body>
    <form method="POST" action="animalProfile.php?id=<?php echo $zims; ?>">
        <button type="submit" class="btn btn-sm btn-success">Back</button>
    </form>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="center-card">
                    <div class="col-12 col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Diet Entry #
                                    <?php echo $did ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <!-- Display animal information here -->

                                    <li class="list-group-item"><strong>ZIM:</strong>
                                        <?php echo $zims; ?>
                                    </li>
                                    <li class="list-group-item"><strong>Diet Entry ID:</strong>
                                        <?php echo $did; ?>
                                    </li>
                                    <li class="list-group-item"><strong>Date:</strong>
                                        <?php echo $date; ?>
                                    </li>
                                    <li class="list-group-item"><strong>Food Type:</strong>
                                        <?php echo $food; ?>
                                    </li>
                                    <li class="list-group-item"><strong>Amount Given:</strong>
                                        <?php echo $quantitygiven . " " . $units; ?>
                                    </li>
                                    <li class="list-group-item"><strong>Amount Consumed:</strong>
                                        <?php echo $quantityate . " " . $units; ?>
                                    </li>
                                    <li class="list-group-item"><strong>Left Over:</strong>
                                        <?php echo $difference . " " . $units; ?>
                                    </li>
                                    <li class="list-group-item"><strong>Percent Eaten:</strong>
                                        <?php echo $percent . "%"; ?>
                                    </li>
                                    <!-- ... (other animal info) ... -->
                                </ul>
                                <!--Display data -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>

<?php
include_once "footer.php";
?>