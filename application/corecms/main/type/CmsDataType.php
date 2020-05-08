<?php

abstract class CmsDataType extends CmsGenericType {

    public function objectAsString($object) {
        return $object->id;
    }
    
    public function typeAsString() {
        return "Data Type";
    }
    
    public function allowAddAction() {
        return true;
    }

    public function allowDeleteAction() {
        return true;
    }
    
    public function allowEditAction() {
        return true;
    }
    
    public function objectUrl($object) {
         return $object->id;
    }

}   