<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Radko Lyutskanov
 */
class Customer extends CmsDataType {
    
    public function objectAsString($object) {
        return sprintf("%s, %s %s", $object->email, $object->firstName, $object->lastName);
    }

    public function typeAsString() {
        return "Customers";
    }
}
