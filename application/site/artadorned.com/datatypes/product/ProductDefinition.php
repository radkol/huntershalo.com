<?php

class ProductDefinition extends CmsDataTypeDefinition {

    /**
     * Description of asset
     */
    public function __construct() {
        parent::__construct();
        
        $field = new StringField("name","Product name");
        $field->validation = "required|min_length[2]";
        $this->addField($field);
        
        $field = new StringField("shortDescription", "Short Description");
        $field->validation = "min_length[2]";
        $this->addField($field);
        
        $field = new TextField("longDescription", "Long Description");
        $field->validation = "min_length[10]";
        $this->addField($field);
        
        $field = new TextField("description1", "Product Description Line 1");
        $field->validation = "min_length[10]";
        $this->addField($field);
        
        $field = new TextField("description2", "Product Description Line 2");
        $field->validation = "min_length[10]";
        $this->addField($field);
        
        $field = new TextField("description3", "Product Description Line 3");
        $field->validation = "min_length[10]";
        $this->addField($field);
        
        $field = new DateTimeField("dateCreated", "Product Create Date");
        $field->visibleAdd = FALSE;
        $field->visibleEdit = FALSE;
        $this->addField($field);
        
        $field = new DateTimeField("dateModified", "Product Modify Date");
        $field->visibleAdd = FALSE;
        $field->visibleEdit = FALSE;
        $this->addField($field);
        
        $field = new DecimalField("price", "Product Price");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new RelationField("category", "Category");
        $field->linkTo = "Category";
        $field->validation = "required";
        $this->addField($field);
        
        $field = new RelationField("collection", "Collection");
        $field->linkTo = "Collection";
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("metalType", "Metal Type Refinement");
        $this->addField($field);
        
        $field = new StringField("style", "Style Refinement");
        $this->addField($field);
        
        $field = new StringField("era", "Era Refinement");
        $this->addField($field);
        
        $field = new StringField("aesthetic", "Aesthetic Refinement");
        $this->addField($field);
        
        $field = new StringField("color", "Color Refinement");
        $this->addField($field);

        $field = new UploadsField("images","Product Images (900x1200, 400x600)");
        $field->sizes = $this->getProductSizes();
        $field->saveOriginal = FALSE;
        $this->addField($field);
        
        $field = new BooleanField("available","Available To Buy");
        $this->addField($field);

        $field = new BooleanField("availableToRent", "Available to Rent");
        $this->addField($field);

    }
    
    public function listFields() {
        return array("id", "name", "shortDescription", "price", "category", "collection","available");
    }
    
    public function searchFields() { 
        return array("name", "category", "collection","available");
    }
    
    public function orderBy() {
        return "name#asc";
    }
 
    private function getProductSizes() {
        $sizes = [];
        $sizeItem = new stdClass();
        $sizeItem->width = 900;
        $sizeItem->height = 1200;
        $sizeItem->resize = TRUE;
        $sizeItem->crop = FALSE;
        $sizeItem->xAxis = 0;
        $sizeItem->yAxis = 0;
        
        $sizes[] = $sizeItem;
        
        $sizeItem = new stdClass();
        $sizeItem->width = 400;
        $sizeItem->height = 600;
        $sizeItem->resize = TRUE;
        $sizeItem->crop = FALSE;
        $sizeItem->xAxis = 0;
        $sizeItem->yAxis = 0;
        
        $sizes[] = $sizeItem;
        
        return $sizes;
    }
}
