<?php


class Logout extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->session->sess_destroy();
        $this->session->sess_create();
        session_destroy();
    }
    public function index(){

        redirect('/', 'refresh');
    }

    public function disabled(){
        $message['data']['message']="Your account had been disabled. Please contact your admin to resolve the issue";

        $this->load->view('base/header');
        $this->load->view('message',$message);
        $this->load->view('base/footer');
    }
}