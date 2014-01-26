<?php
/**
 * Created by PhpStorm.
 * User: Jupike
 * Date: 7.1.14.
 * Time: 13.56
 */
class REST_proposal
{
    private $supported_methods;
    /* converting method name to lower letters for easier comapring */
    private $method;

    public function __construct()
    {
        $this->load->helper('text');
        $this->method=strtolower($_SERVER['REQUEST_METHOD']);
        $this->supported_methods=array('get','post', 'put', 'delete');
    }

    public function index(){
        echo "hello!";
    }

    public function proposal($id)
    {
        if(!in_array($this->method, $this->supported_methods)){
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
                        $error_description=array( "message"=>"Proposal doesn't exist!");
                        $data=json_encode($error_description);
                    }
                    else
                    {
                        /* setting package that will be sent to client */
                        $arr = array(
                            "id" => $proposal->idProposal(),
                            "name" => $proposal->Name() ,
                            "description" => $proposal->Description(),
                            "support" => $proposal->getSupport(),
                            "support_count" => $proposal->getSupportCount(),
                            "owners" => $proposal->getOwners(),
                            "owners_count" => $proposal->getOwnerCount()
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
                        $ind = Proposal::addProposal($db, $pr_data->User, $pr_data->Name, $pr_data->Description);
                        if($ind)
                        {
                            $status=201;
                            $data = array(
                                "message" => "Proposal added!"
                            );
                            $data=json_encode($data);
                        }
                        else
                        {
                            $status=400;
                            $error_description=array(
                                "message" => "Resource wasn't found!"
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
                        $error_description=array( "message"=>"Proposal doesn't exist!");
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
                                "message" => "Proposal updated!"
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
                            "message" => "Proposal deleted!"
                        );
                        $data=json_encode($data);
                    }
                    else
                    {
                        $status=404;
                        $error_description=array(
                            "message" => "Resource wasn't found!"
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

    public function proposalsupport($id)
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
                    break;
                case 'post':
                    $su_data=json_decode(file_get_contents('php://input'));

                    $ind = Proposal::addSupport($db, $su_data->User, $su_data->Proposal);
                    if($ind)
                    {
                        $status=201;
                        $data = array(
                            "message" => "Support added!"
                        );
                        $data=json_encode($data);
                    }
                    else
                    {
                        $status=400;
                            $error_description=array(
                            "message" => "Resource wasn't found!"
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
                        if(Proposal::deleteSupport($db, $su_data->User, $su_data->Proposal))
                        {
                            $status=200;
                            $data = array(
                                "message" => "Support deleted!"
                            );
                            $data=json_encode($data);
                        }
                        else
                        {
                            $status=404;
                            $error_description=array(
                                "message" => "Resource wasn't found!"
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
                    break;
                case 'post':
                    $su_data=json_decode(file_get_contents('php://input'));

                    $ind = Proposal::addOwner($db, $su_data->User, $su_data->Proposal, $su_data->Proposed);
                    if($ind)
                    {
                        $status=201;
                        $data = array(
                            "message" => "Owner added!"
                        );
                        $data=json_encode($data);
                    }
                    else
                    {
                        $status=400;
                        $error_description=array(
                            "message" => "Resource wasn't found!"
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
                    if(Proposal::deleteOwner($db, $su_data->User, $su_data->Proposal, $su_data->Proposed))
                    {
                        $status=200;
                        $data = array(
                            "message" => "Owner deleted!"
                        );
                        $data=json_encode($data);
                    }
                    else
                    {
                        $status=404;
                        $error_description=array(
                            "message" => "Resource wasn't found!"
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
?>