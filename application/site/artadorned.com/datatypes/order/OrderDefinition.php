<?php

class OrderDefinition extends CmsDataTypeDefinition {

    /**
     * Description of Order
     */
    public function __construct() {
        parent::__construct();

        $field = new StringField("uid", "Unique Order Id");
        $field->validation = "required";
        $field->readOnly = TRUE;
        $this->addField($field);
        
        $field = new StringField("paymentId", "Payment Id");
        $field->validation = "required";
        $field->readOnly = TRUE;
        $this->addField($field);
        
//        $field = new StringField("payerId", "Payer Id");
//        $field->validation = "required";
//        $field->readOnly = TRUE;
//        $this->addField($field);
        
        $field = new RelationField("customer", "Customer");
        $field->linkTo = "Customer";
        $field->validation = "required";
        $this->addField($field);
        
        $field = new TextField("shippingAddress", "Order Shipping Address");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new TextField("billingAddress", "Order Billing Address");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new DecimalField("orderTotal", "Total Amount");
        $field->validation = "required";
        $this->addField($field);
        
        $field = new ListField("status", "Order Status");
        $field->values = getOrderStatuses();
        $field->validation = "required";
        $this->addField($field);
        
        $field = new RelationsField("entries", "Order Entries");
        $field->linkTo = "OrderEntry";
        $field->validation = "required";
        $this->addField($field);
        
        $field = new RelationField("shippingDetail", "Shipping Details");
        $field->linkTo = "ShippingDetail";
        $field->validation = "required";
        $this->addField($field);
        
        $field = new DateTimeField("dateCreated", "Order Create Date");
        $this->addField($field);
        
        $field = new DateTimeField("dateModified", "Order Modify Date");
        $this->addField($field);
        
    }

    public function listFields() {
        return array("uid", "status", "orderTotal", "customer", "dateCreated");
    }

    public function searchFields() {
        return array("uid", "status", "customer");
    }
    
    public function orderBy() {
        return "dateCreated#desc";
    }

}
