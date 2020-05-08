<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Radko Lyutskanov
 */
    
/*
 * Default Controller for Contact Module.
 */

class Contact extends CmsModuleType {
    
    public function typeAsString() {
        return "Contact Module";
    }
    
    public function process($moduleInstance) {
        $data = parent::process($moduleInstance);
        $this->ci->load->helper("form");
        
        $data["showSuccess"] = SessionService::instance()->getFlashAttribute("contactSuccess");
        $this->view("default", $data);
    }
    

    
}
