<?php
class Materials extends MY_Controller {

    public $vehicle_brands=Array();
    public $vehicle_type="car";
    public $locations=Array();
    public $latest_news=array();

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


        $mat = new Material(200);
        echo $mat->Name();
        $mat->Name = "Table";
        echo $mat->update();



        /*$this->load->view('base/header');
        $this->load->view('home');
        $this->load->view('base/footer');*/
    }

    public function material_data($idMaterial){
        if(!in_array($this->method, $this->supported_methods)){
            //TODO Error report
        }
        $db = new PDO("mysql:localhost;dbname=manthanodb;charset=utf8","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $db->exec("use manthanodb;");
        /* setting status and data which will be returned */
        $status=200;
        $data="";

        /* Big switch which REST service actually is */
        try{
            switch($this->method){
                /* processing GET - READ request */
                case 'get':
                    $material = new Material($idMaterial);
                    if(!$material->exists()){
                        $status=404;
                        $error_description=array( "message"=>"Material doesn't exist!");
                        $data=json_encode($error_description);
                    }
                    else{

                        /* setting package that will be sent to client */
                        $arr = array(
                            "idMaterial" => $material->idMaterial(),
                            "name" => $material->Name() ,
                            "URI" => $material->URI(),
                            "Type" => $material->Type(),
                            "Date" => $material->Date(),
                          "Owner"=>$material->getOwner($material->idMaterial()),

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

                    if( $this->session->userData('user_id')==$ac_data->OwnerID){

                        $material=new materials($idMaterial);
                        $material->setName($ac_data->Name);
                        $material->setURI($ac_data->URI);
                        $material->setType($ac_data->Type);
                        $material->setDate($ac_data->Date);
                        $material->setOwner($this->session->userdata('user_id'));
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
                        if(material::delete($idMaterial)){
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
        $db = new PDO("mysql:localhost;dbname=manthanodb;charset=utf8","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
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