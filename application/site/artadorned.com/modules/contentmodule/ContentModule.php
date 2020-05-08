<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Radko Lyutskanov
 */
    
/*
 * Default Controller for Content Module.
 */

class ContentModule extends CmsModuleType {
    
    public function typeAsString() {
        return "Content Module";
    }
    
    public function process($moduleInstance) {
        
        $data = parent::process($moduleInstance);
        $this->view("default", $data);
    }
    

    
}
