<?php

    class Home extends Admin_Controller {

        public function __construct(){
            parent::__construct();
        }
        public function index(){
            /*$this->load->view('base/header');
            $this->load->view('admin/home');
            $this->load->view('base/footer');*/
            redirect('/admin/user_control');
        }

    }

