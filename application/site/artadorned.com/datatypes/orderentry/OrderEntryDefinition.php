<?php

class OrderEntryDefinition extends CmsDataTypeDefinition {

    /**
     * Description of Order Entry
     */
    public function __construct() {
        parent::__construct();

        $field = new StringField("uid", "Unique Order Entry Id");
        $this->addField($field);
        
        $field= new IntegerField("productId", "Product Id");
        $field->validation = "required";
        $this->addField($field);
        
        $field= new StringField("productName", "Product Name");
        $field->validation = "required";
        $this->addField($field);
        
        $field= new DecimalField("productPrice", "Product Price");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new IntegerField("quantity", "Quantity");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new DecimalField("subtotal", "Subtotal");
        $field->validation = "required";
        $this->addField($field);
        
        
    }

    public function listFields() {
        return array("uid", "product","quantity", "subtotal");
    }

    public function searchFields() {
        return parent::searchFields("uid");
    }

}
