<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Radko Lyutskanov
 */
class ShippingDetail extends CmsDataType {
    
    public function objectAsString($object) {
        return $object->name;
    }

    public function typeAsString() {
        return "Shipping Methods";
    }
}
