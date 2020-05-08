<?php

class Label extends CmsDataType {
    
    private $labelData = array();
        
    public function objectAsString($object) {
        return $object->code;
    }

    public function typeAsString() {
        return "Label";
    }
    
    public function setLabelData($data) {
        foreach($data as $configItem) {
            $this->labelData[$configItem->code] = getLocalizedValueForField($configItem, "value");
        }
    }

    public function getLabel($name) {
        if(isset($this->labelData[$name])) { 
            return $this->labelData[$name];
        }
        return $name;
    }
    
}
