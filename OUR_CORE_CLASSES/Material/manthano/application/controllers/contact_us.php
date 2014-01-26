<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Contact_us extends MY_Controller {

    	public function __construct(){
            parent::__construct();
			$this->load->library('form_validation');
            $this->load->model('validation_model');
            $this->load->library('email');
        }

        public function index()
        {

        	$page_data['message']='';
        	$validation_rules=$this->validation_model->config['contact_us'];
            $this->form_validation->set_rules($validation_rules);

            if ($this->form_validation->run() == FALSE){
            	if($_POST) $page_data['message']='Niste dobro popunili sva polja!!!';
                
            } else {
                $this->email->initialize($this->config->item('email_config'));
                $data = $_POST;
                /*$this->email->from($data['email'], $data['name']);
                $this->email->to('danijel84@gmail.com');
                $this->email->reply_to($data['email'], $data['name']);
                $this->email->subject($data['subject_message']);
                $this->email->message($data['message']);*/

                $db_data['name']=$data["name"];
                $db_data["email_from"]=$data['email'];
                $db_data["email_to"]=$this->config->item('email_host');
                $db_data["subject"]=$data['subject_message'];
                $db_data["message"]=$data['message'];
                $db_data["date"]=time();



                if($this->crud_model->db_insert_email($db_data)){
                    $page_data['message']='Uspešno poslat email';
                    echo "<script>alert(\"{$page_data['message']}\"); window.location.href='/';</script>";

                } else {
                    $page_data['message']='Sistemska greška pri slanju maila';
                }

                /*if($this->email->send()){
                    redirect('');

                } else {
                    echo ('Mail Sending Failed!');
                }

                echo $this->email->print_debugger();*/
            }

            $this->load->view("base/header");
            $this->load->view("contact_us",$page_data);
            $this->load->view("base/footer");

        }
    }