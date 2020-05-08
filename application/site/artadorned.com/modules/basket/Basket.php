<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Radko Lyutskanov
 */
    
/*
 * Default Controller for Basket.
 */

class Basket extends CmsModuleType {
    
    public function typeAsString() {
        return "Basket Module";
    }
    
    public function process($moduleInstance) {
        
        $data = parent::process($moduleInstance);
        
        $data["wishlistItems"] = WishlistService::instance()->getWishlistItems();
        $data["wishlistItemsImages"] = TypeService::instance()->getType("Product")->search()->getFileUploadsForObjects($data["wishlistItems"], "images", 1);
        $this->view("default", $data);
    }
    

    
}
