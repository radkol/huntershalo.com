<?php

class HomeFeaturedCollectionsDefinition extends CmsModuleTypeDefinition {

    /**
     * Description of Home Featured Collections
     */
    public function __construct() {
        parent::__construct();
        
        $field = new StringField("heading", "Module Heading");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new TextField("subHeading", "Module Sub Heading");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new RelationsField("products","Home Featured Products");
        $field->linkTo = "Product";
        $field->validation = "required";
        $this->addField($field);
        
        $field = new RelationsField("collections","Home Featured Collections");
        $field->linkTo = "Collection";
        $field->validation = "required";
        $this->addField($field);

        $field = new BooleanField('showSection', 'Display the section on home page');
        $this->addField($field);
    }

}
