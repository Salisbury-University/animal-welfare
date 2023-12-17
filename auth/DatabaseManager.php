<?php 
// namespace auth;

// use \config\ConfigHandler;

class DatabaseManager {
    private $internaldbConnection;
    private $databaseIP;
    private $dbUsername;
    private $dbPassword; 
    private $dbName;
    
    public function __construct(){
        $config = $this->getConfig();

        $this->databaseIP = $config['Database']['databaseIP'];
        $this->dbUsername = $config['Database']['databaseUsername'];
        $this->dbPassword = $config['Database']['databasePassword'];
        $this->dbName = $config['Database']['databaseName'];

        if(($debug = $config['DEBUG']['debugMode']) == 1){
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        }

            // If a session has not started, start it.
        if(session_status() != 2){
            session_start();
        }

        $this->internaldbConnection = new mysqli($this->databaseIP, $this->dbUsername, $this->dbPassword, $this->dbName);

        if($this->internaldbConnection->connect_error){
            die("Connection falied: ". $this->internaldbConnection->connect_error);
        }
    }

    /*
    paramStr needs to follow the same rules as the bind function requires.
    https://www.php.net/manual/en/mysqli-stmt.bind-param.php Scroll down to the "types" section.
    For instance if I was passing in an integer first, then two strings after I would pass in "iss" for $paramStr.

    The values stored in $valueArr will replace the '?' in the query that are in the $preparedStatement variable
    */
    public function runParameterizedQuery($preparedQuery, $paramStr, $valueArr){
        try{
            $sizeOfValueArr = count($valueArr);
            
                // Run the string through the prepared statement function
            $statement = $this->internaldbConnection->prepare($preparedQuery);
            
                // Bind the parameters
            for($i = 0; $i < $sizeOfValueArr; $i++){
                $statement->bind_param($paramStr, ...$valueArr);
            }

                // Execute them
            $statement->execute();

            return $statement->get_result();

        } catch(Exception $e){ 
            echo "runParameterizedQuery". $e->getMessage(), "\n";
        }
    }

    /*
    DO NOT USE IF THE QUERY YOU RUN INVOLVES USER INPUT TEXT.
    Returns the result of the mysqli_query function call.
    */
    public function runQuery_UNSAFE($query){
        return $this->internaldbConnection->query($query);
    }

    /*
    Not recommended that you use this, but its here if needed.
    */
    public function getConnection(){
        return $this->internaldbConnection;
    }

    private function getConfig(){
        require_once "../config/ConfigHandler.php";
        $config = new ConfigHandler;
        
        return $config->readConfigFile();
    }

    /*
    Returns an array containing all the database connection info if needed.
    The array is formatted; (DatabaseIP, Username, Password, Database name)
    */
    public function getConnectionDetails(){
        return array($this->databaseIP, $this->dbUsername, $this->dbPassword, $this->dbName);
    }
}