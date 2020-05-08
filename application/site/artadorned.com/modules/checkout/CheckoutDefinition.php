<?php

class CheckoutDefinition extends CmsModuleTypeDefinition {

    /**
     * Description of Basket
     */
    public function __construct() {
        parent::__construct();
        
        $field = new StringField("title", "Checkout Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("billingTitle", "Billing Address Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("billingCurrentTitle", "Billing Existing Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("billingNewTitle", "Billing New Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("shippingTitle", "Shipping Address Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("shippingCurrentTitle", "Shipping Existing Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("shippingNewTitle", "Shipping New Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("shippingMethodTitle", "Shipping Method Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("shippingMethodSubTitle", "Shipping Method Sub Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("paymentTitle", "Payment Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("paymentSubTitle", "Payment Sub Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("orderConfirmationHeading", "Order Confirmation Heading");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("orderErrorHeading", "Order Error / Cancel Heading");
        $field->validation = "required";
        $this->addField($field);
        
    }

}
