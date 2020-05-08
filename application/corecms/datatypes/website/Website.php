<?php

class WebSite extends CmsDataType {
    
    public function objectAsString($object) {
        return $object->title;
    }

    public function typeAsString() {
        return "Web Site";
    }
}

