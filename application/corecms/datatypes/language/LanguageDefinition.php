<?php

class LanguageDefinition extends CmsDataTypeDefinition {
    
    public function __construct() {
        parent::__construct();
         
        $field = new StringField("code","Language code");
        $field->validation = "required|max_length[3]";
        $this->addField($field);
        
        $field = new StringField("name","Language name");
        $field->validation = "required";
        $this->addField($field);
         
        $field = new BooleanField("defaultLanguage", "Default System Language");
        $this->addField($field);
    }
    
    public function listFields() {
        return  array_merge(parent::listFields(),array("code","name"));
    }
    
    public function searchFields() {
        return parent::searchFields();
    }
    
}
