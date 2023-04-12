<!DOCTYPE html>
<html>
    <!--Include CSS-->
    <head>
        <link rel="stylesheet" href="CSS/public.css">
    </head>
    <!--Put image at the top and link it to home-->
    <body>
        <a href="home.php">
            <img src=Images/salisburyZooLogo.png alt="Logo">
        </a>
        
        <!--Included so leaves shows up at top of page-->
        <header><h1></h1></header>
        <hr> <!--hr: horizontal line-->
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li> <!--possible removal-->
                <li><a href="edit.php">Welfare Forms</a></li>
                <li><a href="diet.php">Diet Tracker</a></li>
                <li><a href="search.php">Search</a></li>
                <li><a href="logoutHandler.php">Logout</a></li>
            </ul>
        </nav>
        <hr>
        <main>
            <?=$output?>
        </main>
    <hr>
    <footer>
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
