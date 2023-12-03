<?php
// require_once "../vendor/autoload.php";
include_once "header.php";

// use admin\SessionUser;
require_once "../admin/SessionUser.php";

SessionUser::sessionStatus();

$user = unserialize($_SESSION['user']);

if ($user->checkIsAdmin() == false) {
    header('Location: home.php');
}

$user->openDatabase();

$database = $user->getDatabase();
?>

<link href="../style/admin.css" rel="stylesheet">

<style>
    .container{
        margin-top: 2%;
        margin-left: 5%;
        max-width: 45%;
        margin: auto;
        background-color: white;
        border: solid black 3px;
    }

    .paddingContainer{
        padding: 30px;
    }
</style>

<!--Only edit main-->
<main><!-- Main jumbotron for a primary marketing message or call to action -->
    <?php
        /*
        https://stackoverflow.com/questions/40164797/how-do-i-send-all-selected-data-from-the-drop-down-menu-to-the-server-with-a-po
        https://stackoverflow.com/questions/36313844/posting-the-value-of-a-drop-down-menu-to-a-php-page-via-a-html-form

        */
            // Get a list of tables so that we can include an entry in the drop down menu for each one.
        $tables = $database->runQuery_UNSAFE("SHOW TABLES;");
        $tableNameArr = array();
        for($i = 0; $i < $tables->num_rows; $i++){
            $tableNameArr[$i] = $tables->fetch_row();
        }


            // Echo the actual form that will send a post request to the script Admin/_export_data.php
        /*echo "<form method='post'>";
        echo "<label for='animals'> Choose a:</label>";
        echo "<select id='animals' name='animals'>";
        echo "<form id='exportData' name='exportData'>";
        echo "<input type='submit'>";*/
        //https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/checkbox

            // Iterate through array of tables and create a checkbox for each
        echo "<div class='paddingContainer'>";
        echo "<div class='container'>";
        echo "Check the box of the tables you wish to export.<br>";
        echo "<form id='exportData' name='exportData' action='../admin/_export_data.php' method='POST'>";
        foreach($tableNameArr as $val){
            $val = $val[0]; // The foreach loop makes $val an array of length 1 for some reason.

            //echo "Outputing table for $val<br>";
            echo "<input type='checkbox' id='$val' name='$val' value='$val'";
            echo "<label for='$val'> $val </label><br>";
        }
        echo "<input type='submit'>";
        echo "</form>";
        echo "</div>";
        echo "</div>";

        //echo "<input type='checkbox' id='vehicle1' name='vehicle1' value='Bike'>"
        //echo "<label for='vehicle1'> I have a bike</label><br>"
        
    ?>
</main>

<?php
include_once "footer.php";
?>