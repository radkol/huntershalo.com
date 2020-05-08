<?php

class CustomerDefinition extends CmsDataTypeDefinition {

    /**
     * Description of Customer
     */
    public function __construct() {
        parent::__construct();

        $field = new StringField("firstName", "First Name");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("lastName", "Last Name");
        $field->validation = "required";
        $this->addField($field);

        $field = new StringField("email", "Email Address");
        $field->validation = "required|valid_email";
        $this->addField($field);

        $field = new StringField("login", "Customer Login(Username / Email)");
        $field->validation = "required|valid_email";
        $this->addField($field);
        
        $field = new PasswordField("password", "Password");
        $field->validation = "required|min_length[6]";
        $this->addField($field);
        
        $field = new StringField("phone", "Phone");
        $field->validation = "required|min_length[6]";
        $this->addField($field);
                
        $field = new BooleanField("active", "Is Active");
        $this->addField($field);

        $field = new StringField("resetToken", "Reset Token");
        $this->addField($field);
        
    }

    public function listFields() {
        return array("firstName", "lastName", "email", "active");
    }

    public function searchFields() {
        return parent::searchFields("name");
    }

}
