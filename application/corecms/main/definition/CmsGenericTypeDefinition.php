<?php

abstract class CmsGenericTypeDefinition {
    
    private $fields = array();
    
    public function __construct() {
        
        $field = new IntegerField("id", "Unique id");
        $field->visibleAdd = false;
        $field->visibleEdit = false;
        $this->addField($field);
    }
    
    public function fields() {
        return $this->fields;
    }

    public function getFields() {
        return $this->fields;
    }
    
    public function addField($field) {
        $this->fields[$field->name] = $field;
    }
    
    public function getField($fieldname) {
        if(isset($this->fields[$fieldname])) {
            return $this->fields[$fieldname];
        }
        return NULL;
    }
    
    /**
     * Array of field names that will be displayed as a filters on admin listing section.
     */
    public abstract function searchFields();
    
    /**
     * Array of field names that will be displayed in the listing table.
     */
    public abstract function listFields();
    
    /**
     * String separated with # to form ordery by column # order by type
     */
    public abstract function orderBy();
}
