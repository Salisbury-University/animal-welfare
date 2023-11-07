<?php 
// namespace config;
// NOTE: REDOC NEEDED
class ConfigHandler{
    private $configLocation = "config/master_config.ini";
    private $configDirectory = "/config/";
    private $absoluteConfigLocation = "";
    private $absoluteConfigDirectory = "";
    private $defaultConfigFile = "";

    public function __construct(){
        include "_default_config.php";
        $this->defaultConfigFile = $defaultConfigFile;
    }

    private function calcAbsoluteDir(){
        $this->absoluteConfigDirectory = dirname(__FILE__,1);
        // $this->absoluteConfigDirectory .= $this->configDirectory;
        $this->absoluteConfigLocation = $this->absoluteConfigDirectory . "/master_config.ini";
    }

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

        chmod($this->absoluteConfigLocation, 0777);
        //$fileOwner = fileOwner($this->absoluteConfigDirectory);
        //chown($this->absoluteConfigLocation, $fileOwner);
    }

    public function readConfigFile(){
        $this->calcAbsoluteDir();

        $ini = parse_ini_file($this->absoluteConfigLocation, true);

        if($ini == false){
            $this->recreateConfig();
            die("<br> ConfigHandler.php - Error: Malformed master_config.ini. Resetting to default. Please configure config/master_config.ini and reload the webpage.");
        }
        return $ini;
    }

    public function writeToConfigFile($attribute, $newValue, $currentValue){
        $configContents = file_get_contents($this->absoluteConfigLocation);
        $tmp = str_replace($attribute . "=" . $currentValue, $attribute . " = " . $newValue, $configContents);  
        file_put_contents($this->absoluteConfigLocation, $tmp);
    }
}
