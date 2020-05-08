<?php

abstract class CmsChildType extends CmsGenericType {
    
    public function objectAsString($object) {
        return $object->id;
    }
    
    public function typeAsString() {
        return "Child Type";
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

}   