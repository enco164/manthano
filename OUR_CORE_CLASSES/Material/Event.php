<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 1/2/14
 * Time: 11:37 AM
 */

class Event {
    private $db;
    private $id;
    private $Name;
    private $Description;
    private $Venue;
    private $Date;
    private $Time;
    private $exist;

    public function __construct($id, $db){
        $this->db = $db;
        $stmt = $this->db->prepare("SELECT Name, Description, Venue, Date, Time FROM Event WHERE idEvent = :id ");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($stmt->rowCount())
            $this->exist = 1;
        else
            $this->exist = 0;
        $this->id = $id;
        $this->Name = $result['Name'];
        $this->Description = $result['Description'];
        $this->Venue = $result['Venue'];
        $this->Date = $result['Date'];
        $this->Time = $result['Time'];
    }
    public function __destruct(){
        $this->id = null;
        $this->Name = null;
        $this->Description = null;
        $this->Venue = null;
        $this->Date = null;
        $this->Time = null;
        $this->db = null;
        $this->exist = null;
    }

    /* getters and setters*/

    public function Name(){ return $this->Name;}
    public function Description(){ return $this->Description;}
    public function Venue(){ return $this->Venue;}
    public function Date(){ return $this->Date;}
    public function Time(){return $this->Time;}
    public function id(){ return $this->id;}

    public function setName($value){ $this->Name = $value;}
    public function setDescription($value){ $this->Description = $value;}
    public function setVenue($value){ $this->Venue = $value;}
    public function setDate($value){ $this->CoverPicture = $value;}
    public function setTime($value){$this->Time = $value;}

    /* error handling for Accessing the wrong way to private properties of object*/
    public function __set($name, $value){
        echo "You are trying to direct set property ".$name." to value ".$value." and thats not possible use set".$name."(\$value) method.";
        echo "<br/> At line ".__LINE__." in file ".__FILE__;
    }
    public function __get($name){
        echo "You are trying to direct access property ".$name."and thats not possible. Use ".$name."() method";
        echo "<br/> At line ".__LINE__." in file ".__FILE__;
    }
    public function __toString(){return "dummy";}

    public function getParentActivities(){}
    public function getMaterials(){}
    public function getHolders(){}
    public function upload(){}

    static public function addEvent($idActivity, $db, $Name, $Description, $Venue, $Date, $Time){}
    static public function deleteEvent($idEvent, $db){}


} 