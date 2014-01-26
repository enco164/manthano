<?php

    class Async extends MY_Controller{

        public $user_id;
        public function __construct(){
            parent::__construct();
            $this->load->model('validation_model');
            $this->load->helper('form', 'cookie', 'recaptchalib');
            $this->load->model('user');
            $this->user_id=$this->session->userdata('user_id');
        }

        public function index(){

        }

    }

