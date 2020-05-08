<?php

class CollectionDefinition extends CmsDataTypeDefinition {

    /**
     * Description of ProductCollection
     */
    public function __construct() {
        parent::__construct();

        $field = new StringField("name", "Collection Name");
        $field->validation = "required";
        $this->addField($field);

        $field = new StringField("shortDescription", "Collection Short Description");
        $field->validation = "required";
        $this->addField($field);

        $field = new RichTextField("longDescription", "Collection Long Description");
        $field->validation = "required";
        $this->addField($field);

        $field = new UploadField("image", "Collection Grid / Tile Image (1200x1200)");
        $field->validation = "required";
        $field->saveOriginal = FALSE;
        $field->sizes = $this->gridSizes();
        $this->addField($field);
        
        $field = new UploadField("landingImage", "Collection Landing Image(1200x600)");
        $field->sizes = $this->landingSizes();
        $field->saveOriginal = FALSE;
        $field->validation = "required";
        $this->addField($field);
    }

    public function listFields() {
        return array_merge(parent::listFields(), array("name", "shortDescription"));
    }

    public function searchFields() {
        return parent::searchFields("name");
    }

    private function gridSizes() {
        $sizes = [];
        $sizeItem = new stdClass();
        $sizeItem->width = 1200;
        $sizeItem->height = 1200;
        $sizeItem->resize = TRUE;
        $sizeItem->crop = FALSE;
        $sizeItem->xAxis = 0;
        $sizeItem->yAxis = 0;
        $sizes[] = $sizeItem;
        return $sizes;
    }
    private function landingSizes() {
        $sizes = [];
        $sizeItem = new stdClass();
        $sizeItem->width = 1200;
        $sizeItem->height = 600;
        $sizeItem->resize = TRUE;
        $sizeItem->crop = FALSE;
        $sizeItem->xAxis = 0;
        $sizeItem->yAxis = 0;
        $sizes[] = $sizeItem;
        return $sizes;
    }

}
