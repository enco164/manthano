<?php
    /**
     * Created by PhpStorm.
     * User: Stefan
     * Date: 12/19/13
     * Time: 12:05 PM
     */

    class Activity {

        private $Name;
        private $Description;
        private $BeginDate;
        private $CoverPicture;
        private $db;
        private $id;

        public function __construct($id=0){
            $CI=&get_instance();
            $results=$CI->crud_model->db_get_activity($id);
            $this->id = $id;
            $this->Name = $result['Name'];
            $this->Description = $result['Description'];
            $this->BeginDate = $result['BeginDate'];
            $this->CoverPicture = $result['CoverPicture'];
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
        public function Description(){ return $this->Description;}
        public function BeginDate(){ return $this->BeginDate;}
        public function CoverPicture(){ return $this->CoverPicture;}
        public function id(){ return $this->id;}

        public function setName($value){ $this->Name = $value;}
        public function setDescription($value){ $this->Description = $value;}
        public function setBeginDate($value){ $this->BeginDate = $value;}
        public function setCoverPicture($value){ $this->CoverPicture = $value;}

        /* error handling for Accessing the wrong way to private properties of object*/
        public function __set($name, $value){
            echo "You are trying to direct set property ".$name." to value ".$value." and thats not possible use set".$name."(\$value) method.";
            echo "<br/> At line ".__LINE__." in file ".__FILE__;
        }
        public function __get($name){
            echo "You are trying to direct access property ".$name."and thats not possible. Use ".$name."() method";
            echo "<br/> At line ".__LINE__." in file ".__FILE__;
        }

        /* Find all direct sons of Activity
         *  Return value is 2D array.
         *  Each row has Name, idActivity, Depth fields. (Depth is always 1).
         */
        public function getSons(){
            $stmt = $this->db->prepare("call sonOfActivity(:id)");
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        /* Finding Activity on id and returns all relevant informations.
         * Return value is array if Activity exists, otherwise return String.
         * Array has Name, Description, BeginDate, CoverPicture, Active
         */
        public function getFullInfo(){
            $stmt = $this->db->prepare("SELECT Name, Description, BeginDate, CoverPicture, Active FROM Activity WHERE idActivity = :id");
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            $stmt->execute();
            if( $stmt->rowCount()  == 0){
                return "Activity ne postoji! ";
            }
            else{
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result;
            }
        }
        /* Find all Activity holders (as previous Activity is based on $id)
         * Return value is array which have all user_id of ActivityHolders
         * Array is numeric.
         */
        public function getHolders(){
            $stmt = $this->db->prepare("SELECT user_id from ActivityHolder where idActivity = :id");
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            $stmt->execute();
            if($stmt->rowCount() == 0)
                return "Activity nema nijednog holdera.";
            else{
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $ret = array();
                foreach($result as $i){
                    array_push($ret, $i['user_id']);
                }
                return $ret;
            }
        }
        /* Find all Activity participants
         * Return value is array which have all user_id of Activity Participants, or return string value if there is no participants
         * Array is numeric
         */
        public function getParticipants(){
            $stmt = $this->db->prepare("SELECT user_id from ActivityParticipant where idActivity = :id");
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            $stmt->execute();
            if($stmt->rowCount() == 0)
                return "Activity nema nijednog ucesnika.";
            else{
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $ret = array();
                foreach($result as $i){
                    array_push($ret, $i['user_id']);
                }
                return $ret;
            }
        }
        /* Find all Activity events
         * Return value is array which have all idEvents of Events that belong to given Activity, or return string value if there is no participants
         * Array is numeric
         */
        public function getEvents(){
            $db = new PDO("mysql:localhost;dbname=manthanodb;charset=utf8","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $stmt = $this->db->prepare("SELECT idEvent from ActivityContains where idActivity = :id");
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            $stmt->execute();
            if($stmt->rowCount() == 0)
                return "Activity nema nijedan Event.";
            else{
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $ret = array();
                foreach($result as $i){
                    array_push($ret, $i['idEvent']);
                }
                return $ret;
            }
        }
        /* Updates Activity.
         * Simply applies object changes to database;
         * Return true if successfull or false if not.
         * */
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
        /* Adding participant to current Activity
         * INPUT: ID of user
         * Return boolean indicator of how operation went.
         * */
        public function addParticipant($idUser){
            $stmt = $this->db->prepare("INSERT INTO ActivityParticipant(user_id, idActivity) VALUES(:uid, :aid)");
            $stmt->bindParam(":uid", $idUser, PDO::PARAM_INT);
            $stmt->bindParam(":aid", $this->id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() ? true : false;
        }
        /* Adding holder to current Activity
         * INPUT: ID of user
         * Return boolean indicator of how operation went.
         * */
        public function addHolder($idUser){
            $stmt = $this->db->prepare("INSERT INTO ActivityHolder(user_id, idActivity) VALUES(:uid, :aid)");
            $stmt->bindParam(":uid", $idUser, PDO::PARAM_INT);
            $stmt->bindParam(":aid", $this->id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() ? true : false;
        }
        /* Deleting holder from current Activity
         * INPUT: ID of user
         * Return boolean indicator of how operation went.
         * */
        public function removeHolder($idUser){
            $stmt = $this->db->prepare("DELETE FROM ActivityHolder WHERE user_id = :uid and idActivity = :aid");
            $stmt->bindParam(":uid", $idUser, PDO::PARAM_INT);
            $stmt->bindParam(":aid", $this->id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() ? true : false;
        }
        /* Deleting participant from current Activity
         * INPUT: ID of user
         * Return boolean indicator of how operation went.
         * */
        public function removeParticipant($idUser){
            $stmt = $this->db->prepare("DELETE FROM ActivityParticipant WHERE user_id = :uid and idActivity = :aid");
            $stmt->bindParam(":uid", $idUser, PDO::PARAM_INT);
            $stmt->bindParam(":aid", $this->id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() ? true : false;
        }

        /* Static methods */

        /* Delete Activity
         * No return value;
         */
        static public function deleteActivity($id, $db){
            /* upotrebiti proceduru ovde  */
            $stmt = $db->prepare("call deleteActivity(:id)");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() ? true : false;
        }

    }