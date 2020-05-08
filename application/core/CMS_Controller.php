<?php

class CMS_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();
                
        $this->load->helper("admin");

        if (!AuthorizationService::instance()->isAdminLoggedIn()) {
            redirect(base_url("adminlogin"));
        }
        
        // mark that we are processing request for the admin panel
        RequestService::instance()->setAttribute("adminRequest", TRUE);
        
    }

}
