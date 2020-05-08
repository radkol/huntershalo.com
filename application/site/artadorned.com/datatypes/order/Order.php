<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Radko Lyutskanov
 */

class Order extends CmsDataType {

    public function objectAsString($object) {

        return sprintf("Order ' %s '", $object->uid);
    }

    public function typeAsString() {
        return "Placed Orders";
    }
    
    public function create($fields) {
        $dateCreateField = FieldService::instance()->getFieldByName($fields, "dateCreated");
        $dateModifyField = FieldService::instance()->getFieldByName($fields, "dateModified");

        $now = createDateTime();
        $dateCreateField->value = $now;
        $dateModifyField->value = $now;

        parent::create($fields);
    }

    public function edit($fields) {
        
        $dateModifyField = FieldService::instance()->getFieldByName($fields, "dateModified");
        if($dateModifyField) {
            $now = createDateTime();
            $dateModifyField->value = $now;
        }
        parent::edit($fields);
    }
    
    public function delete($fields) {
        
        $orderEntryType = TypeService::instance()->getType("OrderEntry");
        
        $idField = FieldService::instance()->getFieldByName($fields, "id");
        $entries = $this->search()->getRelations("entries", $idField->value);
        
        $orderEntryType->deleteObjects(UtilityService::instance()->getPropertyArray($entries, "id"));
        
        parent::delete($fields);
    }
    
    
}
