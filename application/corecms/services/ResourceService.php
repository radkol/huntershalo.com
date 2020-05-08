<?php

class ResourceService extends SingletonClass {
    
    private $labels = [];
    private $siteConf = [];
    
    public function __construct() {
        parent::__construct();
        
        // load labels
        $data = TypeService::instance()->getType("Label")->search()->getRecords();
        foreach($data as $label) {
            $this->labels[$label->code] = getLocalizedValueForField($label, "value");
        }
        $data = TypeService::instance()->getType("SiteConfig")->search()->getRecords();
        foreach($data as $config) {
            $this->siteConf[$config->fieldName] = $config->fieldValue;
        }
        $data = NULL;
    }
    
    public function getAssetUrl($assetObject, $width = '', $height = '') {
        return getAssetObjectPath($assetObject, $width, $height);
    }
    
    public function getLabel($labelKey) {
        if(isset($this->labels[$labelKey])) { 
            return $this->labels[$labelKey];
        }
        return $labelKey;
    }
    
    public function getConfig($siteConfigKey) {
        if(isset($this->siteConf[$siteConfigKey])) { 
            return $this->siteConf[$siteConfigKey];
        }
        return NULL;
    }
}

