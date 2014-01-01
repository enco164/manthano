<?php

    class Test extends MY_Controller{

        public function __construct(){
            parent::__construct();
            $this->load->model('validation_model');
            
            $this->load->helper('form');
            $this->load->model('user');
        }

        public function index(){
            phpinfo();
        }

        public function inputs(){
            //do somth
        }


    }