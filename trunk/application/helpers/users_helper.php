<?php
    class Users{

        private $Name;
        private $Surname;
        private $Username;
        private $Mail;
        private $School;
        private $ProfilePicture;
        private $Proffession;
        private $status;
        private $www;
        private $db;
        private $id;


        //public function __construct($id, $db){
        public function __construct($id){
            $CI=&get_instance();
            $result=$CI->crud_model->db_get_user($id);
            //$this->id = $result['user_id'];
            //$this->setName($result['Name']);
            //$this->Surname = $result['Surname'];
            //$this->www = $result['www'];
            //$this->Proffession = $result['Proffession'];
            $this->setProfilePicture($result['ProfilePicture']);
            //$this->status = $result['status'];

        }

        public function __destruct(){
            $this->id = null;
            $this->Name = null;
            $this->Description = null;
            $this->BeginDate = null;
            $this->CoverPicture = null;
            $this->db = null;
        }

        /* getters and setters*/

        public function Name(){ return $this->Name;}

        public function ProfilePicture(){ return $this->ProfilePicture;}
        public function id(){ return $this->id;}

        public function setName($value){ $this->Name = $value;}
        public function setProfilePicture($value){ $this->ProfilePicture = $value;}

        /* error handling for Accessing the wrong way to private properties of object*/
        public function __set($name, $value){
            echo "You are trying to direct set property ".$name." to value ".$value." and thats not possible use set".$name."(\$value) method.";
            echo "<br/> At line ".__LINE__." in file ".__FILE__;
        }
        public function __get($name){
            echo "You are trying to direct access property ".$name."and thats not possible. Use ".$name."() method";
            echo "<br/> At line ".__LINE__." in file ".__FILE__;
        }



        public function update(){
            $query = "UPDATE Activity SET Name = :name, Description = :desc, BeginDate = :date, CoverPicture = :cover WHERE idActivity = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":name", $this->Name, PDO::PARAM_STR);
            $stmt->bindParam(":desc", $this->Description, PDO::PARAM_STR);
            $stmt->bindParam(":date", $this->BeginDate, PDO::PARAM_STR);
            $stmt->bindParam(":cover", $this->CoverPicture, PDO::PARAM_STR);
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->rowCount() ? true : false;
        }

        public static function is_holder($idUser, $idActivity, $db){}
    }