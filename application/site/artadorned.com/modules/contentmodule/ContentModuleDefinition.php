<?php

class ContentModuleDefinition extends CmsModuleTypeDefinition {

    /**
     * Description of Content Module
     */
    public function __construct() {
        parent::__construct();
        
        $field = new RichTextField("content", "Content");
        $field->validation = "required";
        $this->addField($field);
        
    }

}
