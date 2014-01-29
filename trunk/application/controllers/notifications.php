<?php

class Notifications extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->session->sess_destroy();
        $this->session->sess_create();
        session_destroy();
    }
    public function index(){
        $db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $db->exec("use manthanodb;");
        $stmt = $db->prepare("SELECT * from Activity ");
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($res);
    }
}

?>
