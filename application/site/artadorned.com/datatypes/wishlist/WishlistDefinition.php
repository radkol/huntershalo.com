<?php

class WishlistDefinition extends CmsDataTypeDefinition {

    /**
     * Description of Address
     */
    public function __construct() {
        parent::__construct();
        
        $field = new RelationField("customer", "Customer");
        $field->linkTo = "Customer";
        $field->validation = "required";
        $this->addField($field);
        
        $field = new RelationsField("products", "Products");
        $field->linkTo = "Product";
        $this->addField($field);
        
    }

    public function listFields() {
        return array("id", "customer");
    }

    public function searchFields() {
        return array("customer");
    }

}
