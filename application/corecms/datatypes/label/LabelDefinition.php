<?php

class LabelDefinition extends CmsDataTypeDefinition {

    public function __construct() {
        parent::__construct();

        $field = new StringField("code", "Label code");
        $field->validation = "required";
        $this->addField($field);

        $field = new StringField("value", "Label Text");
        $field->multiLanguage = true;
        $field->validation = "required";
        $this->addField($field);
    }

    public function listFields() {
        return array_merge(parent::listFields(), array("code", "value"));
    }

    public function searchFields() {
        return array_merge(parent::searchFields(), array("code"));
    }

    public function orderBy() {
        return "code#asc";
    }

}
