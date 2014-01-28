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
        private $exist;

        public function __construct($id, $db){
            $this->db = $db;
            $stmt = $db->prepare("SELECT Name, Description, BeginDate, CoverPicture FROM Activity WHERE idActivity = :id");
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
            $this->exist = null;
        }


        public function __toString(){
            return $this->Name.$this->Description.$this->BeginDate.$this->CoverPicture;
        }
        /* getters and setters*/
        public function Name(){ return $this->Name;}
        public function Description(){ return $this->Description;}
        public function BeginDate(){ return $this->BeginDate;}
        public function CoverPicture(){ return $this->CoverPicture;}
        public function id(){ return $this->id;}
        public function exists(){
            return $this->exist;
        }

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
        /* getting path from top of tree to chosen activity */
        public function getPath(){
            $stmt = $this->db->prepare("call pathToActivity(:id)");
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        /* Find all direct sons of Activity
         *  Return value is array with id of Activities.
         *
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
         * Return value is array which have all Name, Surname, user_id of ActivityHolders
         * Array is numeric.
         */
        public function getHolders(){
            $stmt = $this->db->prepare("SELECT u.Name, u.Surname, u.user_id from ActivityHolder ap join User u on ap.user_id = u.user_id where idActivity = :id order by Name Desc, Surname Desc");
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            $stmt->execute();
            if($stmt->rowCount() == 0)
                return "Activity nema nijednog holdera.";
            else{
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $ret = array();
                foreach($result as $i){
                    array_push($ret, $i);
                }
                return $ret;
            }
        }
        /* Find all Activity participants
         * Return value is array which have all Name, Surname, user_id of Activity Participants, or return string value if there is no participants
         * Array is numeric
         */
        public function getParticipants(){
            $stmt = $this->db->prepare("SELECT u.Name, u.Surname, u.user_id from ActivityParticipant ap join User u on ap.user_id = u.user_id  where idActivity = :id order by Name Desc, Surname Desc");
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            $stmt->execute();
            if($stmt->rowCount() == 0)
                return "Activity nema nijednog ucesnika.";
            else{
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $ret = array();
                foreach($result as $i){
                    array_push($ret, $i);
                }
                return $ret;
            }
        }
        /* Find all Activity events
         * Return value is array which have all idEvents of Events that belong to given Activity, or return string value if there is no participants
         * Array is numeric
         */
        public function getEvents(){
            $stmt = $this->db->prepare("SELECT e.Name, ac.idEvent from ActivityContains ac join Event e on e.idEvent = ac.idEvent where idActivity = :id");
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

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
        /* Static methods */
        /* Deleting holder from current Activity
         * INPUT: ID of user
         * Return boolean indicator of how operation went.
         * */
        static public function removeHolder($idUser, $idActivity, $db){
            $stmt = $db->prepare("DELETE FROM ActivityHolder WHERE user_id = :uid and idActivity = :aid");
            $stmt->bindParam(":uid", $idUser, PDO::PARAM_INT);
            $stmt->bindParam(":aid", $idActivity, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() ? true : false;
        }
        /* Deleting participant from current Activity
         * INPUT: ID of user
         * Return boolean indicator of how operation went.
         * */
        static public function removeParticipant($idUser, $idActivity, $db){
            $stmt = $db->prepare("DELETE FROM ActivityParticipant WHERE user_id = :uid and idActivity = :aid");
            $stmt->bindParam(":uid", $idUser, PDO::PARAM_INT);
            $stmt->bindParam(":aid",$idActivity, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() ? true : false;
        }
        /*
         * Static method for adding new Activity
         * */
        static public function addActivity($db, $id, $name, $description, $start, $cover){
            $stmt = $db->prepare("call addActivity(:id, :name, :desc, :start, :cover, 1);");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":desc", $description, PDO::PARAM_STR);
            $stmt->bindParam(":start", $start, PDO::PARAM_STR);
            $stmt->bindParam(":cover", $cover, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount() ? true : false;
        }
        /* Delete Activity
         * No return value;
         */
        static public function deleteActivity($id, $db){
            $stmt = $db->prepare("call deleteActivity(:id)");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() ? false : true;
        }

        /* Adding participant to current Activity
       * INPUT: ID of user
       * Return boolean indicator of how operation went.
       * */
        static public function addParticipant($idUser, $idActivity, $db){
            $stmt = $db->prepare("INSERT INTO ActivityParticipant(user_id, idActivity) VALUES(:uid, :aid)");
            $stmt->bindParam(":uid", $idUser, PDO::PARAM_INT);
            $stmt->bindParam(":aid", $idActivity, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() ? true : false;
        }
        /* Adding holder to current Activity
         * INPUT: ID of user
         * Return boolean indicator of how operation went.
         * */
        static public function addHolder($idUser, $idActivity, $db){
            $stmt = $db->prepare("INSERT INTO ActivityHolder(user_id, idActivity) VALUES(:uid, :aid)");
            $stmt->bindParam(":uid", $idUser, PDO::PARAM_INT);
            $stmt->bindParam(":aid", $idActivity, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() ? true : false;
        }

//        static public function removeHolder($idUser, $idActivity, $db){
//            $stmt = $db->prepare("DELETE FROM activityHolder WHERE idUser = :uid and $idActivity = :aid");
//            $stmt->bindParam(":uid", $idUser, PDO::PARAM_INT);
//            $stmt->bindParam(":aid", $idActivity, PDO::PARAM_INT);
//            $stmt->execute();
//            return $stmt->rowCount() ? true : false;
//        }

        static public function isHolder($idUser, $idActivity, $db){
            $stmt = $db->prepare("SELECT * FROM activityHolder WHERE user_id = :uid and idActivity = :aid");
            $stmt->bindParam(":uid", $idUser, PDO::PARAM_INT);
            $stmt->bindParam(":aid", $idActivity, PDO::PARAM_INT);
            $stmt->execute();
            return ($stmt->rowCount() ? true : false) or is_admin();
        }

        static public function isParticipant($idUser, $idActivity, $db){
            $stmt = $db->prepare("SELECT * FROM activityParticipant WHERE user_id = :uid and idActivity = :aid");
            $stmt->bindParam(":uid", $idUser, PDO::PARAM_INT);
            $stmt->bindParam(":aid", $idActivity, PDO::PARAM_INT);
            $stmt->execute();
            return ($stmt->rowCount() ? true : false);
        }

        static public function allActivities($db){
            $stmt = $db->prepare("call treeFormated();");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        static public function addEvent($idEvent, $idActivity, $db){
            $stmt = $db->prepare("INSERT INTO ActivityContains(idEvent, idActivity) values (:ide, :ida)");
            $stmt->bindParam(":ide", $idEvent, PDO::PARAM_INT);
            $stmt->bindParam(":ida", $idActivity, PDO::PARAM_INT);
            $stmt->execute();
            return ($stmt->rowCount() ? true : false);
        }

        static public function Event($idEvent, $idActivity, $db){
            $stmt = $db->prepare("DELETE FROM ActivityContains WHERE idEvent = :ide and idActivity = :ida");
            $stmt->bindParam(":ide", $idEvent, PDO::PARAM_INT);
            $stmt->bindParam(":ida", $idActivity, PDO::PARAM_INT);
            $stmt->execute();
            return ($stmt->rowCount() ? true : false);
        }

        static public function moveActivity($from, $to, $db){
            $stmt = $db->prepare("call moveActivity(:from, :to);");
            $stmt->bindParam(":from", $from, PDO::PARAM_INT);
            $stmt->bindParam(":to", $to, PDO::PARAM_INT);
            $stmt->execute();
            return ($stmt->rowCount() ? true : false);
        }

        static public function getNonHolders($idActivity, $db){
            $stmt = $db->prepare("Select distinct concat(u.name, ' ', u.surname) as nameu, u.user_id, username
                                    from user u
                                    where not exists (select * from activityHolder ah where ah.idActivity = :aid and ah.user_id = u.user_id)
                                    order by nameu");
            $stmt->bindParam(":aid", $idActivity, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        static public function getHoldersStatic($idActivity, $db){
            $stmt = $db->prepare("Select distinct concat(u.name, ' ', u.surname) as nameu, u.user_id, username
                                    from user u join activityholder ah on u.user_id = ah.user_id
                                    where ah.idActivity = :aid
                                    order by nameu");
            $stmt->bindParam(":aid", $idActivity, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        static public function existsActivity($idActivity, $db){
            $stmt = $db->prepare("select * from Activity where idActivity = :aid");
            $stmt->bindParam(":aid", $idActivity, PDO::PARAM_INT);
            $stmt->execute();
            return ($stmt->rowCount() ? true : false);
        }
    }