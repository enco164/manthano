<?php
    class Materials extends MY_Controller {

        public $vehicle_brands=Array();
        public $vehicle_type="car";
        public $locations=Array();
        public $latest_news=array();

        public function __construct(){
            parent::__construct();
            $this->load->helper('text');

        }

        public function index(){


            $kurs = new Material(200);
            echo $kurs->Name();
            $kurs->Name = "Table";
            echo $kurs->update();



            /*$this->load->view('base/header');
            $this->load->view('home');
            $this->load->view('base/footer');*/
        }

    }

