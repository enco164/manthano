<?php

    class Home extends MY_Controller {

        public function __construct(){
            parent::__construct();

            if($this->session->userdata('acc_type')<1){
                redirect('/login');
            }
        }
        public function index(){
            $this->load->view('base/header');
            $this->load->view('user/home');
            $this->load->view('base/footer');
        }

    }

