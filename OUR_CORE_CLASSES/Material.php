<?php
/**
 * Created by Notepad+.
 * User: Gera
 * Date: 12/22/13
 * Time: 4:23 PM
 */

class Material {

    private $Name;
    private $URI;
    private $Type;
    private $Date;
	private $OwnerID;
    private $db;
    private $id;
    private $exist;

    public function __construct($id, $db){
        $this->db = $db;
        $stmt = $db->prepare("SELECT Name, URI, Type, Date,OwnerID FROM Material WHERE idMaterial = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($stmt->rowCount())
            $this->exist = 1;
        else
            $this->exist = 0;
        $this->id = $id;
        $this->Name = $result['Name'];
        $this->URI = $result['URI'];
        $this->Type = $result['Type'];
        $this->Date = $result['Date'];
        $this->OwnerID = $result['OwnerID'];
    }

    public function __destruct(){       
		
		$this->id = null;
        $this->Name = null;
        $this->URI = null;
        $this->Type = null;
        $this->Date = null;
        $this->OwnerID =null;
		$this->db = null;
    }
    

    public function exists(){
        return $this->exist;
    }

    /* get and set functions*/

    public function Name(){ return $this->Name;}
    public function URI(){ return $this->URI;}
	public function Type(){ return $this->Type;}
    public function Date(){ return $this->Date;}
    public function OwnerID(){ return $this->OwnerID;}
    public function id(){ return $this->id;}

    public function setName($value){ $this->Name = $value;}
    public function setURI($value){ $this->URI = $value;}
	public function setType($value){ $this->Type = $value;}
    public function setDate($value){ $this->Date = $value;}
	public function setOwnerID($value){ $this->OwnerID = $value;}
    public function setID($value){ $this->ID = $value;}

    /* error handling for Accessing the wrong way to private properties of object*/
    public function __set($name, $value){
        echo "You are trying to direct set property ".$name." to value ".$value." and that is not possible use set".$name."(\$value) method.";
        echo "<br/> At line ".__LINE__." in file ".__FILE__;
    }
    public function __get($name){
        echo "You are trying to direct access property ".$name."and that is not possible. Use ".$name."() method";
        echo "<br/> At line ".__LINE__." in file ".__FILE__;
    }
    /* getting path from top of tree to chosen Material */
    public function getPath(){
        $db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $stmt = $db->prepare("call pathToMaterial(:id)");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $ret = array();
        foreach($result as $i){
            array_push($ret, $i['idMaterial']);
        }
        return $ret;
    }

    /* Find all direct instances of Material 
     *  Return value is array with id of Materials.
     *
     */
    public function getInstances(){
        $stmt = $this->db->prepare("call sonOfActivity(:id)");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $ret = array();
        foreach($result as $i){
            array_push($ret,$i['idActivity']);
        }
        return $ret;
    }
    /* Finding Materials by id and return all relevant informations.
     * Return value is array if Materials exist, otherwise returns String.
     * Array has Name, URI, Type, Date, OwnerID, Active
     */
    public function getFullInfo(){
        $stmt = $this->db->prepare("SELECT Name, URI, Type, Date,OwnerID FROM Material WHERE idMaterial = :id");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();
        if( $stmt->rowCount()  == 0){
            return "There is no any Material! ";
        }
        else{
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
    }
    
    /* Updates Material.
     * Update few attributes of one or more record from Material table;
     * Return true if successful or false if not.
     * */
    public function update(){
        $query = "UPDATE Material SET Name = :name, URI = :URI,Type=:Type, Date = :date, OwnerId=:OwnerID WHERE idMaterial = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":name", $this->Name, PDO::PARAM_STR);
        $stmt->bindParam(":URI", $this->URI, PDO::PARAM_STR);
		$stmt->bindParam(":Type", $this->Type, PDO::PARAM_STR);
        $stmt->bindParam(":date", $this->Date, PDO::PARAM_STR);
        $stmt->bindParam(":OwnerID", $this->OwnerID, PDO::PARAM_STR);
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount() ? true : false;
    }
    

    /* Static methods */

    /* Delete Material
     * No return value;
     */
    static public function deleteMaterial($id, $db){
        /* upotrebiti proceduru ovde  */
        $stmt = $db->prepare("call deleteMaterial(:id)");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() ? true : false;
    }
}