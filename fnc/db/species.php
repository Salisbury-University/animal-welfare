<?php
class Species
{
    private $id = NULL; // different than that of the id of construct
    private $animals = [];
    protected $formID = NULL;
    private $connection = NULL;
    function __construct($id, $connection)
    {
        $this->connection = $connection;

        // Error handling is important
        try {
            $this->intializeSpeciesData($id);
        } catch (Exception $e) {
            // Handle the exception appropriately
            // Close database connections and perform cleanup
            // Rethrow the exception or log it as needed
        }
        
    }
    private function intializeSpeciesData($id){
        $this->id = $id;
        $getBasicData = $this->connection->prepare("SELECT `form_id` FROM `species` WHERE id=?");
        $getBasicData->bind_param("s", $id);
        $getBasicData->execute();
        $getBasicData->bind_result($formID);
        while ($getBasicData->fetch()) {
            $this->formID = $formID;
        }
        $getBasicData->close();
        $getAnimals = $this->connection->prepare("SELECT `id` FROM `animals` WHERE `species_id`=?");
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

    public function getFormID()
    {
        return $this->formID;
    }

    public function getAnimals()
    {
        return $this->animals;
    }
}