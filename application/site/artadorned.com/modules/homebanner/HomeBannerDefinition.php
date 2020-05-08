<?php

class HomeBannerDefinition extends CmsModuleTypeDefinition {

    /**
     * Description of Home Banner
     */
    public function __construct() {
        parent::__construct();

        $field = new ChildField("slideItems", "Home Banner Slides");
        $field->linkTo = "HomeBannerItem";
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("title","Home Banner Title");
        $this->addField($field);
        
        $field = new RichTextField("description","Home Banner Description");
        $this->addField($field);
        
        $field = new RelationsField("links","Home Banner Links");
        $field->linkTo = "NavigationMenuItem";
        $field->validation = "required";
        $this->addField($field);
        
    }

    public function listFields() {
        return array_merge(parent::listFields(), array("title", "description"));
    }

    public function searchFields() {
        return parent::searchFields();
    }

}
