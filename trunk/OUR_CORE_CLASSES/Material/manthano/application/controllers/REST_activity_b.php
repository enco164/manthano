<?php
    class REST_activity_b extends MY_Controller {
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

    public function place($from, $to){
        $db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $db->exec("use manthanodb;");
        $data = "";
        $status = 200;
        try{
            switch($this->method){
                case 'get':
                    break;
                case 'post':
                    break;
                case 'put':
                    if(is_admin()){
                        Activity::moveActivity($from, $to, $db);
                    }
                    else{
                        /*Unauthorized*/
                        $status = 401;
                    }
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
    public function activity($id){
        if(!in_array($this->method, $this->supported_methods)){
            //TODO Error report
        }
        $db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $db->exec("use manthanodb;");
        /* setting status and data which will be returned */
        $status=200;
        $data="";

        /* Big switch which REST service actually is */
        try{
            switch($this->method){
                /* processing GET - READ request */
                case 'get':
                        $activity = new Activity($id, $db);
                        if(!$activity->exists()){
                            $status=404;
                            $error_description=array( "message"=>"Activity doesn't exist!");
                            $data=json_encode($error_description);
                        }
                        else{

                            $activity_sons = $activity->getSons();
                            $activity_events = $activity->getEvents();
                            /* setting package that will be sent to client */
                            $arr = array(
                                "id" => $activity->id(),
                                "name" => $activity->Name() ,
                                "description" => $activity->Description(),
                                "date" => $activity->BeginDate(),
                                "cover" => $activity->CoverPicture(),
                                "sons" => $activity_sons,
                                "events" => $activity_events,
                                "path" => $activity->getPath(),
                                "is_holder" => Activity::isHolder($this->session->userdata('user_id'), $id, $db),
                                "is_participant" => Activity::isParticipant($this->session->userdata('user_id'), $id, $db),
                                "participants" => $activity->getParticipants(),
                                "holders" => $activity->getHolders(),
                            );
                            $data=json_encode($arr);
                            $etag = md5($activity);
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
    public function activities(){
        $data = "";
        $status = 200;
        $db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $db->exec("use manthanodb;");
        try{
            switch($this->method){
                case 'get':
                    $data = json_encode(Activity::allActivities($db));
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
    public function participant($id){
       $data = "";
       $status = 200;
        $db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $db->exec("use manthanodb;");
        try{
            switch($this->method){
                case 'put':
                    if(Activity::addParticipant($this->session->userdata('user_id'),$id, $db)){
                        $status = 200;
                    }
                    else{
                        $status = 404;
                    }
                    break;
                case 'delete':
                    if(Activity::removeParticipant($this->session->userdata('user_id'),$id, $db)){
                        $status = 200;
                    }
                    else{
                        $status = 404;
                    }
                    break;
                default:
                    $data = array(
                        "message" => "Method is not supported"
                    );
                    $status = 406;
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
    public function holder($idActivity, $user_id){
        $db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $db->exec("use manthanodb;");
        $data = "";
        $status = 200;
        try{
            switch($this->method){
                case 'get':
                    $data = json_encode(Activity::getHoldersStatic($idActivity,$db));
                    $status = 200;
                    break;
                    break;
                case 'post':
                    if(Activity::isHolder($this->session->userdata('user_id'), $idActivity, $db)){
                        if(Activity::addHolder($user_id, $idActivity, $db)){
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
                        if(Activity::removeHolder($user_id, $idActivity, $db)){
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
    public function nonholder($idActivity){
        $db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $db->exec("use manthanodb;");
        $data = "";
        $status = 200;
        try{
            switch($this->method){
                case 'get':
                    $data = json_encode(Activity::getNonHolders($idActivity,$db));
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


    public function event($id){
        $data = "";
        $status = 200;
        $db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $db->exec("use manthanodb;");
        try{
            switch($this->method){
                case 'get':
                    break;
                case 'post':
                    $event_data=json_decode(file_get_contents('php://input'));
                    /* Checking user privileges, need to be implemented.
                     * Is one of activity holders or is it admin?
                     * $ac_data->id is id of activity.
                     */
                    if(Event::isHolderStatic($this->session->userdata('user_id'), $id, $db)){
                        $ind = Activity::addEvent($event_data->idEvent, $event_data->idActivity, $db);
                        if($ind){
                            $status=201;
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
                        $data = json_encode(Event::isHolderStatic($this->session->userdata('user_id'), $id, $db));
                        $status = 401;
                    }
                    break;
                case 'put':
                    break;
                case 'delete':
                    /* Checking user privileges, need to be implemented.
                        * Is one of activity holders or is it admin?
                        * $ac_data->id is id of activity.
                        *  */
                    $event_data=json_decode(file_get_contents('php://input'));
                    if(Event::isHolderStatic($this->session->userdata('user_id'), $id, $db)){
                        if(Activity::removeEvent($event_data->idEvent, $event_data->idActivity, $db)){
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

}

