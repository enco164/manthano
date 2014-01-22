<?php
    class User extends CI_Model {
        public $user_data;
        public $user_id;
        public $acc_type;
        private $CI;
        public $is_logged_in;

        public function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->CI = & get_instance();
        }

        public function add_user($data, $acc_type){
            if(is_numeric($acc_type)){
            $user_data['salt']=create_salt();
            $user_data['name']="";
            $user_data['surname']="";
            $user_data['username']="";
            $user_data['mail']="";
            $user_data['acc_type']=$acc_type;
            $user_data['password']=md5(md5($data['password']).$user_data['salt']);

            unset($data['password_repeat']);
            unset($data['password']);

            foreach($data as $key=>$value){
                $user_data[$key]=$value;
            }
                if(strlen($user_data['mail'])>3){
                    $this->db->insert('user',$user_data);
                    $this->user_id=$this->db->insert_id();
                    //$user_data['hash'] = md5($this->user_id.time());
                    //$user_data['hash_time']=time();
                    $this->db->where('user_id', $this->user_id);
                    $this->db->update('user', $user_data);
                    //$this->send_verification_email($user_data, $this->user_id);

                }
            }
        }


        public function get_user_data(){
            if($this->user_id){

                if($this->session->userdata('username')){
                    $this->user_data['user_id'] =$this->session->userdata('user_id');
                    $this->user_data['mail']   =$this->session->userdata('mail');
                    $this->user_data['username']=$this->session->userdata('username');
                    $this->user_data['acc_type']=$this->session->userdata('acc_type');
                    $this->user_data['status']=$this->session->userdata('status');
                    $this->user_data['name']=$this->session->userdata('name');
                    $this->user_data['surname']=$this->session->userdata('surname');

                    //added regular session variables
                    $_SESSION['user_id'] =$this->session->userdata('user_id');
                    $_SESSION['mail']   =$this->session->userdata('mail');
                    $_SESSION['username']=$this->session->userdata('username');
                    $_SESSION['acc_type']=$this->session->userdata('acc_type');
                    $_SESSION['status']=$this->session->userdata('status');
                    $_SESSION['name']=$this->session->userdata('name');
                    $_SESSION['surname']=$this->session->userdata('surname');


                }else{
                    $this->user_data=$this->db->select('user_id,username,mail,acc_type,status,name,surname')->from('user')->where('user_id',$this->user_id)->get()->row_array();
                    //var_dump($this->user_data);
                    $this->session->set_userdata($this->user_data);
                }
            }else{

                $this->user_data=NULL;
            }
        }

        public function set_user_data($user_id){//DANGEROUS!!! manualy logs specified user in!
            if($user_id){

                if($this->session->userdata('username')){

                    $this->user_data['user_id'] =$this->session->userdata('user_id');
                    $this->user_data['mail']   =$this->session->userdata('mail');
                    $this->user_data['username']=$this->session->userdata('username');
                    $this->user_data['acc_type']=$this->session->userdata('acc_type');
                    $this->user_data['status']=$this->session->userdata('status');
                    $this->user_data['name']=$this->session->userdata('name');
                    $this->user_data['surname']=$this->session->userdata('surname');

                    //added regular session variables
                    $_SESSION['user_id'] =$this->session->userdata('user_id');
                    $_SESSION['mail']   =$this->session->userdata('mail');
                    $_SESSION['username']=$this->session->userdata('username');
                    $_SESSION['acc_type']=$this->session->userdata('acc_type');
                    $_SESSION['status']=$this->session->userdata('status');
                    $_SESSION['name']=$this->session->userdata('name');
                    $_SESSION['surname']=$this->session->userdata('surname');

                }else{

                    $this->user_data=$this->db->select('user_id,username,mail,acc_type,status,name,surname')->from('user')->where('user_id',$user_id)->get()->row_array();
                    //var_dump($this->user_data);
                    $this->session->set_userdata($this->user_data);
                }
            }else{

                $this->user_data=NULL;
            }
        }

        public function login_user($username,$password,$type="normal"){
            //$user_data['username']=$this->input->get_post('username');
            //$user_data['password']=$this->input->get_post('password');

            if($type=='normal'){
                $user_data['mail']=$username;
                $user_data['password']=$password;

                $check=$this->db->select('password,salt,user_id')->from('user')->where('mail',$user_data['mail'])->get()->row_array();

                if(!empty($check) && $check['password']==md5(md5($user_data['password']).$check['salt'])){
                    $this->user_id=$check['user_id'];
                    $this->get_user_data();
                    //redirect('');
                    //echo 'OK';
                    return TRUE;
                }else{
                    //$this->session->sess_destroy();
                    //$this->session->sess_create();
                    //redirect('');
                    //echo 'login_data_not_valid';
                    return FALSE;
                }
            }/*else{
                $user_data['username']=$username;

                $check=$this->db->select('user_id')->from('user')->where('social_auth',$user_data['username'])->get()->row_array();
                $this->user_id=$check['user_id'];
                $this->get_user_data();

                return TRUE;
            }*/
        }

        public function logout_user(){
            $this->CI->logged_in=FALSE;
            $this->session->sess_destroy();
            $this->session->sess_create();
        }

        public function is_banned(){
            if($this->user_id){
                $this->db->select('status');
                $this->db->from('user');
                $this->db->where('user_id',$this->user_id);
                $data=$this->db->get()->row_array();

                return $data['status']==0;
            }
        }

        public function check_login(){
            $this->user_id=$this->session->userdata('user_id');
            if($this->user_id){
                $this->get_user_data();
                $this->CI->logged_in=TRUE;
                return true;
            }else{
                $this->CI->logged_in=FALSE;
                return false;
                //$this->session->sess_destroy();
                //$this->session->sess_create();
            }
        }

        public function send_verification_email($data, $uid){
            $link = base_url()."#registration=".$data['hash'];
            $this->load->library('email','parser');
            $this->email->initialize($this->config->item('email_config'));

            $this->email->from("no_reply@manthano.com","Portal Manthano");
            $this->email->to($data['email']);
            $this->email->subject("Verifikacija kreiranja naloga na portalu Manthano");
            $this->email->message("Molimo vas da u narednih 24h kliknete na ovaj link, kako bi proces vaše registracije bio uspešno završen: ".$link."<br><br><img src='http://lakodokola.devcypher.com/assets/img/logo.png' />");

            if($this->email->send()){
                //SVE JE KUL, MAIL JE POSLAT
                /*$data['email_status']=1;
                $data['hash_time']=time();

                $this->db->where('user_id', $uid);
                $this->db->update('user',$data);
                if($this->db->affected_rows()==1){
                    //echo $this->email->print_debugger();
                }*/
            }
        }

        public function activate_user($data){
            $this->db->select('*');
            $this->db->from('user');
            $this->db->where('hash', $data);
            $query = $this->db->get();
            $result = $query->result_array();
            if($result[0]['status']==0){
                if($result[0]['hash_time']>time()-86400){
                    if($query->num_rows()==1){

                        $result[0]['status']=1;
                        $result[0]['date_joined']=time();
                        $this->db->where('user_id', $result[0]['user_id']);
                        $this->db->update('user', $result[0]);
                        if($this->db->affected_rows()==1){
                            $msg['status']="success";
                            $msg['message']="Uspešno ste se registrovali,";
                            return $msg;
                        } else {
                            $msg['status']="error";
                            $msg['message']="Greška pri upisu u bazu tokom procesa ponovnog slanja verifikacionog emaila.";
                            return $msg;
                        }
                    }
                } else {
                    $msg['status']="warning";
                    $msg['message']="Vaš aktivacioni link je istekao.";
                    return $msg;
                }
            }
            else{
                $msg['status']="error";
                $msg['message']="Neispravan link.";
                return $msg;
            }
        }

        public function verify_user_data($data){
            $result = $this->db->select('*')->from('user')->where('hash', $data)->get()->result_array();
            print_r($result[0]);
            if(count($result)){
                if($result[0]['status']==0){
                    if($result[0]['hash_time']<time()-86400){
                        $user_data['hash']=md5($result[0]['user_id'].time());
                        $user_data['hash_time'] = time();
                        $user_data['mail'] = $result[0]['mail'];

                        $this->db->where('user_id', $result[0]['user_id'])->update('user', $user_data);

                        if($this->db->affected_rows()==1){
                            $this->send_verification_email($user_data, $result[0]['user_id']);
                            return "Novi link je uspešno poslat na vašu email adresu. Molimo vas da potvrdite registraciju klikom na link u vašem email sandučetu. ";
                        } else {
                            return "Greška pri upisu u bazu tokom procesa ponovnog slanja verifikacionog emaila.";
                        }

                    } else {
                        return "Vaš prethodni link je još uvek aktivan. Molimo vas da potvrdite registraciju klikom na link u vašem email sandučetu.";
                    }
                }else{
                    echo "Korisnik je već aktivan.";
                }
            }else{
                echo "Neispravan link.";
            }
        }

/*
        public function store_data(){
            $soc_data=$this->session->userdata('soc_data');
            if(!empty($soc_data)){
                if(!empty($soc_data['email'])){
                    $soc_data['extended']['type']=$soc_data['type'];
                    $soc_data['extended']['id']=$soc_data['id'];

                    $data['email']=$soc_data['email'];
                    $data['extended']=json_encode($soc_data['extended']);

                    $this->db->query("INSERT INTO newsletter (`email`,`extended`) VALUES ('{$data['email']}','{$data['extended']}') ON DUPLICATE KEY UPDATE `extended`='{$data['extended']}'");
                }
            }

        }*/
    }