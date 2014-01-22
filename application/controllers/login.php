<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Login extends MY_Controller {

        function __construct()
        {
            parent::__construct();
            $this->load->helper('url');
            $this->load->model('user');
        }

        function index(){
            //$this->output->enable_profiler(TRUE);
            //making example of some
            /*$some_user=new Users(1);
            var_dump($some_user);*/

            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', 'Username', 'trim|required|xss_clean');
            $this->form_validation->set_rules('pass', 'Password', 'trim|required|xss_clean|callback_check_database');
            //echo $this->input->post('name');
            //echo $this->input->post('pass');
            $start_pages=$this->config->item('user_start_pages');
            if(!$this->session->userdata('user_id')){
                if($this->form_validation->run() == FALSE){
                    //Field validation failed.  User redirected to login page
                    $this->load->view('base/header');
                    $this->load->view('login');
                    $this->load->view('base/footer');
                } else {
                    redirect($start_pages[$this->session->userdata('acc_type')]);

                }
            } else {
                redirect($start_pages[$this->session->userdata('acc_type')]);
            }
        }

        function check_database($password){
            //Field validation succeeded.  Validate against database
            $username = $this->input->post('name');
            //echo $username;
            //query the database
            $result = $this->user->login_user($username, $password);
            if($result){
                return TRUE;
            } else {
                $this->form_validation->set_message('check_database', 'Invalid username or password');
                return false;
            }
        }
    }
?>