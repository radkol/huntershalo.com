<?php

class AccountDefinition extends CmsModuleTypeDefinition {

    /**
     * Description of Account
     */
    public function __construct() {
        parent::__construct();

        $field = new StringField("loginTitle", "Login Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("registerTitle", "Register Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("wishlistHeading", "Wishlist Heading");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("forgottenPasswordHeading", "Forgotten Password Heading");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("setNewPasswordHeading", "New Password Heading");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("accountDetailsHeading", "Account Details Heading");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("shippingAddressHeading", "Shipping Address Heading");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("billingAddressHeading", "Billing Address Heading");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("orderHistoryHeading", "Order History Heading");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("orderDetailHeading", "Order Detail Heading");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("existingAddressTitle", "Existing Addresses Section Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("billingNewTitle", "New Billing Address Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("shippingNewTitle", "New Shipping Address Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("wishlistLink", "My Wishlist Link Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("ordersLink", "My Orders Link Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("billingAddressLink", "Billing Addresses Link Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("shippingAddressLink", "Shipping Addresses Link Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("accountDetailsLink", "Account Details Link Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("orderHistoryBack", "Order History Back Button Text");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new TextField("forgottenPasswordDescription", "Forgotten Password Description Message");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new TextField("forgottenPasswordSuccess", "Forgotten Password Success Message");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new TextField("setNewPasswordDescription", "Set New Password Description Message");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new TextField("setNewPasswordSuccess", "Set New Password Success Message");
        $field->validation = "required";
        $this->addField($field);
        
        
    }

}
