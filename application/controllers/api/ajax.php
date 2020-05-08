<?php

class Ajax extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if (!RequestService::instance()->isAjaxRequest()) {
            redirect(base_url());
        }

        require APPPATH . getCmsFolder() . "main/CmsAjaxProcessor.php";
    }

    public function invoke($operation) {
        CmsAjaxProcessor::instance()->execute($operation);
    }

}
