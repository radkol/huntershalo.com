<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Radko Lyutskanov
 */
class CollectionContent extends CmsDataType {
    
    public function objectAsString($object) {
        return $object->name;
    }

    public function typeAsString() {
        return "Collection Content Asset";
    }
}
