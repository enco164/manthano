<?php
/* This is class is edited based of activity class's  REST service.
 
/* required libraries */
require_once('Material.php');
/* define supported methods */
$supported_methods=array('get','post', 'put', 'delete');
/* converting method name to lower letters for easier comapring */
$method=strtolower($_SERVER['REQUEST_METHOD']);

if(!in_array($method, $supported_methods)){
    //TODO Error report
}

/* Reading data from path */
if(isset($_SERVER['PATH_INFO'])){
    /* path is material_b_rest.php/material/1
     * so we need to explode path on / and do -1 because of name of script
     * Notice that further in script we are consider that array elements begin from index 1
     *
     */
    $url_elements=explode("/", $_SERVER['PATH_INFO']);
    $number_of_url_elements=count($url_elements)-1;
}


/* setting status and data which will be returned */
$status=200;
$data="";
/* Initializing database, this can be changed to db wrapper but now its PDO object
 * on which material object is based.
 */
$db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$db->exec("use manthanodb;");
/* Big switch which REST service actually is */
try{
    switch($method){
        /* processing GET - READ request */
        case 'get':
            /* Checking if url has 2 elements (material/id) and if first one is material
             * if not nothing will be done.
             * Further more we have branching if material exists or not.
             * Its important to set status properly every time.
             */
            if($number_of_url_elements == 2 and $url_elements[1] == 'material'){
                $material = new material(intval($url_elements[2]), $db);
                if(!$material->exists()){
                    $status=404;
                    $error_description=array( "message"=>"material doesn't exist!");
                    $data=json_encode($error_description);
                }
                else{

                   
                    $arr = array(
                        "id" => $material->id(),
                        "name" => $material->Name() ,
                        "uri" => $material->URI(),
                        "date" => $material->Date(),
                        "ownerId" => $material->OwnerID(),
                        "path" => $material->getPath()
                    );
                    $data=json_encode($arr);
                    $etag = md5($material);
                    header('Etag:'.$etag);
                    header("Expires: -1");
                    $status=200;
                }
            }
            else {
                $status=400;
                $error_description=array(
                    "message"=>"Bad URL"
                );
                $data=json_encode($error_description);
            }
            break;

        /* Processing POST - CREATE request*/
        case 'post':
            if($number_of_url_elements==2 and $url_elements[1]=='material'){
                //citamo podatke
                $ac_data=json_decode(file_get_contents('php://input'));
                  /* Checking user privileges, need to be implemented.
                   
                   * $ac_data->id is id of material.
                   */
                   if(true){
                    $ind = material::addmaterial($db, $ac_data->id, $ac_data->Name, $ac_data->URI, $ac_data->Date, $ac_data->OwnerID );
                    if($ind){
                        $status=201;
                        $data = array(
                            "message" => "material added"
                        );
                        $data=json_encode($data);
                    }
                    else{
                        $status=400;
                        $error_description=array(
                            "message" => "Resource wasn't found!"
                        );
                        $data=json_encode($error_description);
                    }
                }
                else{
                    /*Unauthorized*/
                    $status = 401;
                }
            }
            break;
        /* Processing PUT - UPDATE request */
        case 'put':
            if($number_of_url_elements==2 and $url_elements[1]=='material'){
                /* reading data */
                $ac_data=json_decode(file_get_contents('php://input'));
                /* Checking user privileges, need to be implemented.
                 * Is one of material holders or is it admin?
                 * $ac_data->id is id of material.
                 */
                if(true){
                    $temp = new material($ac_data->id,$db);
                    $etag = md5($temp);
                    /* checking if resource was modified */
                    if($ac_data->Etag == $etag){
                        $temp->setName($ac_data->Name);
                        $temp->setURI($ac_data->URI);
                        $temp->setDate($ac_data->Date);
                        $temp->setOwnerID($ac_data->OwnerID);
                        /* updating and proper action if update is ok or not*/
                        if($temp->update()){
                            /* ok */
                            $status = 200;
                        }
                        else{
                            /*Bad request*/
                            $status=400;
                            $error_description=array(
                                "message"=>"Bad request"
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
            }
            break;
        /* Processing DELETE request */
        case 'delete':
            if($number_of_url_elements==2 and $url_elements[1]=='material'){
                $id=intval($url_elements[2]);
                /* Checking user privileges, need to be implemented.
                 * Is one of material holders or is it admin?
                 * $ac_data->id is id of material.
                 *  */
                if(true){
                    if(material::deletematerial($id, $db)){
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

?>
