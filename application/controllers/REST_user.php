<?php
    class REST_user extends MY_Controller {


        public function __construct(){
            parent::__construct();

        }

        public function index(){

        }

        public function activity($act_type="stefane"){
            echo "cao ".$act_type;
        }

    }