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
                                "username" => $user->username(),
                                "Proffession" => $user->Proffession(),
                                "Mail" => $user->Mail(),
                                "School" => $user->School(),
                                "status" => $user->status(),
                                "www" => $user->www(),
                                "ProfilePicture" => $user->ProfilePicture(),

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
                        $status=404; break; //Creating users NOT SUPPORTED
                    /* Processing PUT - UPDATE request */
                    case 'put':
                        $ac_data=json_decode(file_get_contents('php://input'));
                        //var_dump($ac_data);
                        /* Checking user privileges, need to be implemented.
                         * Is one of activity holders or is it admin?
                         * $ac_data->id is id of activity.
                         */
                        if($this->session->userdata('user_id')==$id || is_admin()){

                            $user=new Users($id);
                            $user->setName($ac_data->Name);
                            $user->setSurname($ac_data->Surname);
                            //$user->setMail($ac_data->Mail);
                            //$user->setUsername($ac_data->username);
                            $user->setSchool($ac_data->School);
                            $user->setProffession($ac_data->Proffession);
                            $user->setwww($ac_data->www);
                            //TO-DO srediti uploadovanje slike
                            //$user->setProfilePicture($ac_data->ProfilePicture);
                            $ind=$user->update();

                            if($ind){
                                $status=201;
                                $data = array(
                                    "message" => "User updated succesfuly"
                                );
                                $data=json_encode($data);
                            }
                            else{
                                $status=400;
                                $error_description=array(
                                    "message" => "Error updating user!"
                                );
                                $data=json_encode($error_description);
                            }
                        }
                        else{
                            /*Unauthorized*/
                            $status = 401;
                        }
                        break;

                    case 'delete':
                        /* Checking user privileges, need to be implemented.
                         * Is one of activity holders or is it admin?
                         * $ac_data->id is id of activity.
                         *  */
                        if($this->session->userdata('user_id') && is_admin()){
                            if(User::delete($id)){
                                $status=200;
                            }
                            else{
                                $status=404;
                                $error_description=array(
                                    "message"=>"Korisnik nije obrisan"
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
        public function users(){
            $data = "";
            $status = 200;
            $db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $db->exec("use manthanodb;");
            try{
                switch($this->method){
                    case 'get':
                        $temp = Users::allUsers($db);
                        $data = json_encode($temp);
                        $status=200;
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