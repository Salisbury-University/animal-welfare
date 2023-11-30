<?php
    // Back end script for exporting the data in the database to a .csv file
require_once "../admin/SessionUser.php";
require_once "../auth/DatabaseManager.php";

SessionUser::sessionStatus();
$user = unserialize($_SESSION['user']);

    // CONSTANTS (that you can change later if needed)
$CSVSAVELOCATION = sys_get_temp_dir() . "/" . "export.csv";
$AMOUNTOFBLANKLINES = 5; // Number of blank lines between each table in the exported file.

    // Database stuff
$user->openDatabase();
$database = $user->getDatabase();


    // Variables that are used later
$dataToBeWrittenToCSVFile = array();
$tableNameExport = array(); // Used to label each table in the exported csv file
$blankRowSeparator = array(" "); // Used to separate the different database tables in the file for easier readability.

    // Get data from table each of the tables and add them to the 
    // array that will be written as a csv file.
$tables = $database->runQuery_UNSAFE("SHOW TABLES;");
for($i = 0; $i < $tables->num_rows; $i++){
    $tableNames[$i] = $tables->fetch_row();
}


    //https://www.phptutorial.net/php-tutorial/php-checkbox/
$count = 0;
for($i = 0; $i < sizeof($tableNames); $i++){
    if(filter_has_var(INPUT_POST, $tableNames[$i][0]) == true){
        $tableNameExport[$count] = $tableNames[$i];
        $count++;
    }
    
    /*if(isset($_POST[$tableNames[$i]]) == true){
        $tableNameExport[$count] = $tableNames[$i];
        $count++;
    }*/
}

    // Get the data from the database with the associated tables
for($i = 0; $i < sizeof($tableNameExport); $i++){
    $temp = $database->runQuery_UNSAFE("SELECT * FROM " . $tableNameExport[$i][0]);
    $dataToBeWrittenToCSVFile[$i] = $temp->fetch_all();
}


/*for($i = 0; $i < $tables->num_rows; $i++){
    $tableName = $tables->fetch_row();
    $tableNameExport[$i] = $tableName;

        // Get data from table
    $temp = $database->runQuery_UNSAFE("SELECT * FROM " . $tableName[0]);
    $stuffInTable = $temp->fetch_all();
    $dataToBeWrittenToCSVFile[$i] = $stuffInTable;
}*/


    // Write CSV to file
$fp = fopen($CSVSAVELOCATION, 'w'); //fopen($csvSaveLocation, 'w');
if($fp != false){
    for($i = 0; $i < count($dataToBeWrittenToCSVFile); $i++){
            // Write the table name down so they know what data is stored there
        fputcsv($fp, $tableNameExport[$i]);

            // Write the actual data to the file, row by row
        for($j = 0; $j < count($dataToBeWrittenToCSVFile[$i]); $j++){
            fputcsv($fp, $dataToBeWrittenToCSVFile[$i][$j]);
        }

            // Write a few blank rows to the file
        for($k = 0; $k < $AMOUNTOFBLANKLINES; $k++)
            fputcsv($fp, $blankRowSeparator);
    }

        // Send the file to the browser
    header("Content-disposition: attachment;filename=export.csv");
    readfile($CSVSAVELOCATION);
    
    fclose($fp);
}else{
    echo "fopen() returned value: " . $fp . "<br>"; 
    exit();
}


session_write_close();
exit();
?>