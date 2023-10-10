<?php
class Section
{
    private $id = NULL; // different than that of the id of construct
    private $animals = [];
    protected $formID = NULL;
    private $connection = NULL;
    function __construct($id, $connection)
    {
        $this->connection = $connection;
        $this->initializeSectionData($id);
    }
    private function initializeSectionData($id){
        $this->id = $id;
        $getAnimals = $this->connection->prepare("SELECT `id` FROM `animals` WHERE `section`=?");
        $getAnimals->bind_param("s", $this->id);
        $getAnimals->execute();
        $getAnimals->bind_result($id);

        while ($getAnimals->fetch()) {
            if(!is_null($id))
                array_push($this->animals, $id);
        }
        $getAnimals->close();
    }

    public function getID()
    {
        return $this->id;
    }

    public function getAnimals()
    {
        return $this->animals;
    }

    public function compareAnimals($mode = 0,$date = NULL){
        $result = [];
        switch($mode){
            case 0:
                foreach($this->animals as $id){
                    $animal = new Animal($id,$this->connection);
                    $result[$id] = $animal->getOverallAverage();
                }
                return $result;
            case 1:
                foreach($this->animals as $id){
                    $animal = new Animal($id,$this->connection);
                    $result[$id] = $animal->getAllAverages();
                }
                return $result;
            case 2:
                foreach($this->animals as $id){
                    $animal = new Animal($id,$this->connection);
                    $result[$id] = $animal->getAllCheckupAverages();
                }
                return $result;
            case 3:
                foreach($this->animals as $id){
                    $animal = new Animal($id,$this->connection);
                    if($date != NULL)
                        $result[$id] = $animal->getCheckupOverallAverage($date);
                }
                return $result;
            case 4:
                foreach($this->animals as $id){
                    $animal = new Animal($id,$this->connection);
                    if($date != NULL)
                        $result[$id] = $animal->getCheckupResponses($date);
                }
                return $result;
        }
    }
}