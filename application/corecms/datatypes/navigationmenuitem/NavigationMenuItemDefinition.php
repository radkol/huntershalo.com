<?php

class NavigationMenuItemDefinition extends CmsDataTypeDefinition {

    /**
     * Description of NavigationMenuItem
     */
    public function __construct() {
        parent::__construct();

        $field = new StringField("name", "Link Display Name");
        $field->multiLanguage = true;
        $field->validation = "required|max_length[25]";
        $this->addField($field);
        
        $field = new RelationField("webpage", "Web Page");
        $field->linkTo = "WebPage";
        $this->addField($field);

        $field = new StringField("webpageUrl", "Web Page Url");
        $field->visibleAdd = false;
        $field->visibleEdit = false;
        $this->addField($field);
        
        $field = new StringField("relativeUrl", "Relative Url");
        $field->validation = "max_length[255]";
        $this->addField($field);
        
        $field = new LinkListField("itemLinkData", "Link to Specific Item");
        $field->values = getNavigationTypes();
        $this->addField($field);
        
        $field = new StringField("completeUrl", "Complete Url (http://...)");
        $field->validation = "max_length[255]";
        $this->addField($field);
        
        $field = new StringField("documentLink", "Link to Item in the Document (#)");
        $this->addField($field);
        
        $field = new BooleanField("newTab", "Open in new tab?");
        $this->addField($field);
    }

    public function listFields() {
        return array_merge(parent::listFields(), array("name", "webpageUrl", "itemLinkData", "completeUrl", "documentLink", "relativeUrl"));
    }

    public function searchFields() {
        return array("name", "webpageUrl", "itemLinkData");
    }

}
