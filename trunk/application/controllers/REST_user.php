<?php
    class REST_user extends MY_Controller {


        public function __construct(){
            parent::__construct();
            $this->load->helper('text');
            $this->method=strtolower($_SERVER['REQUEST_METHOD']);
            $this->supported_methods=array('get','post', 'put', 'delete');
        }

        private $supported_methods;
        /* converting method name to lower letters for easier comapring */
        private $method;

        public function index(){
            echo "hello!";
        }

        public function personal_data($id){
            if(!in_array($this->method, $this->supported_methods)){
                //TODO Error report
            }
            //$db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            //$db->exec("use manthanodb;");
            /* setting status and data which will be returned */
            $status=200;
            $data="";

            /* Big switch which REST service actually is */
            try{
                switch($this->method){
                    /* processing GET - READ request */
                    case 'get':
                        $user = new Users($id);
                        if(!$user->exists()){
                            $status=404;
                            $error_description=array( "message"=>"User doesn't exist!");
                            $data=json_encode($error_description);
                        }
                        else{

                            /* setting package that will be sent to client */
                            $arr = array(
                                "id" => $user->id(),
                                "name" => $user->Name() ,
                                "surname" => $user->Surname(),
                                "Proffession" => $user->Proffession(),
                                "School" => $user->School(),
                                "status" => $user->status(),
                                "www" => $user->www(),

                            );
                            $data=json_encode($arr);
                            $etag = md5('user');
                            header('Etag:'.$etag);
                            header("Expires: -1");
                            $status=200;
                        }
                        break;

                    /* Processing POST - CREATE request*/
                    case 'post':
                        $ac_data=json_decode(file_get_contents('php://input'));
                        /* Checking user privileges, need to be implemented.
                         * Is one of activity holders or is it admin?
                         * $ac_data->id is id of activity.
                         */
                        if(Activity::isHolder($this->session->userdata('user_id'), $id, $db)){
                            $ind = Activity::addActivity($db, $ac_data->id, $ac_data->Name, $ac_data->Description, $ac_data->Date, $ac_data->Cover );
                            if($ind){
                                $status=201;
                                $data = array(
                                    "message" => "Activity added"
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
                    /* Processing PUT - UPDATE request */
                    case 'put':
                        /* reading data */
                        $ac_data=json_decode(file_get_contents('php://input'));
                        /* Checking user privileges, need to be implemented.
                         * Is one of activity holders or is it admin?
                         * $ac_data->id is id of activity.
                         */
                        if(Activity::isHolder($this->session->userdata('user_id'), $id, $db)){
                            $temp = new Activity($ac_data->id,$db);
                            $etag = md5($temp);
                            /* checking if resource was modified */
                            if($ac_data->Etag == $etag){
                                $temp->setName($ac_data->Name);
                                $temp->setDescription($ac_data->Description);
                                $temp->setBeginDate($ac_data->Date);
                                $temp->setCoverPicture($ac_data->Cover);
                                /* updating and proper action if update is ok or not*/
                                if($temp->update()){
                                    /* ok */
                                    $status = 200;
                                }
                                else{
                                    /*Bad request*/
                                    $status=400;
                                    $error_description=array(
                                        "message"=>"los zahtev"
                                    );
                                    $data=json_encode($error_description);
                                }
                            }
                            else{
                                /* conflict */
                                $status = 409;
                                $data = json_encode(array("message" => "menjano vec!", "etagreturn"=>$etag));
                            }
                        }
                        else{
                            /*Unauthorized*/
                            $status = 401;
                        }
                        break;
                    /* Processing DELETE request */
                    case 'delete':
                        /* Checking user privileges, need to be implemented.
                         * Is one of activity holders or is it admin?
                         * $ac_data->id is id of activity.
                         *  */
                        if(Activity::isHolder($this->session->userdata('user_id'), $id, $db)){
                            if(Activity::deleteActivity($id, $db)){
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

            /* Using pecl_http Apache extension to make HTTP response packets
             * Simply setting status, contentType, header and data.
             * */
            /*
            $response=new HTTPResponse();
            $response::status($status);
            $response::setContentType("application/json");
            $response::setHeader("Connection:close");
            if(isset($data))
                $response::setData($data);
            $response::send();
            */
            header("HTTP/1.1 ".$status);
            header("Content-Type: application/json");
            if(isset($data))
                echo $data;

        }

    }