<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Radko Lyutskanov
 */

class Product extends CmsDataType {

    public function objectAsString($object) {
        return $object->name;
    }

    public function typeAsString() {
        return "Product";
    }

    public function objectUrl($object) {
        if(is_array($object)) {
            return array($object["options"]["category"], $object["id"], getUrlCompatibleString($object["name"]));
        } else {
            return array($object->category, $object->id, getUrlCompatibleString($object->name));
        }
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
}
