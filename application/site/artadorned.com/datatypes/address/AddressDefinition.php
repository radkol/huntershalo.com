<?php

class AddressDefinition extends CmsDataTypeDefinition {

    /**
     * Description of Address
     */
    public function __construct() {
        parent::__construct();

        $field = new StringField("uid", "Unique Address Id");
        $field->visibleAdd = FALSE;
        $field->visibleEdit = FALSE;
        $this->addField($field);
        
        $field = new StringField("firstName", "Receiver First Name");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("lastName", "Receiver Last Name");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("phone", "Phone Number");
        $this->addField($field);
        
        $field = new StringField("addressLine1", "Address Line 1");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("addressLine2", "Address Line 2");
        $this->addField($field);

        $field = new StringField("country", "Country");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("state", "State");
        $this->addField($field);
        
        $field = new StringField("city", "City");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("postcode", "Postcode");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new BooleanField("shippingAddress", "Is Shipping Address");
        $this->addField($field);
        
        $field = new BooleanField("billingAddress", "Is Billing Address");
        $this->addField($field);
        
        $field = new BooleanField("defaultShippingAddress", "Is Default Shipping Address");
        $this->addField($field);
        
        $field = new BooleanField("defaultBillingAddress", "Is Default Billing Address");
        $this->addField($field);
        
        $field = new RelationField("customer", "Customer");
        $field->linkTo = "Customer";
        $field->validation = "required";
        $this->addField($field);
    }

    public function listFields() {
        return array("id", "customer", "firstName","lastName", "addressLine1", "city", "postcode", "country", "shippingAddress", "billingAddress");
    }

    public function searchFields() {
        return array("customer", "firstName","lastName", "addressLine1", "city", "postcode", "country", "shippingAddress", "billingAddress");
    }

}
