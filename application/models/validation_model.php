<?php

    class Validation_model extends CI_Model{

        public function __construct(){
            parent::__construct();
        }

        public $config = array(
            
            'user_register' => array(
                array(
                    'field' => 'name',
                    'label' => 'ime',
                    'rules' => 'trim|required|min_length[3]|max_length[30]|xss_clean|callback_alpha_num_space'
                ),
                array(
                    'field' => 'surname',
                    'label' => 'prezime',
                    'rules' => 'trim|required|min_length[3]|max_length[30]|xss_clean|callback_alpha_num_space'
                ),
                array(
                    'field' => 'username',
                    'label' => 'korisničko ime',
                    'rules' => 'trim|required|min_length[5]|max_length[30]|is_unique[user.username]|xss_clean|callback_alpha_num_space'
                ),
                array(
                    'field' => 'mail',
                    'label' => 'mail',
                    'rules' => 'trim|required|valid_email|is_unique[user.mail]|xss_clean'
                ),
                array(
                    'field' => 'password',
                    'label' => 'lozinka',
                    'rules' => 'trim|required|min_length[5]|max_length[20]|xss_clean'
                ),
                array(
                    'field' => 'password_repeat',
                    'label' => 'ponovljena lozinka',
                    'rules' => 'trim|required|matches[password]|xss_clean'
                ),
                array(
                    'field' => 'recaptcha_response_field',
                    'label' => 'Recaptcha',
                    'rules' => 'trim|required|callback_val_recaptcha'
                )
            ),
            'edit_user' => array(
                array(
                    'field' => 'name',
                    'label' => 'ime',
                    'rules' => 'trim|required|min_length[3]|max_length[30]|xss_clean|callback_alpha_num_space'
                ),
                array(
                    'field' => 'surname',
                    'label' => 'prezime',
                    'rules' => 'trim|required|min_length[3]|max_length[30]|xss_clean|callback_alpha_num_space'
                ),
                array(
                    'field' => 'username',
                    'label' => 'korisničko ime',
                    'rules' => 'trim|required|min_length[5]|max_length[30]|is_unique[user.username]|xss_clean|callback_alpha_num_space'
                ),
                array(
                    'field' => 'mail',
                    'label' => 'mail',
                    'rules' => 'trim|required|valid_email|is_unique[user.mail]|xss_clean'
                ),
                array(
                    'field' => 'password',
                    'label' => 'lozinka',
                    'rules' => 'trim|required|min_length[6]|max_length[20]|xss_clean'
                ),
                array(
                    'field' => 'password_repeat',
                    'label' => 'ponovljena lozinka',
                    'rules' => 'trim|required|matches[password]|xss_clean'
                )
            ),
            
            'forgot' => array(
                array(
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'trim|required|valid_email|is_unique[forgot_password.email]|callback_email_exists|xss_clean',
                )
            ),
            'password_change' => array(
                array(
                    'field' => 'password',
                    'label' => 'Password',
                    'rules' => 'trim|required|min_length[6]|max_length[12]|xss_clean'

                ),
                array(
                    'field' => 'password_repeat',
                    'label' => 'Repeat password',
                    'rules' => 'trim|required|matches[password]|xss_clean'
                ),
            ),
            
            'contact_us' => array(
                array(
                    'field'=>'name',
                    'label'=>'ime',
                    'rules'=>'trim|required|min_length[3]|max_length[30]|xss_clean|callback_alpha_num_space'
                ),
                array(
                    'field'=>'email',
                    'label'=>'email',
                    'rules'=>'trim|required|valid_email|xss_clean'
                ),
                array(
                    'field'=>'subject_message',
                    'label'=>'naslov poruke',
                    'rules'=>'trim|required|max_length[50]|xss_clean'
                ),
                array(
                    'field'=>'message',
                    'label'=>'poruka',
                    'rules'=>'trim|required|max_length[500]|xss_clean'
                )
            ),

        );

        

        function alpha_dash_space($str){
            return ( ! preg_match("/^([-a-z_ ])+$/i", $str)) ? FALSE : TRUE;
        }

        function alpha_num_space($str){
            return ( ! preg_match("/^[A-Za-z0-9-_\",\'\s]+$/", $str)) ? FALSE : TRUE;
        }
        /*function val_recaptcha($string){//useless here, needs to be in controller :<
            $resp = recaptcha_check_answer($this->config->item('recaptcha_public_key'),$_SERVER["REMOTE_ADDR"],$this->input->post('recaptcha_response_field'),$this->input->post('recaptcha_response_field'));

            if(!$resp->is_valid) {
                $this->form_validation->set_message('val_recaptcha','Your answer for the security question was incorrect, please try again.');
                $this->form_validation->set_message('recaptcha_response_field','Your answer for the security question was incorrect, please try again.');
                return FALSE;
            }
            else {
                return TRUE;
            }
        }*/
    }