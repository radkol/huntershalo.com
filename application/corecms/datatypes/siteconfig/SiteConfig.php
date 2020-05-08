<?php

class SiteConfig extends CmsDataType {
    
    private $configData = array();
    
    public function objectAsString($object) {
        return $object->fieldTitle;
    }

    public function typeAsString() {
        return "Site Configuration";
    }

    public function setConfigData($data) {
        foreach($data as $configItem) {
            $this->configData[$configItem->fieldName] = $configItem->fieldValue;
        }
    }

    public function getConfigProperty($name) {
        if(isset($this->configData[$name])) { 
            return $this->configData[$name];
        }
        return NULL;
    }

}
