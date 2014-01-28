<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 1/5/14
 * Time: 6:26 PM
 */
    class REST_event_b extends MY_Controller {
        private $supported_methods;
        /* converting method name to lower letters for easier comapring */
        private $method;

        public function __construct(){
            parent::__construct();
            $this->load->helper('text');
            $this->method=strtolower($_SERVER['REQUEST_METHOD']);
            $this->supported_methods=array('get','post', 'put', 'delete');


        }
        public function index(){
            echo "hello!";
        }
        public function event($id){
            $data = "";
            $status = 200;
            $db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $db->exec("use manthanodb;");
            try{
                switch($this->method){
                    case 'get':
                        $event = new Event($id, $db);
                            if($event->exists()){
                                $status = 200;
                                $data = array(
                                    "Name" => $event->Name(),
                                    "Description" => $event->Description(),
                                    "Venue" => $event->Venue(),
                                    "Date" => $event->Date(),
                                    "Time" => $event->Time(),
                                    "materials" => $event->getMaterials(),
                                    "holders" => $event->getHolders(),
                                    "is_holder" => $event->isHolder($this->session->userdata('user_id')),
                                    "parents" => $event->getParentActivities()
                                );
                                $etag = md5($event);
                                $data = json_encode($data);
                                header('Etag:'.$etag);
                                header("Expires: -1");
                                $status=200;
                        }
                        else{
                            $status = 404;
                        }
                        break;
                    case 'post':
                        $event_data=json_decode(file_get_contents('php://input'));
                        /* Checking user privileges, need to be implemented.
                         * Is one of activity holders or is it admin?
                         * $ac_data->id is id of activity.
                         */
                        print_r($event_data);
                        if(Event::isHolderStatic($this->session->userdata('user_id'), $id, $db)){
                            $ind = Event::addEvent($id, $db, $event_data->Name, $event_data->Description, $event_data->Venue, $event_data->Date, $event_data->Time);
                            if($ind){
                                $status=201;
                                Event::addHolder($ind, $this->session->userdata('user_id'), $db);
                                $data = array(
                                    "message" => "Event added"
                                );
                                $data=json_encode($data);
                            }
                            else{
                                $status=400;
                                $error_description=array(
                                    "message" => "Resource wasnt found!"
                                );
                                $data=json_encode($error_description);
                            }
                        }
                        else{
                            /*Unauthorized*/
                            $status = 401;
                        }
                        break;
                    case 'put':
                        $ev_data = json_decode(file_get_contents('php://input'));
                        if(Event::isHolderStatic($this->session->userdata('user_id'), $id, $db))
                        {
                            $temp = new Event($id, $db);
                            $etag = md5($temp);
                            if($ev_data->Etag == $etag)
                            {
                                $temp->setName($ev_data->Name);
                                $temp->setDescription($ev_data->Description);
                                $temp->setDate($ev_data->Date);
                                $temp->setVenue($ev_data->Venue);
                                $temp->setTime($ev_data->Time);
                                if($temp->upload())
                                {
                                    $status = 200;
                                }
                                else
                                {
                                    $status = 400;
                                    $error_description=array("Event nije nađen!");
                                    $data=json_encode($error_description);
                                }
                            }
                            else
                            {
                                $status = 409;
                                $data = json_encode(array("message" => "menjano vec!", "etagreturn"=>$etag));
                            }
                        }
                        else
                        {
                            /*Unauthorized*/
                            $status = 401;
                        }
                        break;
                    case 'delete':
                        if(Event::isHolderStatic($this->session->userdata('user_id'), $id, $db))
                        {
                            $ind = Event::deleteEvent($id, $db);
                            if($ind)
                            {
                                $status = 200;
                                $data = array(
                                    "message" => "Event was successfully deleted!"
                                );
                                $data=json_encode($data);
                            }
                            else
                            {
                                $status=400;
                                $error_description=array(
                                    "message" => "Resource wasnt found!"
                                );
                                $data=json_encode($error_description);
                            }

                        }
                        else
                        {
                            /*Unauthorized*/
                            $status = 401;
                        }
                        break;
                }
            }catch(Exception $e){
                $status="500";
                $error_description=array(
                    "blah" => $e->getTraceAsString(),
                    "message"=>"Server error!"
                );

                $data=json_encode($error_description);

            }
            header("HTTP/1.1 ".$status);
            header("Content-Type: application/json");
            if(isset($data))
                echo $data;
        }
        public function eventsshort($id){
            $data = "";
            $status = 200;
            $db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $db->exec("use manthanodb;");
            try{
                switch($this->method){
                    case 'get':
                        $data = json_encode(Event::listExistingEventsForUser($id, $this->session->userdata('user_id'), $db));
                        break;
                    case 'post':
                        break;
                    case 'put':
                        break;
                    case 'delete':
                        break;
                }
            }catch(Exception $e){
                $status="500";
                $error_description=array(
                    "blah" => $e->getMessage(),
                    "message"=>"Server error!"
                );

                $data=json_encode($error_description);

            }
            header("HTTP/1.1 ".$status);
            header("Content-Type: application/json");
            if(isset($data))
                echo $data;
        }

        public function holder($idEvent, $user_id){
            $db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $db->exec("use manthanodb;");
            $data = "";
            $status = 200;
            try{
                switch($this->method){
                    case 'get':
                        $data = json_encode(Event::getHoldersStatic($idEvent,$db));
                        $status = 200;
                        break;
                        break;
                    case 'post':
                        if(Event::isHolderStatic($this->session->userdata('user_id'), $idEvent, $db)){
                            if(Event::addHolder($idEvent, $user_id, $db)){
                                $status=201;
                                $data = array(
                                    "message" => "Activity holder added"
                                );
                                $data=json_encode($data);
                            }
                            else{
                                $status=400;
                                $error_description=array(
                                    "message" => "Resource wasnt found!"
                                );
                                $data=json_encode($error_description);
                            }
                        }
                        else{
                            /*Unauthorized*/
                            $status = 401;
                        }
                        break;
                    case 'put':
                        break;
                    case 'delete':
                        if(Activity::isHolder($this->session->userdata('user_id'), $user_id, $db)){
                            if(Event::removeHolder($user_id, $idEvent, $db)){
                                $status=200;
                            }
                            else{
                                $status=404;
                                $error_description=array(
                                    "message"=>"resurs nije pronadjen"
                                );

                                $data=json_encode($error_description);
                            }
                        }
                        else{
                            /*Unauthorized*/
                            $status = 401;
                        }
                        break;
                }
            }catch(Exception $e){
                $status="500";
                $error_description=array(
                    "blah" => $e->getMessage(),
                    "message"=>"Server error!"
                );

                $data=json_encode($error_description);

            }
            header("HTTP/1.1 ".$status);
            header("Content-Type: application/json");
            if(isset($data))
                echo $data;
        }
        public function nonholder($idEvent){
            $db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $db->exec("use manthanodb;");
            $data = "";
            $status = 200;
            try{
                switch($this->method){
                    case 'get':
                        $data = json_encode(Event::getNonHolders($idEvent,$db));
                        $status = 200;
                        break;
                    case 'post':
                        break;
                    case 'put':
                        break;
                    case 'delete':
                        break;
                }
            }catch(Exception $e){
                $status="500";
                $error_description=array(
                    "blah" => $e->getMessage(),
                    "message"=>"Server error!"
                );

                $data=json_encode($error_description);

            }
            header("HTTP/1.1 ".$status);
            header("Content-Type: application/json");
            if(isset($data))
                echo $data;
        }


    }