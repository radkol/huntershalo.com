<?php

class Import extends CMS_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper("admin");
        
        if (!AuthorizationService::instance()->isAdminLoggedIn()) {
            redirect(base_url("adminlogin"));
        }
    }
    
    public function type($type) {
        //$this->load->helper("file");
        CmsDataImportProcessor::instance()->import($type);
    }

}
