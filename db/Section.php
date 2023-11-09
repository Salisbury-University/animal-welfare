<?php
// namespace db;
// use db\Animal;
// use auth\DatabaseManager;

include_once "./Animal.php";
class Section
{
    private $id = NULL; // different than that of the id of construct
    private $animals = [];
    private $database = NULL;
    function __construct($id, $database)
    {
        $this->database = $database;
        $this->id = $id;

        $query = "SELECT `id` FROM `animals` WHERE `section`=?";
        $animals = $this->database->runParameterizedQuery($query, "s", $this->id);

        while ($animal = $animals->fetch_array(MYSQLI_ASSOC)) {
            if (!is_null($animal['id']))
                array_push($this->animals, $animal['id']);
        }
    }
    private function initializeSectionData($id)
    {

    }

    public function getID()
    {
        return $this->id;
    }

    public function getAnimals()
    {
        return $this->animals;
    }

    public function compareAnimals($mode = 0, $date = NULL)
    {
        $result = [];
        switch ($mode) {
            case 0:
                foreach ($this->animals as $id) {
                    $animal = new Animal($id, $this->database);
                    $result[$id] = $animal->getOverallAverage();
                }
                return $result;
            case 1:
                foreach ($this->animals as $id) {
                    $animal = new Animal($id, $this->database);
                    $result[$id] = $animal->getAllAverages();
                }
                return $result;
            case 2:
                foreach ($this->animals as $id) {
                    $animal = new Animal($id, $this->database);
                    $result[$id] = $animal->getAllCheckupAverages();
                }
                return $result;
            case 3:
                foreach ($this->animals as $id) {
                    $animal = new Animal($id, $this->database);
                    if ($date != NULL)
                        $result[$id] = $animal->getCheckupOverallAverage($date);
                }
                return $result;
            case 4:
                foreach ($this->animals as $id) {
                    $animal = new Animal($id, $this->database);
                    if ($date != NULL)
                        $result[$id] = $animal->getCheckupResponses($date);
                }
                return $result;
        }
    }
}