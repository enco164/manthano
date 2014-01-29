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

        public function send_emails_to_users(){
            $data=json_decode(file_get_contents('php://input'),TRUE);
            json_decode(file_get_contents('php://input'),TRUE);
            if(isset($data['list']) && count($data['list'])){
                //$data_list=$data['list'];
                $user_list=$this->crud_model->db_get_users_in('user_id',$data['list']);
                $db_data=array();
                $counter=0;
                if($user_list) foreach($user_list as $user){
                    $db_data['email_to']=$user['mail'];
                    $db_data['email_from']=config_item('email_host');
                    $db_data['subject']=$data['subject'];
                    $db_data['message']=$data['body'];
                    $db_data['sent']=0;
                    $db_data['date']=time();
                    $db_data['name']='Manthano project';
                    $this->crud_model->db_insert_email($db_data);
                }else{
                    echo "navedeni korisnici ne postoje";
                }

                var_dump($data['list']);
                var_dump($user_list);
            }
        }

        public function upload_user_image($user_id){//ova metoda ne brise postojece slike, vec samo dodaje nove sa nazivima i=1:n.jpg
            if(!is_admin()){//SAMO ADMIN SME DA DIRA TUDJE SLIKE, inace deafultuje na tvoju sliku
                $user_id=$this->session->userdata('user_id');
            }
            set_time_limit(0);
            //$user_id = $this->session->userdata('user_id');
            $limit_width=$_POST['limit_width'];
            $max_size=5*1024*1024;
            $allowed=array("jpeg","jpg","png","JPG");
            $files=$_FILES['file_upload_input'];
            $output=array();
            $output['error']="";
            $root=$_SERVER['DOCUMENT_ROOT'];
            //var_dump($user_id);
            if(is_array($files)){
                $folder="$root/assets/resources/images/users/$user_id";     // for user files
                $relative_path="/assets/resources/images/users/$user_id";

                if(!is_dir($folder)){mkdir($folder,0777);}
                //$br = count(glob($folder."/*.{jpg,png,jpeg}",GLOB_BRACE))+1;
                foreach($files['name'] as $file=>$name){
                    $exp=explode(".",$name);
                    $extension=$exp[count($exp)-1];
                    unset($exp[count($exp)-1]);
                    $f_name=implode(".",$exp);
                    header("Content-Length: ".$files['size'][$file]);
                    if(in_array($extension,$allowed)){
                        if($files['size'][$file]<=$max_size){
                            for($i=1;$i<=12;$i++){
                                $ch_file = count(glob($folder."/".$i.".{jpg,png,jpeg}",GLOB_BRACE));
                                if($ch_file==0){
                                    $new_filename=$i.'.jpg'; break;
                                }
                            }
                            $new_image_path=$folder.'/'.$new_filename;
                            $new_image_relative_path=$relative_path.'/'.$new_filename;
                            if(isset($new_filename) && move_uploaded_file($files["tmp_name"][$file],$new_image_path)){
                                if(is_numeric($limit_width)){
                                    //if(!is_numeric($limit_quality)){
                                    $limit_quality=100;
                                    //}
                                    resize_img($new_image_path,"jpg",$limit_width,$limit_quality);
                                }

                                $output['file'][$file]=$relative_path.'/'.$new_filename;
                                $db_data['ProfilePicture']=$new_image_relative_path;
                                $this->crud_model->db_update_user_data($user_id,$db_data);
                            }
                        } else {
                            $output['error'] = "Prekoračili ste dozvoljenu veličinu slike. Maksimalna veličina je 5MB.";
                        }
                    } else {
                        $output['error']="Nedozvoljeni tip datoteke";
                    }
                }
                echo json_encode($output);
            }

        }
    }

