<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Radko Lyutskanov
 */

class OrderEntry extends CmsDataType {

    public function objectAsString($object) {
        return sprintf("Order Entry ' %s '", $object->uid);
    }

    public function typeAsString() {
        return "Placed Orders Entries";
    }
    
    public function create($fields) {
//        $fieldService = FieldService::instance();
//        $shipping = $fieldService->getFieldByName($fields, "shippingAddress");
//        $billing = $fieldService->getFieldByName($fields, "billingAddress");
//        $uid = $fieldService->getFieldByName($fields, "uid");
//        $uid->value = uniqid();
//        if(!$shipping->value && !$billing->value) {
//            $billing->value = 1;
//        }
        
        //debug($fields);
        
        return parent::create($fields);
    }
    
}
