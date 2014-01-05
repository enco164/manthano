<?php
    class Home extends User_Controller {


        public function __construct(){
            parent::__construct();
            $this->load->helper('text');

        }

        public function index(){
            $this->load->view('home');
            //$korisnik=new Users($this->session->userdata('user_id'));
            //var_dump();
        }

        public function activity_info(){
            $this->load->view('activity_info');
        }



    }

