<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 1/3/14
 * Time: 6:14 PM
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
        $this->db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
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
    public function exists(){
        return $this->exist;
    }

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

    public function getParentActivities(){
        $stmt = $this->db->prepare("SELECT a.Name, a.idActivity FROM Activity a join activityContains ac on a.idActivity = ac.idActivity WHERE ac.idEvent = :id");
        $stmt->bindParam(":id",$this->id,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    public function getMaterials(){
        $stmt = $this->db->prepare("SELECT a.Name, a.idEvent FROM Event a join eventContains ac on a.idEvent = ac.idEvent WHERE ac.idEvent = :id");
        $stmt->bindParam(":id",$this->id,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getHolders(){
        $stmt = $this->db->prepare("SELECT u.user_id, u.Name, u.Surname FROM User u join eventHolder eh on u.user_id = eh.user_id WHERE eh.idEvent = :id");
        $stmt->bindParam(":id",$this->id,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isHolder($id){
        $stmt = $this->db->prepare("SELECT * FROM  eventHolder WHERE idEvent = :id and user_id = :uid");
        $stmt->bindParam(":id",$this->id,PDO::PARAM_INT);
        $stmt->bindParam(":uid", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount()? 1:0;
    }

    public function upload(){
        $query = "UPDATE Event SET Name = :name, Description = :desc, Venue = :venue, Date = :date, Time = :time WHERE idEvent = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":name", $this->Name, PDO::PARAM_STR);
        $stmt->bindParam(":desc", $this->Description, PDO::PARAM_STR);
        $stmt->bindParam(":venue", $this->Venue, PDO::PARAM_STR);
        $stmt->bindParam(":date", $this->Date, PDO::PARAM_STR);
        $stmt->bindParam(":time", $this->Time, PDO::PARAM_STR);
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() ? true : false;
    }

    static public function addEvent($idActivity, $db, $Name, $Description, $Venue, $Date, $Time){
        $stmt = $db->prepare("call addEvent(:id, :name, :desc, :venue, :date, :time);");
        $stmt->bindParam(":id", $idActivity, PDO::PARAM_INT);
        $stmt->bindParam(":name", $Name, PDO::PARAM_STR);
        $stmt->bindParam(":desc", $Description, PDO::PARAM_STR);
        $stmt->bindParam(":venue", $Venue, PDO::PARAM_STR);
        $stmt->bindParam(":date", $Date, PDO::PARAM_STR);
        $stmt->bindParam(":time", $Time, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount() ? true : false;
    }
    static public function deleteEvent($idEvent, $db){
        $stmt = $db->prepare("DELETE FROM Event where idEvent = :id");
        $stmt->bindParam(":id", $idEvent, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() ? false : true;
    }
/*
 * INPUT: id of activity, user id, db
 * */
    static public function listExistingEventsForUser ($ida, $uid, $db){
        $stmt = $db->prepare("select e.idEvent, e.name,CASE WHEN ac.idActivity = :ida THEN 1 ELSE 0  END as 'isMember'
                              from event e join activitycontains ac on e.idEvent = ac.idEvent
                              where exists(select * from eventholder where user_id = :uid and idEvent = e.idEvent)
                              group by e.idEvent, e.name LIMIT 0, 1000");
        $stmt->bindParam(":ida", $ida, PDO::PARAM_INT);
        $stmt->bindParam(":uid", $uid, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function isHolderStatic($user_id, $idEvent, $db){
        $stmt = $db->prepare("select * from eventholder where user_id = :uid and idEvent = :ide ");
        $stmt->bindParam(":ide", $idEvent, PDO::PARAM_INT);
        $stmt->bindParam(":uid", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $isHolderV = $stmt->rowCount() ? true : false;
        return $isHolderV || is_admin();
    }
   

} 