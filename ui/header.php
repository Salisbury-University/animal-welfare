<?php
// require_once "../vendor/autoload.php";

// use admin\SessionUser;
require_once "../admin/SessionUser.php";

SessionUser::sessionStatus();

    // Prevent unauthorized users from using the application
if(isset($_SESSION['user']) == false){
    die();
}

$user = unserialize($_SESSION['user']);
$user->openDatabase();

##Initializes forms variable
$query = "SELECT * FROM `forms`;";
$forms = $user->getDatabase()->runQuery_UNSAFE($query);
?>

<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Dashboard</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Main styles -->
    <link href="../style/main.css" rel="stylesheet">

    <!--Boostrap javascript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js"
        integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT"
        crossorigin="anonymous"></script>

</head>

<body>
    <!--Header-->
    <header></header>

    <!--Navigation Bar-->
    <hr>
    <nav class="navbar navbar-expand-md my-light">

        <!--Logo-->
        <div class="logo-overlay">
            <a href="home.php">
                <img src=../img/Header/logo.png alt="Logo">
            </a>
        </div>

        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav mr-auto">
                <!--Home-->
                <li class="nav-item">
                    <a class="nav-link my-text-info" href="home.php">Home</a>
                </li>

                <!--Search Page-->
                <li class="nav-item">
                    <a class="nav-link my-text-info" href="search.php">Search</a>
                </li>

                <!--Start Admin Only-->
                <?php
                if ($user->checkIsAdmin() == 1) { ?>
                    <!--Welfare Forms-->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle my-text-info" href="#" id="navbarDropdownMenuLink"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Edit Forms
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <?php while (($form = $forms->fetch_array(MYSQLI_ASSOC)) != NULL) { ?>
                                <form method="POST" action="editForm.php?id=<?php echo $form['id']; ?>">
                                    <button type="submit" class="dropdown-item btn btn-secondary">
                                        <?php echo $form["title"]; ?>
                                    </button>
                                </form>
                            <?php } ?>
                        </div>
                    </li>


                    <!--Dropdown menu-->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle my-text-info" href="#" id="navbarDropdownMenuLink"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Admin
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="admin.php">Manage admin</a>
                            <a class="dropdown-item" href="createUser.php">Create User</a>
                            <a class="dropdown-item" href="exportData.php">Export Data</a>
                        </div>
                    </li>

                <?php } ?> <!--End admin only-->
            </ul>

            <a class='btn btn-success my-2 my-sm-0 float-left' href='header.php?logout=1' role='button'>Logout</a>

        </div>
    </nav>
    <hr>
    <!--End Navigation Bar-->

    <?php

    if (isset($_GET['logout'])) {
        $user->logout();
        header("Location: index.php");
        unset($user);
        exit();
    }
    // Call destructor for database variable.
//unset($database);
    ?>
