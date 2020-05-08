<?php

class BasketDefinition extends CmsModuleTypeDefinition {

    /**
     * Description of Basket
     */
    public function __construct() {
        parent::__construct();
        
        $field = new StringField("title", "Basket Page Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("orderSummaryTitle", "Order Summary Title");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new StringField("wishlistTitle", "Wishlist Title");
        $field->validation = "required";
        $this->addField($field);
        
    }

}
