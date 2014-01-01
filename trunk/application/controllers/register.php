<?php

    class Register extends MY_Controller{

        public function __construct(){
            parent::__construct();
            $this->load->model('validation_model');
            //$this->load->helper('recaptchalib');
            $this->load->helper('form');
            $this->load->model('user');
        }

        public function index(){
            $this->load->library('form_validation');
            //$this->load->helper('recaptchalib_helper');
            $validation_arr = $this->validation_model->config['user_register'];
            //$this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules($validation_arr);

            $this->load->view('base/header');
            if($this->form_validation->run()==FALSE){
                $this->load->view('login');
            } else {
                $data = $_POST;
                $this->user->add_user($data, 1);
                $this->load->view('success');
            }
            $this->load->view('base/footer');
        }

        public function test_register(){
            $this->load->library('form_validation');
            //$this->load->helper('recaptchalib_helper');
            $validation_arr = $this->validation_model->config['user_register'];
            //$this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules($validation_arr);

            $this->load->view('base/header');
            if($this->form_validation->run()==FALSE){
                $this->load->view('login');
            } else {
                $data = $_POST;
                $this->user->add_user($data, 1);
                $this->load->view('success');
            }
            $this->load->view('base/footer');
        }

        public function val_recaptcha($string){
            $resp = recaptcha_check_answer(config_item('recaptcha_private_key'),$_SERVER["REMOTE_ADDR"],$this->input->post('recaptcha_challenge_field'),$this->input->post('recaptcha_response_field'));
            if(!$resp->is_valid) {
                //$this->form_validation->set_message('recaptcha_response_field','Uneli ste pogrešan kod. Molimo vas unesite ponovo.');
                $this->form_validation->set_message('val_recaptcha','Uneli ste pogrešan kod. Molimo vas unesite ponovo.');
                return FALSE;
            }
            else {
                return TRUE;
            }
        }
    }