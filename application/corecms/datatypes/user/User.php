<?php

class User extends CmsDataType {

    public function objectAsString($object) {
        return $object->username;
    }
    
    public function typeAsString() {
        return "User";
    }
}

