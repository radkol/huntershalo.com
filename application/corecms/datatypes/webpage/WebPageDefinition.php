<?php

class WebPageDefinition extends CmsDataTypeDefinition {

    public function __construct() {
        parent::__construct();

        $field = new StringField("name", "Name");
        $field->validation = "required|min_length[3]";
        $field->multiLanguage = true;
        $this->addField($field);

        $field = new RelationField("parent", "Parent Page");
        $field->linkTo = "WebPage";
        $this->addField($field);

        $field = new RelationField("website", "Web Site");
        $field->linkTo = "WebSite";
        $field->validation = "required";
        $this->addField($field);

        $field = new StringField("url", "Url");
        $field->visibleAdd = false;
        $field->visibleEdit = true;
        $field->readOnly = true;
        $this->addField($field);

        $field = new StringField("title", "SEO Title");
        $field->validation = "required|min_length[5]";
        $field->multiLanguage = true;
        $this->addField($field);

        $field = new TextField("keywords", "SEO Keywords");
        $field->multiLanguage = true;
        $this->addField($field);

        $field = new TextField("description", "SEO Description");
        $field->multiLanguage = true;
        $this->addField($field);

        $field = new ListField("template", "Template");
        $field->values = $this->getAvailableTemplates();
        $this->addField($field);
        
        $field = new BooleanField("homePage", "Is home page");
        $this->addField($field);
    }

    public function listFields() {
        return array_merge(parent::listFields(), array("name", "url", "title", "template"));
    }

    public function searchFields() {
        return array_merge(parent::searchFields(), array("name", "url", "title"));
    }

    public function getAvailableTemplates() {
        return getTemplates();
    }
    
    public function getColors() {
        return array(
            "grey" => "Grey",
            "black" => "Black",
            "blue" => "Blue",
            "lblue" => "Light Blue",
            "green" => "Green",
            "orange" => "Orange",
            "rose" => "Rose",
            "purple" => "Purple",
            "red" => "Red",
            "yellow" => "Yellow"
        );
    }
    
}
