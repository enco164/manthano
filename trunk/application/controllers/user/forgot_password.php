<?php

    class Forgot_password extends MY_Controller{

        public function __construct(){
            parent::__construct();
        }

        public function index(){
            $this->load->helper(array('form', 'url'));
            $this->load->model('validation_model');
            $this->load->library('form_validation');
            $validation_rules=$this->validation_model->config['forgot'];
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_message('is_unique', 'Zahtev za ponovno slanje šifre je već poslat!');
            $this->load->view('base/header');
            $data['message']="";

            if ($this->form_validation->run() == FALSE)
            {
                $this->load->view('forgot_password',$data);
            }
            else
            {
                $email=$_POST['email'];

                $data['message']="Email sa linkom za promenu šifre je poslat na ovu adresu: $email";
                $this->load->view('forgot_password',$data);
                $hash=sha1(md5($email+time()));

                $db_input['hash']=$hash;
                $db_input['email']=$email;

                if($this->data_model->db_insert_forgot_password($db_input)){

                }

                $link=base_url().'user/forgot_password/change_password/'.$hash;
                $email_data['email_from']=$this->config->item('email_host');
                $email_data['email_to']=$email;
                $email_data['subject']="Manthano - Vaš link za kreiranje nove šifre.";
                $email_data['message']=('Molimo vas potvrdite zahtev za promenu vaše šifre klikom ovaj link:<br>'.$link);
                $email_data['date']=time();
                $this->crud_model->db_insert_email($email_data);
            }
            $this->load->view('base/footer');
        }

        public function change_password($input=NULL){

            $this->load->helper(array('form', 'url'));
            $this->load->model('validation_model');
            $this->load->library('form_validation');
            $validation_rules=$this->validation_model->config['password_change'];
            $this->form_validation->set_rules($validation_rules);
            $this->load->view('base/header');

            $hash=addslashes($input);
            $db_input['hash']=$hash;
            $forgot_email=$this->data_model->db_get_forgot_password($db_input);
            if($forgot_email){
                $data['message']="";
                $data['hash']=$hash;
                $hash=addslashes($input);
                if ($this->form_validation->run() == FALSE)
                {
                    //$data['message']="Please provide ";
                    $this->load->view('change_password',$data);
                }
                else
                {
                    $email=$forgot_email['email'];
                    $salt=create_salt();
                    $pass=md5(md5($this->input->get_post('password')).$salt);
                    $this->data_model->db_update_user_password($email,$pass,$salt);
                    $this->data_model->db_delete_forgot_password($hash);

                    $data['message']="Vaša šifra je uspešno promenjena.";
                    $this->load->view('change_password',$data);

                    //posalji email,opciono
                    echo "<script>alert('Šifra uspešno promenjena!');
                      //window.location.href='/login/';
                </script>";
                }
            }else{
                redirect('/');
            }
            $this->load->view('base/footer');
        }

        public function email_exists($str) //returns true if email is present in user table
        {
            // You can access $_POST variable

            $result = $this->crud_model->email_exists($str);
            //var_dump($result);
            if (!$result)
            {
                $this->form_validation->set_message('email_exists', 'Email nije pronađen.');
                return FALSE;
            }else{
                //$this->form_validation->set_message('email_exists', 'Email %s not found');
                return TRUE;
            }
        }
    }