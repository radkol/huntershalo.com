<?php

class CMS_Loader extends CI_Loader {
    
    public function __construct() {
        parent::__construct();
        
        $cmsUtility = CmsCoreUtility::instance();
        $customSitePaths = $cmsUtility->getCustomSitesRelativePaths();
        $viewPaths = array();
        $cmsPath = $cmsUtility->getCMSPath();
        foreach($customSitePaths as $sitename) {
            $viewPaths[$sitename.'views/'] = TRUE;
            $viewPaths[$sitename.'templates/'] = TRUE;
        }
        
        $viewPaths[$cmsPath.'views/'] = TRUE;
        $this->_ci_view_paths = $viewPaths;
        $this->_ci_helper_paths = array(APPPATH, BASEPATH, APPPATH.'site/');
        
    }
    
}
