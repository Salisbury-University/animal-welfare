<!DOCTYPE html>
<html>

<head>
    <!--<link rel="stylesheet" href="public.css"> -->
    <link rel="stylesheet" type="text/css" href="search.css">
</head>

<body>
    <a href="home.php">
        <img src=Images/salisburyZooLogo.png alt="Logo">
    </a>
    <header>
        <h1></h1>
    </header>
    <nav>
        <ul>
            <li><a href="home.php">Home</a></li> <!--possible removal-->
            <li><a href="edit.php">Welfare Forms</a></li>
            <li><a href="diet.php">Diet Tracker</a></li>
            <li><a href="search_page.php">Search</a></li>
        </ul>
    </nav>
    <?= $output ?>
    <footer>   <!-- TODO: Update links for each page as they are made -->
        <div class="f-top">
            <div class="row">
                <div class="col">
                    <h4>welfares</h4>
                    <ul>
                        <li><a href="#">animals</a></li>
                        <li><a href="#">species</a></li>
                        <li><a href="#">sections</a></li>
                        <li><a href="#">checkups</a></li>
                        <li><a href="#">forms</a></li>
                    </ul>
                    <!-- <h4>account</h4> -->

                </div>
                <div class="col">
                    <h4>diets</h4>
                    <ul>
                        <li><a href="#">coming soon</a></li>
                        <!-- <li><a href=''></li> -->
                    </ul>
                </div>
                <div class="col">
                    <h4>data</h4>
                    <ul>
                        <li><a href="#">compare animals</a></li>
                        <li><a href="#">view all</a></li>
                        <li><a href="#">export data</a></li>
                        <!-- <li><a href="#">interactive map</a></li> -->
                        <!-- <li><a href=''></li> -->
                    </ul>
                </div>
                <div class="col">
                    <h4>help</h4>
                    <ul>
                        <li><a href="#">coming soon</a></li>
                        <!-- <li><a href=''></li> -->
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>