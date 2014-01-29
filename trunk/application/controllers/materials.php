<?php
    class Materials extends MY_Controller {

        public $vehicle_brands=Array();
        public $vehicle_type="car";
        public $locations=Array();
        public $latest_news=array();

        public function __construct(){
            parent::__construct();
            $this->load->helper('text');

        }

        public function index(){


            $kurs = new Material(200);
            echo $kurs->Name();
            $kurs->Name = "Table";
            echo $kurs->update();



            /*$this->load->view('base/header');
            $this->load->view('home');
            $this->load->view('base/footer');*/
        }
		
		 public function material_data($id){
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
                        $material = new Materials($id);
                        if(!$material->exists()){
                            $status=404;
                            $error_description=array( "message"=>"Material doesn't exist!");
                            $data=json_encode($error_description);
                        }
                        else{

                            /* setting package that will be sent to client */
                            $arr = array(
                                "id" => $material->id(),
                                "name" => $material->Name() ,
                                "URI" => $material->URI(),
                                "Type" => $material->Type(),
                                "Date" => $material->Date(),
                                

                            );
                            $data=json_encode($arr);
                            $etag = md5('material');
                            header('Etag:'.$etag);
                            header("Expires: -1");
                            $status=200;
                        }
                        break;

                    /* Processing POST - CREATE request*/
                    case 'post':
                        $status=404; break; //Creating materials NOT SUPPORTED
                    /* Processing PUT - UPDATE request */
                    case 'put':
                        $ac_data=json_decode(file_get_contents('php://input'));
                        
                        if($this->session->materialdata('material_id')==$id ){

                            $material=new materials($id);
                            $material->setName($ac_data->Name);
                            $material->setURI($ac_data->URI);
                            $material->setType($ac_data->Type);
                            $material->setDate($ac_data->Date);
                            $ind=$material->update();

                            if($ind){
                                $status=201;
                                $data = array(
                                    "message" => "material updated succesfuly"
                                );
                                $data=json_encode($data);
                            }
                            else{
                                $status=400;
                                $error_description=array(
                                    "message" => "Error updating material!"
                                );
                                $data=json_encode($error_description);
                            }
                        }
                        
                        break;

                    case 'delete':
                        /* Checking material privileges, need to be implemented.
                         * Is one of activity holders or is it admin?
                         * $ac_data->id is id of activity.
                         *  */
                        if($this->session->materialdata('material_id') ){
                            if(material::delete($id)){
                                $status=200;
                            }
                            else{
                                $status=404;
                                $error_description=array(
                                    "message"=>"Material is not deleted"
                                );

                                $data=json_encode($error_description);
                            }
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
        public function materials(){
            $data = "";
            $status = 200;
            $db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $db->exec("use manthanodb;");
            try{
                switch($this->method){
                    case 'get':
                        $temp = materials::allmaterials($db);
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

