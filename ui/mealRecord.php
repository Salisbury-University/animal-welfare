<?php
// require_once "../vendor/autoload.php";
include_once "header.php";
// use admin\SessionUser;

require_once "../admin/SessionUser.php";
SessionUser::sessionStatus();

$user = unserialize($_SESSION['user']);


$zim = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta tags and CSS links -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <link href="../style/main.css" rel="stylesheet">
    <link href="../style/display.css" rel="stylesheet">
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
    <form method="POST" action="animalProfile.php?id=<?php echo $zim; ?>">
        <button type="submit" class="btn btn-sm btn-success">Back</button>
    </form>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="center-card">
                    <div class="col-12 col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Diet Entry</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="food"
                                            placeholder="Enter the type of food">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="reason"
                                            placeholder="Enter the reason">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="quantityg" placeholder="Enter the quantity given">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="quantitye" placeholder="Enter the quantity eaten">
                                    </div>
                                    <p>Please select a unit</p>
                                    <div class="form-check">
                                        <input type="radio" id="unit" class="form-check-input" name="unit"
                                            value="pounds">
                                        <label class="form-check-label" for="unit1">pounds</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" id="unit" class="form-check-input" name="unit"
                                            value="grams">
                                        <label class="form-check-label" for="unit2">grams</label>
                                    </div>
                                    <div class="card-footer text-center">
                                        <button type="submit" name="sub" class="btn btn-primary mt-3">Submit</button>
                                </form>
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

if (isset($_POST['sub'])) {
    $unit = $_POST['unit'];
    $food = $_POST['food'];
    $reason = $_POST['reason'];
    $quantitygiv = $_POST['quantityg'];
    $quantityeat = $_POST['quantitye'];
    $date = date('Y-m-d');
    $difference = $quantitygiv-$quantityeat;

    echo $unit;
    echo $food;
    echo $reason;
    echo $quantitygiv;
    echo $quantityeat;
    echo $date;

    $user->openDatabase();

    $query = "INSERT INTO diet (zim, dates, reason, food, quantitygiven, quantityeaten, difference, units) VALUES (?,?,?,?,?,?,?,?)";
    $result = $user->getDatabase()->runParameterizedQuery($query, "isssisss", array($zim, $date, $reason, $food, $quantitygiv, $quantityeat, $difference, $unit));
    
}

include_once "footer.php";
?>