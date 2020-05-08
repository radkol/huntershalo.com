<?php

class UtilityService extends SingletonClass {
    
    public function getPropertyArray($objects, $propertyName) {
        if(!$objects || empty($objects) || !is_array($objects)) {
            return [];
        }
        
        if(!is_object($objects[0])) {
            return $objects;
        }
        
        return array_map(create_function('$o', 'return $o->'.$propertyName.';'), $objects);
    }
    
    /**
     * @param array $contentZones
     */
    public function extractModulesFromContentZones($contentZones) {
        $modules = [];
        foreach($contentZones as $contentZone)  {
            foreach($contentZone as $module) {
                if(isset($modules[$module['moduleType']])) {
                    $modules[$module['moduleType']][] = $module['moduleId'];
                } else {
                    $modules[$module['moduleType']] = [$module['moduleId']];
                }
            }
        }
        return $modules;
    }
    
    
}

