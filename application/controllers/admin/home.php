<?php

    class Home extends Admin_Controller {

        public function __construct(){
            parent::__construct();
        }
        public function index(){
            /*$this->load->view('base/header');
            $this->load->view('admin/home');
            $this->load->view('base/footer');*/
            redirect('/admin/home/user_list');
        }

        public function user_list(){
            $where['OwnerId']=1;
            var_dump($this->crud_model->db_get_user_material($where));

            $page_data['users']=$this->crud_model->db_get_users();
            $page_data['type']='users';
            //$this->load->view('base/header');
            $this->load->view('admin/user_list',$page_data);
            //$this->load->view('base/footer');
        }

    }

