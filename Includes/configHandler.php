<?php
class configHandler{

    // These are used for calculating the absolute path to a given file
    private $configLocation = "/Config/masterConfig.ini";
    private $configDirectory = "/Config/";

    // The values of these two variables are generated, Do not change these values.
    private $absoluteConfigLocation = "";
    private $absoluteConfigDirectory = "";

        // The contents of defaultConfig.php are dumped into this variable in the constructor
    private $defaultConfigFile = "";

    public function __construct(){
            // Pull in the defaultConfig file in from another php file
        include "defaultConfig.php";
        $this->defaultConfigFile = $defaultConfigFile;
    }

    /*
        A lot of the PHP functions in this library do 
        not use relative file paths and instead only use absolute paths.
        
        This function will calculate the absolute directory of the config files from
        where this script is located.
    */
    private function calcAbsoluteDir(){
        $this->absoluteConfigDirectory = $this->movePathUpOneDir(__FILE__);
        $this->absoluteConfigDirectory = $this->movePathUpOneDir($this->absoluteConfigDirectory);
        $this->absoluteConfigDirectory = $this->absoluteConfigDirectory . $this->configDirectory;

        $this->absoluteConfigLocation = $this->absoluteConfigDirectory . 'masterConfig.ini';
    }

    /*
        Recreates the config if its missing or deleted.
        Also creates a backup of the previous config incase something happened in error.

        Not a perfect solution since the backup will be overwritten if it exists, so this will need
        to be fleshed out more in the future.

        TODO: Make a better system for backing up the files.
        TODO: Remove the code that sets the permissions to 0777 when development is done, this is bad practice.
    */
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

    /*
        Removes all the characters before /
        Simulates "moving up" a directory when given a file path.
    */
    private function movePathUpOneDir($path){
        $tmp = 'a';
        while($tmp !== '/'){
            $len = strlen($path);
            $tmp = $path[$len - 1];
            $path = substr_replace($path, "", -1);
        }

        return $path;
    }

    /*
    Reads the config file masterConfig.ini
    */
    public function readConfigFile(){
        $this->calcAbsoluteDir();

        $ini = parse_ini_file($this->absoluteConfigLocation);

        if($ini == false){
            $this->recreateConfig();
            die('configHandler.php - Error - Malformed masterConfig.ini. Resetting to default. Please configure Config/masterConfig.ini and reload the webpage.');
        }

        return $ini;
    }

    /*
        Disables the recovery account. Called in loginHandler.php after the user was authenticated
        with the credentials for this user account.

        TODO: Make a function that handles all writing to the config file so theres a universal 
        solution that everyone can use.
    */
    public function disableDefaultAccountFlag(){
        $configContents = file_get_contents($this->absoluteConfigLocation);
        $tmp = str_replace("recoveryAccountEnabled = 1", "recoveryAccountEnabled = 0", $configContents);
        file_put_contents($this->absoluteConfigLocation, $tmp);
    }

}

?>