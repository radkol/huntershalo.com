<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Radko Lyutskanov
 */
    
/*
 * Default Controller for Home Newsletter.
 */

class Newsletter extends CmsModuleType {
    
    public function typeAsString() {
        return "Newsletter";
    }
    
    public function process($moduleInstance) {
        
        $data = parent::process($moduleInstance);
        $this->view("default",$data);
    }
    

    
}
