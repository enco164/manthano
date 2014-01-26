<?php
class MY_Controller extends CI_Controller {
    public $logged_in=FALSE;
    public $theme;
    public $mobile_check;

    public function __construct(){
        parent::__construct();
        session_start();

        // Place the driver calling code here
        $this->load->driver('cache',array('adapter' => 'memcached', 'backup' => 'file'));
        $this->load->model('user');
        $this->user->check_login();

    }
    function set_logged_in($status){
        $this->logged_in=$status;
    }
}
class User_Controller extends MY_Controller {
    public function __construct(){
        parent::__construct();
        if($this->session->userdata('acc_type')<1){
            redirect('/login');
        }
    }
}

class Admin_Controller extends MY_Controller {
    public function __construct(){
        parent::__construct();
        if($this->session->userdata('acc_type')!=99){
            redirect('/login');
        }
    }
}