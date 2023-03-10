<?php
class Database {

    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $conn;

    public function __construct($server, $usr, $pass, $db) {
        $this->servername = $server;
        $this->username = $usr;
        $this->password = $pass;
        $this->dbname = $db;
        $this->makeconn();
    }

    private function makeconn() {
        $this->conn = mysqli_connect($this->servername, $this->username, $this->password, $this->dbname);

        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        echo "Connected successfully";
    }

    private function closeconn() {
        mysqli_close($this->conn);
        if (!$this->conn) {
            echo "Connection Closed";
        }
    }

    public function disconnect(){
            $this->closeconn();
    }

    public function update() {
        // TODO: implement update method
    }

    public function insert($table) {
        // TODO: construct insert query dynamically based on table schema
    }
}
?>
