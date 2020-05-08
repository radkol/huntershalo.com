<?php

class NewsletterDefinition extends CmsModuleTypeDefinition {

    /**
     * Description of Home Newsletter
     */
    public function __construct() {
        parent::__construct();
        
        $field = new StringField("title", "Newsletter Panel Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("formUrl", "News Letter Form URL");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("hiddenInputCode", "Prevent Bots Hash");
        $field->validation = "required";
        $this->addField($field);        
        
    }

}
