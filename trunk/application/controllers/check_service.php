<?php
class check_service extends MY_Controller {


    public function __construct(){
        parent::__construct();
        $this->load->helper('text');

    }

    public function activity($idActivity){
        $db = new PDO("mysql:localhost;dbname=manthanodb;charset=utf8","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $db->exec("use manthanodb;");
        $arr = array("check" => Activity::isHolder($this->session->userdata('user_id'), $idActivity, $db),
                     "exist" => Activity::existsActivity($idActivity, $db));
        echo json_encode($arr);
    }

    public function event( $idEvent){
        $db = new PDO("mysql:localhost;dbname=manthanodb;charset=utf8","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $db->exec("use manthanodb;");
        $arr = array("check" => Event::isHolderStatic($this->session->userdata('user_id'), $idEvent, $db));
        echo json_encode($arr);
    }

    public function material($id, $idMaterial){
//        $db->exec("use manthanodb;");
//        $db = new PDO("mysql:localhost;dbname=manthanodb;charset=utf8","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
//        $arr = array("check" => Activity::isHolder($id, $idActivity, $db));
//        echo json_encode($arr);
    }

    public function proposal($id){
//        $db = new PDO("mysql:localhost;dbname=manthanodb;charset=utf8","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
//        $db->exec("use manthanodb;");
//        $arr = array("check" => Activity::isHolder($id, $idActivity, $db));
//        echo json_encode($arr);
    }

    public function user($id){
//        $db = new PDO("mysql:localhost;dbname=manthanodb;charset=utf8","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
//        $db->exec("use manthanodb;");
//        $arr = array("check" => Activity::isHolder($id, $idActivity, $db));
//        echo json_encode($arr);
    }
}