<?php

/**
 * @author Radko Lyutskanov
 */

class StructureService extends SingletonClass {
    
    /**
     *  Return list of all WebPages which contain module of the specified type,
     *  and belongs to particular websites. If website is not provided, default
     *  website will be used.
     */
    public function getWebPagesForModuleTypes($moduleTypes, $websiteObject = NULL) {
        if(!$moduleTypes || empty($moduleTypes))  { return NULL; }
        
        $website = $websiteObject === NULL ? WebsiteService::instance()->getCurrentWebSite() : $websiteObject;
        $webpageType = TypeService::instance()->getType("WebPage");
        $modulePageRelTable = getModuleToPageTablename();
        array_walk($moduleTypes, create_function('&$item','$item = wrapString($item,"\'","\'");'));
                
        $inQuery = implode(",", $moduleTypes);
        $sqlQuery = "SELECT webpage.*, pagemodel.moduleType FROM {$webpageType->tableName} webpage INNER JOIN {$modulePageRelTable} pagemodel 
                    ON webpage.id = pagemodel.pageid WHERE pagemodel.moduleType IN ({$inQuery}) AND webpage.website={$website->id}";
        
        $query = $this->ci->db->query($sqlQuery);
        
        $result = [];
        
        foreach($query->result() as $resItem) {
            if(isset($result[$resItem->moduleType])) {
                $result[$resItem->moduleType][] = $resItem;
            } else {
                $result[$resItem->moduleType] = [$resItem];
            }
        }
        
        return $result;            
    }
    
    public function getContentModulesForType($moduleType, $webpage = NULL , $website = NULL) {
        if(!$moduleType)  { return NULL; }
    }
    
}