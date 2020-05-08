<?php

class AssetDefinition extends CmsDataTypeDefinition {

    public function __construct() {
        parent::__construct();
        
        $field = new StringField("name","Name");
        $field->validation = "required|min_length[5]";
        $this->addField($field);
        
        $field = new TextField("description","Description");
        $this->addField($field);

        $field = new UploadField("file","Upload File");
        $field->allowedTypes = array("jpg", "png", "jpeg", "gif", "pdf");
        $field->validation = "required";
        $this->addField($field);
    }
    
    public function listFields() {
        return array_merge(parent::listFields(),array("file", "name", "description"));
    }
    
    public function searchFields() {
        return array_merge(parent::searchFields(),array("name","description"));
    }
    
    public function orderBy() {
        return "name#asc";
    }
    
}
