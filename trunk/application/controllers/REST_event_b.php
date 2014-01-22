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
                            $data = json_encode($data);
                        }
                        else{
                            $status = 404;
                        }
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