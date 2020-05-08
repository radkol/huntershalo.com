<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Radko Lyutskanov
 */

class Address extends CmsDataType {

    public function objectAsString($object) {

        $addressType = $object->shippingAddress ? "Shipping" : "Billing";
        return sprintf("Customer id: %d; %s: %s, %s, %s, %s; Contact: %s, %s", $object->customer, $addressType, 
                $object->addressLine1, $object->city, $object->postcode, 
                $object->country, $object->firstName, $object->lastName);
    }

    public function typeAsString() {
        return "Customer Addresses";
    }
    
    public function getAddressForOrder($object) {
        return sprintf("%s, %s, %s, %s, %s, %s, %s, %s", 
        $object->addressLine1, $object->addressLine2 ? ', '.$object->addressLine2 : '', $object->city, $object->postcode, 
        $object->country, $object->firstName, $object->lastName, $object->phone);
    }
    
    public function create($fields) {
        $fieldService = FieldService::instance();
        $shipping = $fieldService->getFieldByName($fields, "shippingAddress");
        $billing = $fieldService->getFieldByName($fields, "billingAddress");
        $uid = $fieldService->getFieldByName($fields, "uid");
        $uid->value = uniqid();
        if(!$shipping->value && !$billing->value) {
            $billing->value = 1;
        }
        
        //debug($fields);
        
        return parent::create($fields);
    }
    
}
