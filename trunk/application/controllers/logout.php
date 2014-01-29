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
        $page_data['message']="Vaš nalog je blokiran.";
        $page_data['title']="Nalog blokiran.";

        $this->load->view('base/header');
        $this->load->view('message_static',$page_data);
        $this->load->view('base/footer');
    }

    public function inactive(){
        $page_data['message']="Vaš nalog nije aktivan.";
        $page_data['title']="Nalog neaktivan.";

        $this->load->view('base/header');
        $this->load->view('message_static',$page_data);
        $this->load->view('base/footer');
    }
}