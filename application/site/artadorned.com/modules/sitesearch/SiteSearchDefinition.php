<?php

class SiteSearchDefinition extends CmsModuleTypeDefinition {

    /**
     * Description of Basket
     */
    public function __construct() {
        parent::__construct();
        
        $field = new StringField("title", "Search Page Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new RichTextField("subtitle", "Search Page Sub Title");
        $this->addField($field);
        
        $field = new StringField("searchFor", "Search For Content");
        $this->addField($field);
        
        $field = new RichTextField("noResults", "No Results Content");
        $this->addField($field);
        
    }

}
