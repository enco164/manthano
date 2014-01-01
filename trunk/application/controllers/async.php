<?php

    class Async extends MY_Controller{

        public function __construct(){
            parent::__construct();
            $this->load->model('validation_model');
            $this->load->helper('form', 'cookie', 'recaptchalib');
            $this->load->model('user');
        }

        public function index(){

        }

        public function login(){
            $this->load->helper('url');
            $this->load->model('user');
            //This method will have the credentials validation
            $this->load->library('form_validation');


            $this->form_validation->set_rules('name', 'Username', 'trim|required|xss_clean');
            $this->form_validation->set_rules('pass', 'Password', 'trim|required|xss_clean');

            //echo $this->input->post('name');
            //echo $this->input->post('pass');

            $start_pages=$this->config->item('user_start_pages');
            if(!$this->session->userdata('user_id')){

                if($this->form_validation->run() == FALSE)
                {
                    $data['message']="Neispravno korisnicko ime ili lozinka";
                    $this->load->view('login-dropbox',$data);
                }
                else
                {
                    $username = $this->input->post('name');
                    $password = $this->input->post('pass');

                    //echo '<script>alert("'.$username.'");alert("'.$password.'");</script>';

                    $attempt=$this->check_login($username,$password);
                    if($attempt===TRUE){
                        $_SESSION['user_id']=$this->session->userdata('user_id');
                        echo '<script>if($("#btn_add_ad").length>0){$("#btn_add_ad").trigger("click");}else{window.location.href="'.$start_pages[$this->session->userdata('acc_type')].'"}</script>';
                        //echo '<script>window.location.href="'.$start_pages[$this->session->userdata('acc_type')].'"</script>';
                    }else{
                        $data['message']="Neispravno korisnicko ime ili lozinka";
                        $this->load->view('login-dropbox',$data);
                    }
                    //echo "<script>window.location.reload</script>";
                    //redirect($start_pages[$this->session->userdata('acc_type')]);
                }
            }else{
                echo '<script>window.location.href="'.$start_pages[$this->session->userdata('acc_type')].'"</script>';
                //redirect($start_pages[$this->session->userdata('acc_type')]);
            }

        }


        private function check_login($username,$password){
            //Field validation succeeded.  Validate against database
            $username=addslashes($username);
            $password=addslashes($password);
            $result = $this->user->login_user($username, $password);
            if($result)
            {
                return TRUE;
            }
            else
            {
                return "Neispravna kombinacija korisničkog imena i lozinke";
                //$this->form_validation->set_message('check_database', 'Invalid username or password');
            }
        }
    }

