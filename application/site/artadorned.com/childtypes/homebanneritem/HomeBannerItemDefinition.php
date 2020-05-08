<?php

class HomeBannerItemDefinition extends CmsDataTypeDefinition {

    /**
     * Description of HomeBannerItem
     */
    public function __construct() {
        parent::__construct();

        $field = new StringField("title", "Slide Title");
        $field->validation = "required|max_length[40]";
        $this->addField($field);
     
        $field = new StringField("description", "Slide Description");
        $field->validation = "required|min_length[5]";
        $this->addField($field);

        $field = new RelationField("backgroundImage", "Background Image");
        $field->linkTo = "Asset";
        $field->validation = "required";
        $this->addField($field);

        $field = new RelationField("linkLocation", "Link To");
        $field->linkTo = "NavigationMenuItem";
        $field->validation = "required";
        $this->addField($field);
        
    }

    public function listFields() {
        return array_merge(parent::listFields(), array("title", "description", "linkText"));
    }

    public function searchFields() {
        return parent::searchFields("title");
    }

}
