<?php

class Setup extends CI_Controller {
    
    public function index() {
        $this->load->view("administration", array("view" => "setup"));
    }
    
}

