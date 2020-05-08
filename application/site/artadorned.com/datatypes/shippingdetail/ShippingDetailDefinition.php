<?php

class ShippingDetailDefinition extends CmsDataTypeDefinition {

    /**
     * Description of Shipping Detail
     */
    public function __construct() {
        parent::__construct();

        $field = new StringField("name", "Shipping Detail Name");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("label", "Shipping Detail Label");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("currencyCode", "Currency Code");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new DecimalField("shippingCost", "Shipping Detail Cost Amount");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new DecimalField("shippingTax", "Shipping Detail Tax Amount");
        $field->validation = "required";
        $this->addField($field);

        $field = new BooleanField("defaultShipping", "Default Shipping Method");
        $this->addField($field);
    }

    public function listFields() {
        return array("name", "label", "shippingCost", "shippingTax");
    }

    public function searchFields() {
        return parent::searchFields("name");
    }

}
