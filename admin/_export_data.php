<?php
// Back end script for exporting the data in the database to a .csv file
//https://www.php.net/manual/en/function.fputcsv.php
require_once "../admin/SessionUser.php";
require_once "../auth/DatabaseManager.php";

//$database = new DatabaseManager;

SessionUser::sessionStatus();
$user = unserialize($_SESSION['user']);

    // CONSTANTS (that you can change later if needed)
$CSVSAVELOCATION = sys_get_temp_dir() . "/" . "export.csv";
$AMOUNTOFBLANKLINES = 5; // Number of blank lines between each table in the exported file.

    // Database stuff
//require_once("Includes/databaseManipulation.php");
$user->openDatabase();
$database = $user->getDatabase(); //new databaseManipulation;


    // Variables that are used later
$dataToBeWrittenToCSVFile = array();
$tableNameExport = array(); // Used to label each table in the exported csv file
$blankRowSeparator = array(" "); // Used to separate the different database tables in the file for easier readability.

    // Get data from table each of the tables and add them to the 
    // array that will be written as a csv file.
$tables = $database->runQuery_UNSAFE("SHOW TABLES;");
for($i = 0; $i < $tables->num_rows; $i++){
    $tableName = $tables->fetch_row();
    $tableNameExport[$i] = $tableName;

        // Get data from table
    $temp = $database->runQuery_UNSAFE("SELECT * FROM " . $tableName[0]);
    $stuffInTable = $temp->fetch_all();
    $dataToBeWrittenToCSVFile[$i] = $stuffInTable;
}


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