<?php

    class User_control extends Admin_Controller {

        public function __construct(){
            parent::__construct();
        }
        public function index(){
            redirect('/admin/user_control/user_list');
        }

        public function user_list(){

            $page_data['users']=$this->data_model->db_get_users();
            $page_data['some_data']='ovo je neki podatak';
            $this->load->view('base/header');
            $this->load->view('admin/home',$page_data);
            $this->load->view('base/footer');
        }

    }