<?php 
// namespace auth;

// use \config\ConfigHandler;

class DatabaseManager {
    private $connection;
    private $ip;
    private $username;
    private $password; 
    private $name;
    
    public function __construct(){
        $config = $this->getConfig();

        $this->ip = $config['Database']['databaseIP'];
        $this->username = $config['Database']['databaseUsername'];
        $this->password = $config['Database']['databasePassword'];
        $this->name = $config['Database']['databaseName'];

        if(($debug = $config['DEBUG']['debugMode']) == 1){
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        }

        if(session_status() != 2){
            session_start();
        }

        $this->connection = new mysqli($this->ip, $this->username, $this->password, $this->name);

        if($this->connection->connect_error){
            die("Connection falied: ". $this->connection->connect_error);
        }
    }

    public function runParameterizedQuery($preparedQuery, $paramStr, $valueArr){
        try{
            $size = count($valueArr);
            $statement = $this->connection->prepare($preparedQuery);
            for($i = 0; $i < $size; $i++){
                $statement->bind_param($paramStr, ...$valueArr);
            }
            $statement->execute();

            return $statement->get_result();
        } catch(Exception $e){ 
            echo "runParameterizedQuery". $e->getMessage(), "\n";
        }
    }

    public function runQuery_UNSAFE($query){
        return $this->connection->query($query);
    }

    public function getConnection(){
        return $this->connection;
    }

    private function getConfig(){
        require_once "../config/ConfigHandler.php";
        $config = new ConfigHandler;
        return $config->readConfigFile();
    }

    public function getConnectionDetails(){
        return array($this->ip, $this->username, $this->password, $this->name);
    }
}