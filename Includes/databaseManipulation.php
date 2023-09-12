<?php
// Designed to be a replacement for the "DatabaseConnection.php" file.
// In order to use it, see databaseManipTutorial.txt that will be located in the same directory as this file.


class databaseManipulation{
    // Makes heavy use of this
    // https://www.php.net/manual/en/pdo.prepared-statements.php

    private $internaldbConnection;

    public function __construct(){
        $databaseIP = "localhost";
        $dbusername = "restricted_user";
        $dbpassword = "j60oPoObT3PSnEvZ";
        $dbName = "zooDB";
        $debug = 1;
        
        if($debug == 1){
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        }

        if(session_status() != 2) // If session has not started yet, Start it.
            session_start();

        // Create connection
        $this->internaldbConnection = mysqli_connect($databaseIP, $dbusername, $dbpassword, $dbName);

        // Check connection
        if ($this->internaldbConnection->connect_error) {
            die("Connection failed: " . $this->internaldbConnection->connect_error);
        }
    }

    /*
    paramStr needs to follow the same rules as the bind function requires.
    https://www.php.net/manual/en/mysqli-stmt.bind-param.php Scroll down to the "types" section.
    For instance if I was passing in an integer first, then two strings after I would pass in "iss" for $paramStr.

    The values stored in $valueArr will replace the '?' in the query that are in the $preparedStatement variable
    */
    public function runParameterizedQuery($preparedStatement, $paramStr, $valueArr){
        try{
            $sizeOfValueArr = count($valueArr);

                // Run the string through the prepared statement function
            $statement = $this->internaldbConnection->prepare($preparedStatement);

                // Bind the parameters
            for($i = 0; $i < $sizeOfValueArr; $i++){
                $statement->bind_param($paramStr, ...$valueArr);
            }

                // Execute them
            $statement->execute();

        } catch(Exception $e){
            echo 'runParameterizedQuery - Caught exception : ', $e->getMessage(), "\n";
        }
    }

    /*
    DO NOT USE IF THE QUERY YOU RUN INVOLVES USER INPUT.
    Returns the result of the mysqli_query function call.
    */
    public function runQuery_UNSAFE($query){
        return mysqli_query($this->internaldbConnection, $query);
    }

    /*
    Not recommended that you use this, but its here if needed.
    */
    public function getDatabaseConnection(){
        return $this->internaldbConnection;
    }
}
?>