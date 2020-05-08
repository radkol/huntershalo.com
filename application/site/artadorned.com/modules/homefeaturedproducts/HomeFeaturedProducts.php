<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Radko Lyutskanov
 */
    
/*
 * Default Controller for Home Featured Products
 */

class HomeFeaturedProducts extends CmsModuleType {
    
    public function typeAsString() {
        return "Home Featured Products";
    }
    
    public function process($moduleInstance) {
        
        $data = parent::process($moduleInstance);
        
        $productSearch = TypeService::instance()->getSearch("Product");
        $data["products"] = $this->search()->getRelations("products", $moduleInstance);
        $data["productImages"] = $productSearch->getFileUploadsForObjects($data["products"], "images" , 1);
        $this->view("default", $data);
    }
    

    
}
