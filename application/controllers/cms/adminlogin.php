<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Radko Lyutskanov
 */

class Adminlogin extends CI_Controller {
    
    private $data = array();
    
    public function __construct() {
        parent::__construct();
        $this->load->helper("admin", "form");
        if (AuthorizationService::instance()->isAdminLoggedIn()) {
            redirect(base_url("admin"));
        }
        
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="adminerror">', '</p>');
        $this->form_validation->set_rules("username","Username","required|valid_email");
        $this->form_validation->set_rules("password","Password","required|min_length[5]");
    }

    //render login form
    public function index() {
        $this->data["view"] = "login";
        $this->load->view("administration",$this->data);
    }

    public function login() {
        
        if ($this->form_validation->run($this) !== FALSE) {
            $data = array(
                "username" => $this->input->post("username"),
                "password" => md5($this->input->post("password")),
                "adminRights" => 1,
                "isActive" => 1
            );
            
            $userSearch = TypeService::instance()->getSearch("User");
            $adminUser = $userSearch->getRecord($data);
            
            if ($adminUser !== NULL) {
                AuthorizationService::instance()->setUser($adminUser);
                redirect(base_url("admin"));
            } else {
                $this->data["custom_errors"] = $this->getCustomErrors();
            }
        }
        $this->index();
    }
    
    protected function getCustomErrors() {
        return array("<p class='error'>Account with these details can't be found. Try again</p>");
    }
}
