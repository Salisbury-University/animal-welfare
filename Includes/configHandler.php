<?php
class configHandler{

    private $defaultConfigFile = '[Database]
databaseIP = "localhost"
databaseUsername = "user"
databasePassword = "password"
databaseName = "database name"
';

    // These are used for calculating the absolute path to a given file
    private $configLocation = "/Config/masterConfig.ini";
    private $configDirectory = "/Config/";

    // The values of these two variables are generated, Do not change these values.
    private $absoluteConfigLocation = "";
    private $absoluteConfigDirectory = "";

    public function __construct(){}

    private function calcAbsoluteDir(){
        $this->absoluteConfigDirectory = $this->movePathUpOneDir(__FILE__);
        $this->absoluteConfigDirectory = $this->movePathUpOneDir($this->absoluteConfigDirectory);
        $this->absoluteConfigDirectory = $this->absoluteConfigDirectory . $this->configDirectory;

        $this->absoluteConfigLocation = $this->absoluteConfigDirectory . 'masterConfig.ini';
    }

    private function recreateConfig(){
        $tmp = copy($this->absoluteConfigDirectory . 'masterConfig.ini', $this->absoluteConfigDirectory . 'masterConfig.ini.backup');
        
        if($tmp == true){
            unlink($this->absoluteConfigDirectory . 'masterConfig.ini');
        }

        $file = fopen($this->absoluteConfigLocation, 'w');
        fwrite($file, $this->defaultConfigFile);
        fclose($file);
        
            // Set the permissions to 777 and the owner to whoever owns the folder.
            // REMOVE LATER
            // This is needed because of our servers current setup.
        chmod($this->absoluteConfigLocation, 0777);
        //$fileOwner = fileOwner($this->absoluteConfigDirectory);
        //chown($this->absoluteConfigLocation, $fileOwner);
    }

    private function movePathUpOneDir($path){
        $tmp = 'a';
        while($tmp !== '/'){
            $len = strlen($path);
            $tmp = $path[$len - 1];
            $path = substr_replace($path, "", -1);
        }

        return $path;
    }

    public function readConfigFile(){
        $this->calcAbsoluteDir();

        $ini = parse_ini_file($this->absoluteConfigLocation);

        if($ini == false){
            $this->recreateConfig();
            die('configHandler.php - Error - Malformed masterConfig.ini. Resetting to default.');
        }
    }

}

?>