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
}