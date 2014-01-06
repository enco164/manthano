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
        private $exist;

        //public function __construct($id, $db){
        public function __construct($id){
            $CI=&get_instance();
            $result=$CI->crud_model->db_get_user($id);
            if($result){
                $this->exist=1;
                $this->id = $result['user_id'];
                $this->Name=$result['Name'];
                $this->Username=$result['username'];
                $this->Surname = $result['Surname'];
                $this->www = $result['www'];
                $this->Proffession = $result['Proffession'];
                $this->Mail = $result['mail'];
                $this->School = $result['School'];
                $this->ProfilePicture=$result['ProfilePicture'];
                $this->status = $result['status'];
            }else{
                $this->exist=0;
            }
        }

        public function __destruct(){
            $this->exist=0;
            $this->id = 0;
            $this->Name=0;
            $this->Surname = 0;
            $this->Mail = 0;
            $this->Username = 0;
            $this->www = 0;
            $this->Proffession = 0;
            $this->School = 0;
            $this->ProfilePicture=0;
            $this->status = 0;
        }

        /* getters and setters*/

        public function Name(){ return $this->Name;}
        public function ProfilePicture(){ return $this->ProfilePicture;}
        public function id(){ return $this->id;}
        public function exists(){return $this->exist;}
        public function Surname(){ return $this->Surname;}
        public function Mail(){ return $this->Mail;}
        public function Proffession(){ return $this->Proffession;}
        public function School(){ return $this->School;}
        public function status(){ return $this->status;}
        public function www(){ return $this->www;}
        public function Username(){ return $this->Username;}


        public function setName($value){ $this->Name = $value;}
        public function setProfilePicture($value){ $this->ProfilePicture = $value;}
        public function setSurname($value){ $this->Surname = $value;}
        public function setUsername($value){ $this->Username = $value;}
        public function setProffession($value){ $this->Proffession = $value;}
        public function setSchool($value){ $this->School = $value;}
        public function setwww($value){ $this->www = $value;}
        public function setMail($value){ $this->www = $value;}

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
            $db_data['$Name']=$$this->Name;
            $db_data['Surname']=$this->Surname;
            $db_data['username']=$this->Username;
            $db_data['mail']=$this->Mail;
            $db_data['School']=$this->School;
            $db_data['ProfilePicture']=$this->ProfilePicture;
            $db_data['Proffession']=$this->Proffession;
            //$db_data['status']=$this->status;
            //$db_data['username']=$this->Username;
            $db_data['www']=$this->www;

            $CI=&get_instance();
            return $CI->crud_model->db_update_user_data($this->id, $db_data);
        }

        public function delete(){//srsly
            $CI=&get_instance();
            //$CI->crud_model->db_delete_user($this->id);//srsly....
        }

        public static function is_holder($idUser, $idActivity, $db){}
    }