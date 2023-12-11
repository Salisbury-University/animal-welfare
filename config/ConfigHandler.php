<?php 
// namespace config;
class ConfigHandler{
    /*
        These two variables are used to get the absolute path of the config file relative
        to this script.
    */
    private $configName = "/master_config.ini";

        // ABSOLUTE PATHS, DO NOT EDIT. 
        // THEY ARE AUTOGENERATED BY calcAbsoluteDir()
    private $absoluteConfigLocation = "";
    private $absoluteConfigDirectory = "";

        // The information in this variable is read in from the file "_default_config.php"
    private $defaultConfigFile = "";

    public function __construct(){
        include "_default_config.php";
        $this->defaultConfigFile = $defaultConfigFile;
    }


    /*
    This class relies heavily on system calls which need absolute paths.
    This function is responsible for getting the location of the config file relative to the location
    of this php script.
    */
    private function calcAbsoluteDir(){
        $this->absoluteConfigDirectory = dirname(__FILE__, 1);
        $this->absoluteConfigLocation = $this->absoluteConfigDirectory . $this->configName;
    }

    /*
    Deletes master_config.ini inside of the config directory and replaces it with the default config file
    defined in "_default_config.php".
    */
    private function recreateConfig(){
        $tmp = copy($this->absoluteConfigDirectory . "master_config.ini", $this->absoluteConfigDirectory . "master_config.ini.backup");
        
        if($tmp == true){
            unlink($this->absoluteConfigDirectory . "master_config.ini");
        } else {
            die("<br> ConfigHandler.php - Unable to backup config: Does the web server have permission for master_config.ini? <br>");
        }

        $file = fopen($this->absoluteConfigLocation, 'w');

        if($file == false){
            die("<br> ConfigHandler.php - Failed to create file: Does the web server have the permission for master_config.ini? <br>");
        }

        fwrite($file, $this->defaultConfigFile);
        fclose($file);
    }

    /*
    Parses the config file and returns an array of values.
    See https://www.php.net/manual/en/function.parse-ini-file.php for return values.
    */
    public function readConfigFile(){
            // Get location of the ini file located on the system
        $this->calcAbsoluteDir();

        $ini = parse_ini_file($this->absoluteConfigLocation, true);

            // Recreate config if there was an error, or the file does not exist
        if($ini == false){
            $this->recreateConfig();
            die("<br> ConfigHandler.php - Error: Malformed master_config.ini. Resetting to default. Please configure config/master_config.ini and reload the webpage.");
        }
        
        return $ini;
    }

    /*
    Simple function for writing to the config file. 
    Could be rewritten to be better, but it does reliably work.
    */
    public function writeToConfigFile($attribute, $newValue, $currentValue){
            // Read the whole config into a variable we can manipulate.
        $configContents = file_get_contents($this->absoluteConfigLocation);

            // Replace the data in the config file with the data that we want to replace
        $tmp = str_replace($attribute . "=" . $currentValue, $attribute . " = " . $newValue, $configContents);  
        
            // Write the data to the file
        file_put_contents($this->absoluteConfigLocation, $tmp);
    }
}
