<?php

class HomeNewsletterDefinition extends CmsModuleTypeDefinition {

    /**
     * Description of Home Newsletter
     */
    public function __construct() {
        parent::__construct();
        
        $field = new StringField("title", "Newsletter Panel Title");
        $field->validation = "required";
        $this->addField($field);
        
    }

}
