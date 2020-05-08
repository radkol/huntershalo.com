<?php

class NavigationMenu extends CmsDataType {

    public function objectAsString($object) {
        return $object->name;
    }

    public function typeAsString() {
        return "Navigation Menu";
    }
    
    /**
     * Retrieve all navigation menus for that website.
     * @return type
     */
    public function getNavigation() {
        
        $search = $this->search();
        $menuObjects = $search->getRecords();
        $result = array();
        $fields = $this->definition->fields();
        $menuItemField = FieldService::instance()->getFieldByName($fields, "menuItems");
        
        //$search->getRelationsForObjects("menuItems", $menuObjects);
        
        foreach($menuObjects as $menuObject) {
            $result[$menuObject->name] = $search->getRelations($menuItemField, $menuObject->id);
        }
        
        return $result;
    }
    
}
