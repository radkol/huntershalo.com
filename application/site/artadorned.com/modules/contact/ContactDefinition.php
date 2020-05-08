<?php

class ContactDefinition extends CmsModuleTypeDefinition {

    /**
     * Description of Contact Module
     */
    public function __construct() {
        parent::__construct();
        
        $field = new StringField("title", "Contact Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("boxedContent", "Boxed Content");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("boxedContentSubtitle", "Boxed Content Subtitle");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("phone", "Contact Phone Number");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("email", "Contact Email Address");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new TextField("successMessage", "Contact Submission Success Message");
        $field->validation = "required";
        $this->addField($field);
        
    }

}
