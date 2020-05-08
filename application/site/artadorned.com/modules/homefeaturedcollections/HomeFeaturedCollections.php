<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Radko Lyutskanov
 */
    
/*
 * Default Controller for Home Featured Collections.
 */

class HomeFeaturedCollections extends CmsModuleType {
    
    public function typeAsString() {
        return "Home Featured Collections";
    }
    
    public function process($moduleInstance) {
        
        $data = parent::process($moduleInstance);
        
        $collectionSearch = TypeService::instance()->getSearch("Collection");
        $productSearch = TypeService::instance()->getSearch("Product");
        
        $data["collections"] = $this->search()->getRelations("collections", $moduleInstance);
        $data["products"] = $this->search()->getRelations("products", $moduleInstance);
        $data["collectionImages"] = $collectionSearch->getFileUploadForObjects($data["collections"], "image");
        $data["productImages"] = $productSearch->getFileUploadsForObjects($data["products"], "images", 1);
        
        $this->view("default",$data);
    }
    

    
}
