<?php

class UserDefinition extends CmsDataTypeDefinition {

    public function __construct() {
        parent::__construct();

        $field = new StringField("name", "Name");
        $field->validation = "required|min_length[5]";
        $this->addField($field);

        $field = new StringField("username", "Username / Email");
        $field->validation = "required|valid_email";
        $this->addField($field);

        $field = new PasswordField("password", "Password");
        $field->validation = "required|min_length[5]";
        $this->addField($field);

        $field = new BooleanField("adminRights", "Has Admin Rights");
        $this->addField($field);

        $field = new BooleanField("isActive", "Is active");
        $this->addField($field);
    }

    public function listFields() {
        return array_merge(parent::listFields(), array("username", "name", "adminRights", "isActive"));
    }

    public function searchFields() {
        return parent::searchFields();
    }

}
