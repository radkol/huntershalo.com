<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Radko Lyutskanov
 */
class Collection extends CmsDataType {
    
    public function objectAsString($object) {
        return $object->name;
    }

    public function typeAsString() {
        return "Collections";
    }
    
    public function objectUrl($object) {
        return array($object->id, getUrlCompatibleString($object->name));
    }
}
