<?php

abstract class CmsDataTypeDefinition extends CmsGenericTypeDefinition {
    
    public function searchFields() {
        return array();
    }
    
    public function listFields() {
        return array("id");
    }
    
    public function orderBy() {
        return "id#asc";
    }
    
}