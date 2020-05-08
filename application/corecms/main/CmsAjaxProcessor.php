<?php

class CmsAjaxProcessor extends SingletonClass {
    
    const SITE_PROCESSOR_CLASSNAME = "WebsiteAjaxProcessor";
    
    public function execute($siteFunctionName)  {
        $className = self::SITE_PROCESSOR_CLASSNAME;
        require getAjaxFolder().$className.".php";
        $className::instance()->$siteFunctionName();
    }
}
