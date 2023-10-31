<?php namespace fnc\db;

use fnc\db\Species;
use fnc\db\Section;

// include "species.php";
// include "section.php";
class Animal
{
    public $id = NULL;
    public $section = NULL;
    private $species = NULL;
    private $sex = NULL;
    private $birthDate = NULL;
    private $acqDate = NULL;
    public $name = NULL;
    private $connection = NULL;


    function __construct($id, $connection)
    {
        $this->connection = $connection;

        $this->initializeAnimalData($id);
        
    }

    private function initializeAnimalData($id)
    {
        $getBasic = $this->connection->prepare("SELECT `section`, `species_id`, `sex`, `birth_date`, `acquisition_date`, `name` FROM `animals` WHERE id=?");
        $getBasic->bind_param("i", $id);
        $getBasic->execute();
        $getBasic->bind_result($section, $species, $sex, $birthDay, $acqDay, $name);
        $species = "";
        $section = "";
        
        while ($getBasic->fetch()) {
            $this->id = $id;
            $section_id = $section;
            $species_id = $species;
            $this->sex = $sex;
            $this->birthDate = $birthDay;
            $this->acqDate = $acqDay;
            $this->name = $name;
        }
        $getBasic->close();
        $this->species = new Species($species_id, $this->connection);
        $this->section = new Section($section_id, $this->connection);
        // Remember to properly handle exceptions and close resources
    }

    public function getID()
    {
        return $this->id;
    }
    public function getSection()
    {
        return $this->section;
    }
    public function getSpecies()
    {
        return $this->species;
    }
    public function getSex()
    {
        return $this->sex;
    }
    public function getBirthday()
    {
        return $this->birthDate;
    }
    public function getAcqDay()
    {
        return $this->acqDate;
    }
    public function getName()
    {
        return $this->name;
    }

    public function getOverallAverage() // Average of all averages
    {
        $getAverages = $this->connection->prepare("SELECT `wid`, `avg_health`, `avg_nutrition`, `avg_pse`, `avg_behavior`, `avg_mental` FROM `welfaresubmission` WHERE zim=?");
        $getAverages->bind_param("i", $this->id);
        $getAverages->execute();
        $getAverages->bind_result($checkupID, $avgHealth, $avgNutrition, $avgPSE, $avgBehavior, $avgMental);
        $average = 0;
        $count = 0;
        while ($getAverages->fetch()) {
            $average += ($avgHealth + $avgNutrition + $avgPSE + $avgBehavior + $avgMental) / 5;
            $count++;
        }
        if ($count == 0)
            return "No Data Available";
        $getAverages->close();
        return $average / $count;
    }
    public function getAllAverages() // Nested/key array for all averages
    {
        $getAverages = $this->connection->prepare("SELECT `wid`, `dates`,`avg_health`, `avg_nutrition`, `avg_pse`, `avg_behavior`, `avg_mental` FROM `welfaresubmission` WHERE zim=?");
        $getAverages->bind_param("i", $this->id);
        $getAverages->execute();
        $getAverages->bind_result($checkupID, $checkupDate, $avgHealth, $avgNutrition, $avgPSE, $avgBehavior, $avgMental);

        $averages = [];
        while ($getAverages->fetch()) {
            $averages[$checkupID] = ['date' => $checkupDate, 'health' => $avgHealth, 'nutrition' => $avgNutrition, 'pse' => $avgPSE, 'behavior' => $avgBehavior, 'mental' => $avgMental];

        }
        $getAverages->close();

        return $averages;
    }
    public function getAllCheckupAverages() // Nested/key array for all total averages
    {
        $getAverages = $this->connection->prepare("SELECT `wid`, `dates`,`avg_health`, `avg_nutrition`, `avg_pse`, `avg_behavior`, `avg_mental` FROM `welfaresubmission` WHERE zim=?");
        $getAverages->bind_param("i", $this->id);
        $getAverages->execute();
        $getAverages->bind_result($checkupID, $checkupDate, $avgHealth, $avgNutrition, $avgPSE, $avgBehavior, $avgMental);

        $averages = [];
        while ($getAverages->fetch()) {
            $average = ($avgHealth + $avgNutrition + $avgPSE + $avgBehavior + $avgMental) / 5;
            $averages[$checkupDate] = $average;
        }
        $getAverages->close();

        return $averages;
    }
    public function getLatestCheckupDate() // Date of most recent checkup
    {
        $getDate = $this->connection->prepare("SELECT `dates` FROM welfaresubmission WHERE zim=?");
        $getDate->bind_param("i", $this->id);
        $getDate->execute();
        $getDate->bind_result($dates);
        $date = "";
        $num = 0;
        while ($getDate->fetch()) {
            if ($num == 0) {
                $date = $dates;
                $num += 1;
            } else {
                if ($date < $dates) {
                    $date = $dates;
                }
            }
        }
        $getDate->close();
        return $date;
    }
    public function getCheckupOverallAverage($date) // Average on a specific date
    {
        $getAverage = $this->connection->prepare("SELECT `wid`, `avg_health`, `avg_nutrition`, `avg_pse`, `avg_behavior`, `avg_mental` FROM `welfaresubmission` WHERE zim=? AND dates=?");
        $getAverage->bind_param("is", $this->id, $date);
        $getAverage->execute();
        $getAverage->bind_result($checkupID, $avgHealth, $avgNutrition, $avgPSE, $avgBehavior, $avgMental);

        $average = 0;
        while ($getAverage->fetch()) {
            $average += ($avgHealth + $avgNutrition + $avgPSE + $avgBehavior + $avgMental) / 5;
        }
        $getAverage->close();
        return $average;
    }
    public function getSectionAveragesOnDate($date) // Array of averages on a specific date
    {
        $getAverages = $this->connection->prepare("SELECT `dates`, `wid`, `avg_health`, `avg_nutrition`, `avg_pse`, `avg_behavior`, `avg_mental` FROM `welfaresubmission` WHERE zim=? AND dates=?");
        $getAverages->bind_param("is", $this->id, $date);
        $getAverages->execute();
        $getAverages->bind_result($checkupDate, $checkupID, $avgHealth, $avgNutrition, $avgPSE, $avgBehavior, $avgMental);

        $averages = [];
        while ($getAverages->fetch()) {
            $averages[$checkupDate] = ['id' => $checkupID, 'health' => $avgHealth, 'nutrition' => $avgNutrition, 'pse' => $avgPSE, 'behavior' => $avgBehavior, 'mental' => $avgMental];
        }
        $getAverages->close();
        return $averages;
    }
    public function getOverallSectionAverage($section) // Average of all in specific section
    {
        $getAverages = $this->connection->prepare("SELECT `wid`, ? FROM `welfaresubmission` WHERE zim=?");
        $getAverages->bind_param("si", $section, $this->id);
        $getAverages->execute();
        $getAverages->bind_result($checkupID, $avgSection);
        $average = 0;
        $count = 0;
        while ($getAverages->fetch()) {
            $average += $avgSection;
            $count++;
        }
        if ($count == 0)
            return "N/A";
        $getAverages->close();
        return $average / $count;
    }
    public function getCheckupAverages($section) // Nested/key array for all averages in a specific section
    {
        $getAverages = $this->connection->prepare("SELECT `wid`, `dates`, ? FROM `welfaresubmission` WHERE zim=?");
        $getAverages->bind_param("si", $section, $this->id);
        $getAverages->execute();
        $getAverages->bind_result($checkupID, $checkupDate, $avgSection);

        $averages = [];
        while ($getAverages->fetch()) {
            $averages[$checkupDate] = $avgSection;
        }
        $getAverages->close();

        return $averages;
    }
    public function getSectionAverageOnDate($section, $date) // Average of a specific section on a specific date
    {
        $getAverage = $this->connection->prepare("SELECT `wid`, ? FROM `welfaresubmission` WHERE zim=? AND dates=?");
        $getAverage->bind_param("sis", $this->section, $this->id, $date);
        $getAverage->execute();
        $getAverage->bind_result($checkupID, $avgSection);

        $average = 0;
        while ($getAverage->fetch()) {
            $average += $avgSection;
        }
        $getAverage->close();
        return $average;
    }
    public function getCheckupResponses($date) // Responses on a specific checkup no.
    {
        $getAverage = $this->connection->prepare("SELECT `wid`, `responses` FROM `welfaresubmission` WHERE zim=? AND dates=?");
        $getAverage->bind_param("is", $this->id, $date);
        $getAverage->execute();
        $getAverage->bind_result($checkupID, $responseList);

        while ($getAverage->fetch()) {
            $responses[] = $this->parseResponses($checkupID, $responseList);
        }
        $getAverage->close();
        return $responses;
    }

    private function parseResponses($checkupID, $responseList) // soon deprecated
    {
        $responses = ['checkupID' => $checkupID, 'formID' => null, 'sections' => []];
        $res = unpack("C*", $responseList);
        $rowCount = $colCount = 0;
        $next = $header = true;
        foreach ($res as $data) {
            if (chr($data) == ',') { // Split the data by comma
                if ($next) {
                    $colCount++;
                    $next = false;
                } else {
                    $next = true;
                }
            } else if ($data == 10) { // Split data by row
                $colCount = 0;
                if ($header)
                    $header = false;
                $rowCount++;
            } else if ($data != 13 && chr($data) != ',' && $data != 00) {
                if ($header) {
                    if ($colCount == 0) {
                        $responses['formID'] = chr($data);
                    } else {
                        $responses["sections"][$colCount]['colID'] = chr($data);
                    }
                } else {
                    $responses["sections"][$colCount][$rowCount] = chr($data);

                }
                $colCount++;
                $next = false;
            }
        }
        return $responses;
    }
    public function compareAnimals($id, $mode = 0, $date = NULL){ // change to pass an Animal obj 
        $result = [];
        switch($mode){
            case 0:
                $animal = new Animal($id,$this->connection);
                $result[$this->id] = $this->getOverallAverage();
                $result[$id] = $animal->getOverallAverage();
                
                return $result;
            case 1:
                $animal = new Animal($id,$this->connection);
                $result[$this->id] = $this->getAllAverages();
                $result[$id] = $animal->getAllAverages();
                
                return $result;
            case 2:
                $animal = new Animal($id,$this->connection);
                $result[$this->id] = $this->getAllCheckupAverages();
                $result[$id] = $animal->getAllCheckupAverages();
                
                return $result;
            case 3:
                $animal = new Animal($id,$this->connection);
                if($date != NULL){
                    $result[$this->id] = $this->getCheckupOverallAverage($date);
                    $result[$id] = $animal->getCheckupOverallAverage($date);
                }
                return $result;
            case 4:
                $animal = new Animal($id,$this->connection);
                if($date != NULL){
                    $result[$this->id] = $this->getCheckupResponses($date);
                    $result[$id] = $animal->getCheckupResponses($date);
                }
                return $result;
        }
    }
}