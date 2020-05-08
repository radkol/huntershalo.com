<?php

class SiteConfigDefinition extends CmsDataTypeDefinition {

    public function __construct() {
        parent::__construct();

        $field = new StringField("fieldName", "Field name");
        $field->validation = "required";
        $field->visibleEdit = true;
        $field->visibleAdd = true;
        $field->readOnly = true;
        $this->addField($field);

        $field = new StringField("fieldTitle", "Field title");
        $field->validation = "required";
        $this->addField($field);

        $field = new StringField("fieldValue", "Field Value");
        $field->validation = "required";
        $field->validation = "required";
        $this->addField($field);
    }

    public function listFields() {
        return array_merge(parent::listFields(), array("fieldTitle", "fieldName", "fieldValue"));
    }

    public function searchFields() {
        return parent::searchFields();
    }

    public function _typeAsString() {
        return "Site Configuration";
    }
    
    public function orderBy() {
        return "fieldName#asc";
    }

}
