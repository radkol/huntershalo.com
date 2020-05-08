<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Radko Lyutskanov
 */

class Wishlist extends CmsDataType {

    public function objectAsString($object) {
        return sprintf("Wishlist for customer %d", $object->customer);
    }
    
    public function typeAsString() {
        return "Customer Wishlists";
    }
    
}
