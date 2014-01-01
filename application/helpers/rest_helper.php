<?php
/* Based on Andjelka's example of REST service.
 * Simply script that reads number of arguments and parse them.
 * Currently is implemented only get method for full activity information
 * And short activity information.
 * Route for full info looks like: REST.php/activity/2 where 2 is id of activity
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
 * Route for shorte info looks like: REST.php/activity/short/2 where 2 is id of activity
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
/* define supported methods */
$supported_methods=array('get','post', 'put', 'delete');
/* converting method name to lower letters for easier comapring */
$method=strtolower($_SERVER['REQUEST_METHOD']);

if(!in_array($method, $supported_methods)){
    //TODO Error report
}

/* Reading data from path */
if(isset($_SERVER['PATH_INFO'])){
    /* path is REST.php/activity/1
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
                        "message" => "ok",
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
                    $status=200;
                }
            }
            /* /activity/short/1 case  */
            else if($number_of_url_elements == 3 and $url_elements[1] == 'activity' and $url_elements[2] == 'short'){
                $activity = new Activity(intval($url_elements[3]), $db);
                if(!$activity->exists()){
                    $status=404;
                    $error_description=array( "message"=>"Activity doesn't exist!");
                    $data=json_encode($error_description);
                }
                $arr = array(
                    "message" => "ok",
                    "id" => $activity->id(),
                    "name" => $activity->Name() ,
                    "description" => $activity->Description(),
                    "date" => $activity->BeginDate(),
                    "cover" => $activity->CoverPicture()
                );
                $data=json_encode($arr);
                $status=200;
                echo $data;
            }
            /* handling bad url (example: REST.php/blahblah/1 */
            else{
                $status=400;
                $error_description=array(
                    "message"=>"Bad URL"
                );
                $data=json_encode($error_description);
            }
            break;

        /* Processing POST - CREATE request*/
        case 'post':
            if($number_of_url_elements==1 and $url_elements[1]=='books'){
                //citamo podatke
                $book_data=json_decode(file_get_contents('php://input'));
                $id=$db->insert($book_data->title, $book_data->price);
                if($id!=-1){
                    $status=201;
                    $new_link_description=array(
                        "link"=>"http://localhost/rest/server/server.php/books/$id"
                    );
                    $data=json_encode($data);
                }
                else{
                    $status=400;

                    $error_description=array(
                        "message"=>"resurs nije pronadjen"
                    );

                    $data=json_encode($error_description);
                }
            }
            break;
        /* Processing PUT - UPDATE request */
        case 'put':
            if($number_of_url_elements==1 and $url_elements[1]=='books'){
                /* reading data */
                $book_data=json_decode(file_get_contents('php://input'));
                if($db->update($book_data->id, $book_data->title, $book_data->price)){
                    $status=200;
                }
                else{
                    $status=400;

                    $error_description=array(
                        "message"=>"los zahtev"
                    );

                    $data=json_encode($error_description);
                }
            }
            break;
        /* Processing DELETE request */
        case 'delete':
            if($number_of_url_elements==2 and $url_elements[1]=='books'){
                $id=intval($url_elements[2]);
                if($db->delete($id)){
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

$response=new HTTPResponse();
$response::status($status);
$response::setContentType("application/json");
$response::setHeader("Connection:close");
if(isset($data))
    $response::setData($data);

$response::send();

?>
