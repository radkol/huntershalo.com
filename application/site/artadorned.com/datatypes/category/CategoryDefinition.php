<?php

class CategoryDefinition extends CmsDataTypeDefinition {

    /**
     * Description of asset
     */
    public function __construct() {
        parent::__construct();
        
        $field = new StringField("name","Category Name");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("description","Category Description");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new RelationField("parent", "Parent Category");
        $field->linkTo = "Category";
        $this->addField($field);
        
    }
    
    public function listFields() {
        return array("id", "name", "description", "parent");
    }
    
    public function searchFields() { 
        return array("name", "description", "parent");
    }
    
}
