<?php

class WebSiteDefinition extends CmsDataTypeDefinition {

    /**
     * * Description of navigation menu
     */
    public function __construct() {
        parent::__construct();

        $field = new StringField("name", "Qualified(Domain) name");
        $field->validation = "required|max_length[50]";
        $this->addField($field);

        $field = new StringField("title", "Site Title");
        $field->validation = "required";
        $this->addField($field);

        $field = new BooleanField("httpsEnabled", "Https Enabled");
        $this->addField($field);

        $field = new RelationField("defaultLanguage", "Default Language");
        $field->linkTo = "language";
        $field->validation = "required";
        $this->addField($field);

        $field = new RelationsField("availableLanguages", "Available Languages");
        $field->linkTo = "language";
        $field->validation = "required";
        $this->addField($field);

        $field = new BooleanField("defaultWebsite", "Make Default WebSite");
        $this->addField($field);
    }

    public function listFields() {
        return array_merge(parent::listFields(), array("code", "name"));
    }

    public function searchFields() {
        return parent::searchFields();
    }

}
