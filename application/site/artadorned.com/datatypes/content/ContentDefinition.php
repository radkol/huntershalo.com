<?php

class ContentDefinition extends CmsDataTypeDefinition {

    /**
     * Description of Content
     */
    public function __construct() {
        parent::__construct();

        $field = new StringField("name", "Content Name");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new RichTextField("content", "Content");
        $field->validation = "required";
        $this->addField($field);

    }

    public function listFields() {
        return array("name");
    }

    public function searchFields() {
        return parent::searchFields("name");
    }

}
