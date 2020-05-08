<?php

abstract class CmsChildTypeDefinition extends CmsDataTypeDefinition {
    
    public function searchFields() {
        return array();
    }
    
    public function listFields() {
        return array("id");
    }
    
}