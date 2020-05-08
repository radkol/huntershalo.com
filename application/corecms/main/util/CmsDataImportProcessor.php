<?php

class CmsDataImportProcessor extends SingletonClass {
    
    public function import($type) {
        
        $ci = &get_instance();
        $className = "{$type}Importer";
        require APPPATH."site/import/{$className}.php";
        $importer = new $className();
        
        $result = $importer->import();
        
       
        $ci->session->set_flashdata("message", $result->message);
        redirect(getAdminPrefix());
        
    }
    
    
}


