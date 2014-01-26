<?php
    class Partial extends MY_Controller {

        public function __construct(){
            parent::__construct();
            $this->load->helper('text');

        }

        public function index(){
            //$this->load->view('base/header');
            $this->load->view('partial');
            //$this->load->view('base/footer');

            /*$kurs = new Material(200);
            echo $kurs->Name();
            $kurs->Name = "Table";
            echo $kurs->update();*/
        }

    }

