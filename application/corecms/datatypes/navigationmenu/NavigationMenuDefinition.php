<?php

class NavigationMenuDefinition extends CmsDataTypeDefinition {

    public function __construct() {
        parent::__construct();

        $field = new StringField("name", "Menu name");
        $field->validation = "required";
        $field->visibleEdit = true;
        $field->readOnly = true;
        $this->addField($field);
        
        $field = new RelationsField("menuItems", "Menu Items");
        $field->linkTo = "NavigationMenuItem";
        $field->validation = "required";
        $this->addField($field);
        
    }

    public function listFields() {
        return array_merge(parent::listFields(), array("name"));
    }

    public function searchFields() {
        return parent::searchFields();
    }
    
    public function orderBy() {
        return "name#desc";
    }
}
