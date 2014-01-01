<?php
/* Based on Andjelka's example of REST service.
 * Simply script that reads number of arguments and parse them.
 * Currently is implemented only get method for full activity information
 * And short activity information.
 * Route for full info looks like: activity_b_rest.php/activity/2 where 2 is id of activity
 * Example:
 *  {
        "message": "ok",
        "name": "Informatika",
        "description": "ISmer",
        "date": "2004-12-20",
        "cover": "slika.com\/coverI.png",
        "sons": ["5", "6", "7"],
        "events": "Activity nema nijedan Event.",
        "path": ["1", "2"]
    }
 * Route for shorte info looks like: activity_b_rest.php/activity/short/2 where 2 is id of activity
 * Example:
 * {
        "message": "ok",
        "name": "Informatika",
        "description": "ISmer",
        "date": "2004-12-20",
        "cover": "slika.com\/coverI.png"
   }
 *
 *
 *
 *
 * */
/* required libraries */
require_once('Activity.php');
/* define supported methods */
$supported_methods=array('get','post', 'put', 'delete');
/* converting method name to lower letters for easier comapring */
$method=strtolower($_SERVER['REQUEST_METHOD']);

if(!in_array($method, $supported_methods)){
    //TODO Error report
}

/* Reading data from path */
if(isset($_SERVER['PATH_INFO'])){
    /* path is activity_b_rest.php/activity/1
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
 * on which Activity object is based.
 */
$db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$db->exec("use manthanodb;");
/* Big switch which REST service actually is */
try{
    switch($method){
        /* processing GET - READ request */
        case 'get':
            /* Checking if url has 2 elements (activity/id) and if first one is activity
             * if not nothing will be done.
             * Further more we have branching if activity exists or not.
             * Its important to set status properly every time.
             */
            if($number_of_url_elements == 2 and $url_elements[1] == 'activity'){
                $activity = new Activity(intval($url_elements[2]), $db);
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
                        "path" => $activity->getPath()
                    );
                    $data=json_encode($arr);
                    $etag = md5($activity);
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
            if($number_of_url_elements==2 and $url_elements[1]=='activity'){
                //citamo podatke
                $ac_data=json_decode(file_get_contents('php://input'));
                  /* Checking user privileges, need to be implemented. */
                   if(true){
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
            }
            break;
        /* Processing PUT - UPDATE request */
        case 'put':
            if($number_of_url_elements==2 and $url_elements[1]=='activity'){
                /* reading data */
                $ac_data=json_decode(file_get_contents('php://input'));
                /* /* Checking user privileges, need to be implemented. */
                if(true){
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
            }
            break;
        /* Processing DELETE request */
        case 'delete':
            if($number_of_url_elements==2 and $url_elements[1]=='activity'){
                $id=intval($url_elements[2]);
                if(true){
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
