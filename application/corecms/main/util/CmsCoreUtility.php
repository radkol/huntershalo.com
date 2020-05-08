<?php

class CmsCoreUtility extends SingletonClass {
    
    private $systemFolders = array(
        "_assets","helpers","config","static","interceptors","ajax","services","templates"
    );
    
    /**
     * Return relative path to the folder containing CMS functionality
     */
    public function getCMSPath(){
        return APPPATH.$this->getCMSFolder().'/';
    }
    
    /**
     * Return the prefix that the system database tables should have.
     */
    public function getCMSPrefix(){
        return 'corecms_';
    }  
    
    /**
     * Return the cms folder
     */
    public function getCMSFolder(){
        return 'corecms';
    }
    
    /**
     * Return foldernames that representents types and modules location in the CMS
     */
    public function getAvailableFolders() {
        return array("datatypes", "modules", "childtypes");
    }
    
    /**
     * Return all site names (if more than one) which are placed in /site/ folder
     */
    public function getCustomSitesFolderNames() {
        $sites = array();
        $dir = new DirectoryIterator(APPPATH.'site');
        foreach ($dir as $fileinfo) {
            if ($fileinfo->isDir() && !$fileinfo->isDot() && !in_array($fileinfo->getFilename(), $this->systemFolders)) {
                $sites[] = $fileinfo->getFilename();
            }
        }
        return $sites;
    }
    
     /**
     * Return all site relative paths (if more than one) which are placed in /site/ folder
     */   
    public function getCustomSitesRelativePaths() {
        $sites = array();
        $dir = new DirectoryIterator(APPPATH.'site');
        foreach ($dir as $fileinfo) {
            if ($fileinfo->isDir() && !$fileinfo->isDot() && !in_array($fileinfo->getFilename(), $this->systemFolders)) {
                $sites[] = APPPATH.'site/'.$fileinfo->getFilename().'/';
            }
        }
        return $sites;
    }
    
    public function loadCustomServices() {
        $dir = new DirectoryIterator(APPPATH. 'site/services/');
        foreach ($dir as $fileinfo) {
            if(!$fileinfo->isDot()) {
                require $dir->getPath().'/'.$fileinfo->getFilename();
            }
        }
    }
    
}
