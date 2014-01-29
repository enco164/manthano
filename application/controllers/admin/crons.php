 <?php

    class Crons extends MY_Controller {

        public function __construct(){
            parent::__construct();
            $this->load->model('crud_model');
        }

        public function index(){
            echo "You don't have permission to be on this page";
        }

        public function send_unsent_emails(){  // slanje email-a visokog prioriteta
            $this->load->library('email','parser');
            $this->email->initialize($this->config->item('email_config'));
            $emails=$this->crud_model->db_get_emails_unsent();
            $db_data['sent']=1;
            $counter=0;
            if($emails) foreach($emails as $email){
                //var_dump($email);
                $this->email->from($email['email_from'],$email['email_from']);
                $this->email->to($email['email_to']);
                $this->email->subject($email['subject']);
                $this->email->message($email['message']);
                $this->email->set_alt_message('');
                if($this->email->send()){
                    //show_error$this->email->print_debugger());
                    $this->crud_model->db_update_email($email['id'],$db_data);
                    $counter++;
                }
            }
            echo "Poslato emailova: $counter";
        }

    }
?>