<?php

abstract class CmsModuleTypeDefinition extends CmsGenericTypeDefinition {
    
    public function __construct() {
        parent::__construct();
        
        $field = new StringField("name","Name");
        $field->validation = "required|min_length[5]";
        $this->addField($field);
    }
    
    public function searchFields() {
        return array("name");
    }
    
    public function listFields() {
        return array("id","name");
    }
    
    public function orderBy() {
        return "name#asc";
    }
    
}