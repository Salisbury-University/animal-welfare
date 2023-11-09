<?php
// namespace db;
// use db\Species;
// use db\Section;
// use auth\DatabaseManager;

class Animal
{
    public $id = NULL;
    public $section = NULL;
    private $species = NULL;
    private $sex = NULL;
    private $birthDate = NULL;
    private $acqDate = NULL;
    public $name = NULL;
    private $database = NULL;


    function __construct($id, $database)
    {
        $this->database = $database;
        $this->initializeAnimalData($id);
    }

    private function initializeAnimalData($id)
    {

        $query = "SELECT `section`, `species_id`, `sex`, `birth_date`, `acquisition_date`, `name` FROM `animals` WHERE id=?";
        $basicData = $this->database->runParameterizedQuery($query, "i", array($id));

        while ($data = $basicData->fetch_array(MYSQLI_ASSOC)) {
            $this->id = $id;
            $this->section = $data['section'];
            $this->species = $data['species_id'];
            $this->sex = $data['sex'];
            $this->birthDate = $data['birth_date'];
            $this->acqDate = $data['acquisition_date'];
            $this->name = $data['name'];
        }
        // May be dropping having instances inside
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
        $query = "SELECT `wid`, `avg_health`, `avg_nutrition`, `avg_pse`, `avg_behavior`, `avg_mental` FROM `welfaresubmission` WHERE zim=?";
        $averages = $this->database->runParameterizedQuery($query, "i", $this->id);

        $average = 0;
        $count = 0;
        while ($average = $averages->fetch_array(MYSQLI_ASSOC)) {
            $average += ($average['avg_health'] + $average['avg_nutrition'] + $average['avg_pse'] + $average['avg_behavior'] + $average['avg_mental']) / 5;
            $count++;
        }
        if ($count == 0)
            return "No Data Available";
        return $average / $count;
    }
    public function getAllAverages() // Nested/key array for all averages
    {
        $query = "SELECT `wid`, `dates`,`avg_health`, `avg_nutrition`, `avg_pse`, `avg_behavior`, `avg_mental` FROM `welfaresubmission` WHERE zim=?";
        $averages = $this->database->runParameterizedQuery($query, "i", $this->id);
        $averageList = [];
        while ($average = $averages->fetch_array(MYSQLI_ASSOC)) {
            $averageList[$average['wid']] = ['date' => $average['dates'], 'health' => $average['avg_health'], 'nutrition' => $average['avg_nutrition'], 'pse' => $average['avg_pse'], 'behavior' => $average['avg_behavior'], 'mental' => $average['avg_mental']];
        }

        return $averageList;
    }
    public function getAllCheckupAverages() // Nested/key array for all total averages
    {
        $query = "SELECT `wid`, `dates`,`avg_health`, `avg_nutrition`, `avg_pse`, `avg_behavior`, `avg_mental` FROM `welfaresubmission` WHERE zim=?";
        $averages = $this->database->runParameterizedQuery($query, "i", $this->id);

        $averageList = [];
        while ($average = $averages->fetch_array(MYSQLI_ASSOC)) {
            $averageList[$average['wid']] = ($average['avg_health'] + $average['avg_nutrition'] + $average['avg_pse'] + $average['avg_behavior'] + $average['avg_mental']) / 5;
        }

        return $averageList;
    }
    public function getLatestCheckupDate() // Date of most recent checkup
    {
        $query = "SELECT `dates` FROM welfaresubmission WHERE zim=?";
        $dates = $this->database->runParameterizedQuery($query, "i", $this->id);
        $day = "";
        $num = 0;
        while ($date = $dates->fetch_array(MYSQLI_ASSOC)) {
            if ($num == 0) {
                $day = $date['dates'];
                $num += 1;
            } else {
                if ($day < $date['dates']) {
                    $day = $date['dates'];
                }
            }
        }

        return $date;
    }
    public function getCheckupOverallAverage($date) // Average on a specific date
    {
        $query = "SELECT `wid`, `avg_health`, `avg_nutrition`, `avg_pse`, `avg_behavior`, `avg_mental` FROM `welfaresubmission` WHERE zim=? AND dates=?";
        $avgsOnDate = $this->database->runParameterizedQuery($query, "is", $this->id, $date);

        $average = 0;
        while ($avgOnDate = $avgsOnDate->fetch_array(MYSQLI_ASSOC)) {
            $average += ($avgOnDate['avg_health'] + $avgOnDate['avg_nutrition'] + $avgOnDate['avg_pse'] + $avgOnDate['avg_behavior'] + $avgOnDate['avg_mental']) / 5;
        }

        return $average;
    }
    public function getSectionAveragesOnDate($date) // Array of averages on a specific date
    {
        $query = "SELECT `dates`, `wid`, `avg_health`, `avg_nutrition`, `avg_pse`, `avg_behavior`, `avg_mental` FROM `welfaresubmission` WHERE zim=? AND dates=?";
        $avgsOnDate = $this->database->runParameterizedQuery($query, "is", $this->id, $date);

        $averageList = [];
        while ($avgOnDate = $avgsOnDate->fetch_array(MYSQLI_ASSOC)) {
            $averageList[$avgOnDate['dates']] = ['id' => $avgOnDate['wid'], 'health' => $avgOnDate['avg_health'], 'nutrition' => $avgOnDate['avg_nutrition'], 'pse' => $avgOnDate['avg_pse'], 'behavior' => $avgOnDate['avg_behavior'], 'mental' => $avgOnDate['avg_mental']];
        }

        return $averageList;
    }
    public function getOverallSectionAverage($section) // Average of all in specific section
    {
        // <NOTE>: not available - will work with new db
        // $getAverages = $this->database->prepare("SELECT `wid`, ? FROM `welfaresubmission` WHERE zim=?");
        // $getAverages->bind_param("si", $section, $this->id);
        // $getAverages->execute();
        // $getAverages->bind_result($checkupID, $avgSection);
        // $average = 0;
        // $count = 0;
        // while ($getAverages->fetch()) {
        //     $average += $avgSection;
        //     $count++;
        // }
        // if ($count == 0)
        //     return "N/A";
        // $getAverages->close();
        // return $average / $count;
        return;
    }
    public function getCheckupAverages($section) // Nested/key array for all averages in a specific section
    {
        // <NOTE>: not available - will work with new db
        // $getAverages = $this->database->prepare("SELECT `wid`, `dates`, ? FROM `welfaresubmission` WHERE zim=?");
        // $getAverages->bind_param("si", $section, $this->id);
        // $getAverages->execute();
        // $getAverages->bind_result($checkupID, $checkupDate, $avgSection);

        // $averages = [];
        // while ($getAverages->fetch()) {
        //     $averages[$checkupDate] = $avgSection;
        // }
        // $getAverages->close();

        // return $averages;
        return;
    }
    public function getSectionAverageOnDate($section, $date) // Average of a specific section on a specific date
    {
        // <NOTE>: not available - will work with new db
        // $getAverage = $this->database->prepare("SELECT `wid`, ? FROM `welfaresubmission` WHERE zim=? AND dates=?");
        // $getAverage->bind_param("sis", $this->section, $this->id, $date);
        // $getAverage->execute();
        // $getAverage->bind_result($checkupID, $avgSection);

        // $average = 0;
        // while ($getAverage->fetch()) {
        //     $average += $avgSection;
        // }
        // $getAverage->close();
        // return $average;
        return;
    }
    public function getCheckupResponses($date) // Responses on a specific checkup no.
    {
        $query = "SELECT `wid`, `responses` FROM `welfaresubmission` WHERE zim=? AND dates=?";
        $responses = $this->database->runParameterizedQuery($query, "is", $this->id, $date);

        $responseList = [];
        while ($response = $responses->fetch_array(MYSQLI_ASSOC)) {
            $responseList[] = $this->parseResponses($response['wid'], $response['responses']);
        }

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
    public function compareAnimals($id, $mode = 0, $date = NULL)
    { // change to pass an Animal obj 
        $result = [];
        switch ($mode) {
            case 0:
                $animal = new Animal($id, $this->database);
                $result[$this->id] = $this->getOverallAverage();
                $result[$id] = $animal->getOverallAverage();

                return $result;
            case 1:
                $animal = new Animal($id, $this->database);
                $result[$this->id] = $this->getAllAverages();
                $result[$id] = $animal->getAllAverages();

                return $result;
            case 2:
                $animal = new Animal($id, $this->database);
                $result[$this->id] = $this->getAllCheckupAverages();
                $result[$id] = $animal->getAllCheckupAverages();

                return $result;
            case 3:
                $animal = new Animal($id, $this->database);
                if ($date != NULL) {
                    $result[$this->id] = $this->getCheckupOverallAverage($date);
                    $result[$id] = $animal->getCheckupOverallAverage($date);
                }
                return $result;
            case 4:
                $animal = new Animal($id, $this->database);
                if ($date != NULL) {
                    $result[$this->id] = $this->getCheckupResponses($date);
                    $result[$id] = $animal->getCheckupResponses($date);
                }
                return $result;
        }
    }
}