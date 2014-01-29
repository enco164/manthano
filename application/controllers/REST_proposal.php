<?php
/**
 * Created by PhpStorm.
 * User: Jupike
 * Date: 7.1.14.
 * Time: 13.56
 */
class REST_proposal extends MY_Controller
{
    private $supported_methods;
    private $method;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('text');
        /* converting method name to lower letters for easier comapring */
        $this->method=strtolower($_SERVER['REQUEST_METHOD']);
        $this->supported_methods=array('get','post', 'put', 'delete');
    }

    public function index()
    {
        echo "Rest servis za Predloge!";
    }

    public function proposal($id)
    {
        if(!in_array($this->method, $this->supported_methods))
        {
            //TODO Error report
        }

        $db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $db->exec("use manthanodb;");
        /* setting status and data which will be returned */
        $status=400;
        $data="";

        /* Big switch which REST service actually is */
        try
        {
            switch($this->method)
            {
                /* processing GET - READ request */
                case 'get':
                    $proposal = new Proposal($id, $db);
                    if(!$proposal->exists())
                    {
                        $status=404;
                        $error_description=array( "message"=>"Predlog ne postoji!",
                                                    "error" => "Greška!");
                        $data=json_encode($error_description);
                    }
                    else
                    {
                        $creator = $this->session->userdata('user_id')==$proposal->UserProposed()? true : false;
                        /* setting package that will be sent to client */
                        $user = new Users($proposal->UserProposed());
                        $arr1 = array("user_id" => $proposal->UserProposed(),
                                        "Name" => $user->Name(),
                                        "Surname" => $user->Surname());
                        $arr = array(
                            "id" => $proposal->idProposal(),
                            "user_proposed" => $arr1,
                            "name" => $proposal->Name() ,
                            "description" => $proposal->Description(),
                            "support" => $proposal->getSupport(),
                            "support_count" => $proposal->getSupportCount(),
                            "is_support" => Proposal::is_support($db, $this->session->userdata('user_id'), $proposal->idProposal()),
                            "owners" => $proposal->getOwners($this->session->userdata('user_id')),
                            "owners_count" => $proposal->getOwnerCount(),
                            "is_owner" => Proposal::is_owner($db, $this->session->userdata('user_id'), $proposal->idProposal()),
                            "is_creator" => $creator,
                            "is_admin" => is_admin()
                        );
                        $data=json_encode($arr);
                        $etag = md5($proposal);
                        header('Etag:'.$etag);
                        header("Expires: -1");
                        $status=200;
                    }
                    break;
                case 'post':
                    $pr_data=json_decode(file_get_contents('php://input'));
                    /* Checking user privileges, need to be implemented.
                     * Is one of activity holders or is it admin?
                     * $ac_data->id is id of activity.
                     */
                    if(!isset($pr_data->User))
                        $userId=$this->session->userdata('user_id');
                    else
                        $userId=$pr_data->User;
                        $ind = Proposal::addProposal($db, $userId, $pr_data->Name, $pr_data->Description);
                        if($ind)
                        {
                            $status=201;
                            $data = array(
                                "message" => "Predlog je uspšno dodat!"
                            );
                            $data=json_encode($data);
                        }
                        else
                        {
                            $status=400;
                            $error_description=array(
                                "message" => "Predlog nije dodat!",
                                "error" => "Greška na serveru!"
                            );
                            $data=json_encode($error_description);
                        }
                    break;
                case 'put':
                    $proposal = new Proposal($id, $db);
                    $pr_data=json_decode(file_get_contents('php://input'));

                    if(!$proposal->exists())
                    {
                        $status=404;
                        $error_description=array( "message"=>"Predlog ne postoji!",
                                                  "error" => "Greška!");
                        $data=json_encode($error_description);
                    }
                    else
                    {

                        $proposal->setUserProposed($pr_data->User);
                        $proposal->setName($pr_data->Name);
                        $proposal->setDescription($pr_data->Description);

                        if($proposal->updateProposal())
                        {
                            $status=200;
                            $data = array(
                                "message" => "Predlog je uspešno izmenjen!"
                            );
                            $data=json_encode($data);
                        }
                    }
                    break;
                case 'delete':
                    if(Proposal::deleteProposal($db, $id))
                    {
                        $status=200;
                        $data = array(
                            "message" => "Predlog je uspešno obrisan!"
                        );
                        $data=json_encode($data);
                    }
                    else
                    {
                        $status=404;
                        $error_description=array( "message"=>"Predlog ne postoji!",
                                                    "error" => "Greška!");

                        $data=json_encode($error_description);
                    }
                    break;
            }
        }
        catch(Exception $e)
        {
            $status="500";
            $error_description=array(
                "blah" => $e->getMessage(),
                "message"=>"Greška na serveru!",
                "error" => "Greška!"
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

    public function proposalsupport($id)
    {
        if(!in_array($this->method, $this->supported_methods))
        {
            //TODO Error report
        }

        $db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $db->exec("use manthanodb;");
        /* setting status and data which will be returned */
        $status=200;
        $data="";

        /* Big switch which REST service actually is */
        try
        {
            switch($this->method)
            {
                /* processing GET - READ request */
                case 'get':
                        if(Proposal::idExists($db, $id))
                        {
                            /* setting package that will be sent to client */
                            $arr = array(
                                "idProposal" => $id,
                                "support" => Proposal::getSupportS($db, $id),
                                "support_count" => Proposal::getSupportCountS($db, $id)
                            );
                            $data=json_encode($arr);
                            $etag = md5(time()."");
                            header('Etag:'.$etag);
                            header("Expires: -1");
                            $status=200;
                        }
                        else
                        {
                            $status=400;
                            $error_description=array(
                                "message" => "Predlog nije pronađen!",
                                "error" => "Greška!"
                            );
                            $data=json_encode($error_description);
                        }
                    break;
                case 'post':
                    $su_data=json_decode(file_get_contents('php://input'));
                    if(!isset($su_data->User))
                        $userId=$this->session->userdata('user_id');
                    else
                        $userId=$su_data->User;

                    if(Proposal::addSupport($db, $userId, $su_data->Proposal))
                    {
                        $status=201;
                        $data = array(
                            "message" => "Podrška je uspešno dodata!"
                        );
                        $data=json_encode($data);
                    }
                    else
                    {
                        $status=400;
                            $error_description=array("message"=>"Predlog ne postoji!",
                                                        "error" => "Greška!");
                        $data=json_encode($error_description);
                    }
                    break;
                case 'put':
                    break;
                case 'delete':
                    /* Checking user privileges, need to be implemented.
                     * Is one of activity holders or is it admin?
                     * $ac_data->id is id of activity.
                     */
                    $su_data=json_decode(file_get_contents('php://input'));
                        if(!isset($su_data->User))
                            $userId=$this->session->userdata('user_id');
                        else
                            $userId=$su_data->User;
                        if($userId==$this->session->userdata('user_id') || is_admin() ||
                            Proposal::is_creator($db, $this->session->userdata('user_id'), $su_data->Proposal))
                        {
                            if(Proposal::deleteSupport($db, $userId, $su_data->Proposal))
                            {
                                $status=200;
                                $data = array(
                                    "message" => "Podrška je uspešno obrisana!"
                                );
                                $data=json_encode($data);
                            }
                            else
                            {
                                $status=404;
                                $error_description=array("message"=>"Podrška ne postoji!",
                                                            "error" => "Greška!" );

                                $data=json_encode($error_description);
                            }
                        }
                    break;
            }
        }
        catch(Exception $e)
        {
            $status="500";
            $error_description=array(
                "blah" => $e->getMessage(),
                "message"=>"Greška na serveru!",
                "error" => "Greška!"
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

    public function proposalowner($id)
    {
        if(!in_array($this->method, $this->supported_methods)){
            //TODO Error report
        }

        $db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $db->exec("use manthanodb;");
        /* setting status and data which will be returned */
        $status=200;
        $data="";

        /* Big switch which REST service actually is */
        try
        {
            switch($this->method)
            {
                /* processing GET - READ request */
                case 'get':
                    if(Proposal::idExists($db, $id))
                    {
                        /* setting package that will be sent to client */
                        $arr = array(
                            "idProposal" => $id,
                            "owners" => Proposal::getOwnersS($db, $id),
                            "owners_count" => Proposal::getOwnerCountS($db, $id)
                        );
                        $data=json_encode($arr);
                        $etag = md5(time()."");
                        header('Etag:'.$etag);
                        header("Expires: -1");
                        $status=200;
                    }
                    else
                    {
                        $status=400;
                        $error_description=array(
                            "message" => "Predlog nije pronađen!",
                            "error" => "Greška!"
                        );
                        $data=json_encode($error_description);
                    }

                    break;
                case 'post':
                    $su_data=json_decode(file_get_contents('php://input'));

                    if(!isset($su_data->UserProposed))
                        $userId=$this->session->userdata('user_id');
                    else
                        $userId=$su_data->UserProposed;

                    if(!isset($su_data->ProposedBy))
                        $propBy=$this->session->userdata('user_id');
                    else
                        $propBy=$su_data->ProposedBy;

                    $ind = Proposal::addOwner($db, $propBy, $su_data->Proposal, $userId);
                    if($ind)
                    {
                        $status=201;
                        $data = array(
                            "message" => "Vlasnik je dodat!"
                        );
                        $data=json_encode($data);
                    }
                    else
                    {
                        $status=400;
                        $error_description=array(
                            "message" => "Predlog nije pronađen!",
                            "error" => "Greška!"
                        );
                        $data=json_encode($error_description);
                    }
                    break;
                case 'put':
                    break;
                case 'delete':
                    /* Checking user privileges, need to be implemented.
                     * Is one of activity holders or is it admin?
                     * $ac_data->id is id of activity.
                     */
                    $su_data=json_decode(file_get_contents('php://input'));

                    if(!isset($su_data->UserProposed))
                        $userId=$this->session->userdata('user_id');
                    else
                        $userId=$su_data->UserProposed;

                    if(!isset($su_data->ProposedBy))
                        $propBy=$this->session->userdata('user_id');
                    else
                        $propBy=$su_data->ProposedBy;

                    if($propBy==$userId && $propBy==$this->session->userdata('user_id') &&
                        Proposal::deleteOwnerMe($db, $su_data->Proposal, $userId))
                    {
                        $status=200;
                        $data = array(
                            "message" => "Vlasnik je uspešno obrisan!"
                        );
                        $data=json_encode($data);
                    }
                    else if(Proposal::deleteOwner($db, $userId, $su_data->Proposal, $propBy))
                    {
                        $status=200;
                        $data = array(
                            "message" => "Vlasnik je uspešno obrisan!"
                        );
                        $data=json_encode($data);
                    }
                    else
                    {
                        $status=404;
                        $error_description=array(
                            "message"=>"Vlasnik nije pronađen!",
                            "error" => "Greška!"
                        );

                        $data=json_encode($error_description);
                    }
                    break;
            }
        }
        catch(Exception $e)
        {
            $status="500";
            $error_description=array(
                "blah" => $e->getMessage(),
                "message"=>"Greška na serveru!",
                "error" => "Greška!"
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

    public function proposallist()
    {
        if(!in_array($this->method, $this->supported_methods))
        {
            //TODO Error report
        }

        $db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $db->exec("use manthanodb;");
        /* setting status and data which will be returned */
        $status=200;
        $data="";

        /* Big switch which REST service actually is */
        try
        {
            switch($this->method)
            {
                /* processing GET - READ request */
                case 'get':
                    $arr = Proposal::getAllProposals($db);
                    if(sizeof($arr)>0)
                    {
                        /* setting package that will be sent to client*/
                        $data=json_encode($arr);
                        $etag = md5(time()."");
                        header('Etag:'.$etag);
                        header("Expires: -1");
                        $status=200;
                    }
                    else
                    {
                        $status=400;
                        $error_description=array(
                            "message" => "Lista predloga je prazna!",
                            "error" => "Greška!"
                        );
                        $data=json_encode($error_description);
                    }
                    break;
                case 'post':
                    break;
                case 'put':
                    break;
                case 'delete':
                    break;
            }
        }
        catch(Exception $e)
        {
            $status="500";
            $error_description=array(
                "blah" => $e->getMessage(),
                "message"=>"Greška na serveru!",
                "error" => "Greška!"
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

    public function nonusers($idActivity)
    {
        $db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $db->exec("use manthanodb;");
        $data = "";
        $status = 200;
        try
        {
            switch($this->method)
            {
                case 'get':
                    $data = json_encode(Proposal::getAll($idActivity, $this->session->userdata('user_id'), $db));
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
?>