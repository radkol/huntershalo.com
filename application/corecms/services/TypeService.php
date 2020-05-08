<?php

/**
 * Singleton service handling type retrival
 * It loads all data related to specific type - datetype or moduletype
 * Loads:
 * Controller,
 * Definition,
 * Search
 */
class TypeService extends SingletonClass {
    
    // cache loaded types
    private $loadedTypes = array();
    
    // cache loaded types
    private $loadedSearchTypes = array();
    
    /**
     * Get type with all related properties.
     * $type can be controller instance or string.
     */
    public function getType($type) {
        
        $type = $this->getLowerCaseNameFromType($type);
        
        //get from cache
        if(isset($this->loadedTypes[$type])) {
            return $this->loadedTypes[$type];
        }
        
        $cmsUtility = CmsCoreUtility::instance();
        //check all locations where types could be placed.
        $availableFolders = $cmsUtility->getAvailableFolders();
        $availablePaths = array_merge($cmsUtility->getCustomSitesRelativePaths(),array($cmsUtility->getCMSPath()));
        
        foreach($availablePaths as $mainPath) {
            foreach($availableFolders as $folder) {
                
                $path = $mainPath.$folder.'/'.$type.'/';
                
                if(file_exists($path)) {
                    
                    $definitionName='';
                    $typeName='';
                    
                    $dir = new DirectoryIterator($path);
                    foreach ($dir as $fileinfo) {
                        if(!$fileinfo->isDot() && $fileinfo->isFile()) {
                            $filenameNoExtension = substr($fileinfo->getFilename(), 0 , strpos($fileinfo->getFilename(), "."));
                            if(strpos($filenameNoExtension, "Definition") !== FALSE) {
                                $definitionName = $filenameNoExtension;
                            } else {
                                $typeName = $filenameNoExtension;
                            }
                            
                            require $path.$fileinfo->getFilename();
                        }
                    }
                    
                    //load classes and set them
                    $cmsTypeInstance = new $typeName();
                    $cmsTypeInstance->folderName = $folder;
                    $cmsTypeInstance->completePath = $path;
                    $cmsTypeInstance->typeName = $typeName;
                    $cmsTypeInstance->definition = new $definitionName();
                    $cmsTypeInstance->pluginName = $this->getPluginName($path, $typeName);
                    $cmsTypeInstance->tableName = $this->getTableName($folder, $typeName, $cmsTypeInstance->pluginName);
                    
                    $this->loadedTypes[$type] = $cmsTypeInstance;
                    //create database table here if required
                    if(RequestService::instance()->getAttribute("adminRequest") && enableDbSiteGeneration()) {
                        (new DbGenerator($cmsTypeInstance))->process();
                    }
                    
                    return $this->loadedTypes[$type];
                }
            }
        }
        
        return NULL;
    }
    
    public function getSearch($type) {
        
        $type = $this->getLowerCaseNameFromType($type);
        //get from cache
        if(isset($this->loadedSearchTypes[$type])) {
            return $this->loadedSearchTypes[$type];
        }
        return $this->loadedSearchTypes[$type] = new Search($this->getType($type));
    }
    
    private function getLowerCaseNameFromType($type) {
        $searchableType = NULL;
        if(is_object($type)) {
            $searchableType = get_class($type);
        } else {
            $searchableType = $type;
        }
        return strtolower($searchableType);
    }
    
    private function getPluginName($completePathType,$type) {
        $utility = CmsCoreUtility::instance();
        $folderNames = array_merge($utility->getCustomSitesFolderNames(),array($utility->getCMSFolder()));
        
        foreach($folderNames as $folderName) {
            if(strpos($completePathType, $folderName) !== FALSE) {
                return $folderName;
            }
        }
        
        throw new Exception("Cannot find plugin among (". implode(",", $folderNames).") which contains type: {$type}");
    }
    
    private function getTableName($folderName,$type,$pluginName) {
        $tablename = $folderName."_".$type;
        $prefix = $pluginName.'_';
        return strtolower(str_replace(".", "_",$prefix.$tablename));
    }
    
}

