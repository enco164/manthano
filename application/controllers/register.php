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

        public function validate_user_registration(){
            $user_types=$this->config->item('user_accounts');
            $this->load->library('form_validation');
            //$this->load->helper('recaptchalib_helper');
            $validation_arr = $this->validation_model->config['user_register'];
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules($validation_arr);

            if($this->form_validation->run()==FALSE){
                $this->load->view('user_registration');

            } else {
                $data = $_POST;
                $data['acc_type']=1;
                $this->user->add_user($data, 1);
                //$this->user->get_user_data();
                $this->load->view('success');
            }
        }

        public function test_register(){
            $this->load->library('form_validation');
            $this->load->helper('recaptchalib_helper');
            $validation_arr = $this->validation_model->config['user_register'];
            //$this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules($validation_arr);

            $this->load->view('base/header');
            if($this->form_validation->run()==FALSE){
                $this->load->view('login');
            } else {
                unset($_POST['recaptcha_challenge_field']);
                unset($_POST['recaptcha_response_field']);
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
                $this->form_validation->set_message('val_recaptcha','Uneli ste pogrešan RECAPTCHA kod. Molimo vas unesite ponovo.');
                return FALSE;
            }
            else {
                return TRUE;
            }
        }
    }