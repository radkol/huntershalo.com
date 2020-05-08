<?php

class NavigationService extends SingletonClass {

    private $menus = [];
    private $navigationMenuItemType = null;
    private $listingTypes = null;
    private $listingModules = null;
    private $listingPages = null;
    private $i18nService = null;
    private $allMenusItems = null;
    
    public function __construct() {
        parent::__construct();
        $this->menus = TypeService::instance()->getType("NavigationMenu")->getNavigation();
        $this->navigationMenuItemType = TypeService::instance()->getType("NavigationMenuItem");
        $this->listingTypes = getNavigationTypes();
        $this->listingModules = getNavigationModules();
        $this->listingPages = StructureService::instance()->getWebPagesForModuleTypes(array_keys($this->listingModules));
        $this->i18nService = I18NService::instance();
        $this->allMenusItems = $this->buildWebsiteMenus();
    }

    public function getItemUrl($item, $type) {
        $url = $this->getUriForListingType($type);
        $itemType = TypeService::instance()->getType($type);
        $uri = vsprintf($url, $itemType->objectUrl($item));
        return $this->getInternalUrl($uri);
    }

    private function getInternalUrl($uri) {
        if ($this->i18nService->isMultiLanguageWebsite()) {
            return base_url($uri);
        } else {
            return lang_url($uri);
        }
    }

    public function getNavItemUrl($navigationMenuItem) {
        $items = $this->getNavItemUrls(array($navigationMenuItem));
        return $items[$navigationMenuItem->id];
    }

    public function getStaticUrl($uri = NULL) {
        return $this->getInternalUrl($uri);
    }
    
    public function getWebPageUrl($webPage) {
        return $this->getInternalUrl($webPage->url);
    }

    /**
     * Group a set of navigation items and return their urls
     * @param type $navigationMenuItems
     */
    public function getNavItemUrls($navigationMenuItems) {

        $withLinks = array_filter($navigationMenuItems, create_function('$item', 'return TypeService::instance()->getType("NavigationMenuItem")->hasItemLink($item);'));
        $noLinks = array_diff_key($navigationMenuItems, $withLinks);

        //groupBy type
        $grouped = [];
        
        foreach ($withLinks as $item) {
            $data = explode(CmsConstants::LINKLIST_FIELD_DELIMITER, $item->itemLinkData);
            $type = $data[0];
            $id = $data[1];
            if (isset($grouped[$type])) {
                $grouped[$type]['ids'][$id] = $id;
                $grouped[$type]['items'][$id] = $item;
            } else {
                $grouped[$type] = array('ids' => array($id => $id), 'items' => array($id => $item));
            }
        }
        $resultMap = [];

        // with item link
        foreach ($grouped as $type => $typeData) {

            $url = $this->getUriForListingType($type);
            if(!$url) { continue; }
            
            $objType = TypeService::instance()->getType($type);
            $objSearch = $objType->search();
            $ids = $typeData["ids"];
            $navItems = $typeData["items"];
            $objects = $objSearch->getWhereInRecords("id", $ids);
            
           
            foreach($objects as $object) {
                if(!isset($resultMap[$navItems[$object->id]->id])) {
                    $resultMap[$navItems[$object->id]->id] = $this->getStaticUrl(vsprintf($url, $objType->objectUrl($object)));
                }
            }
        }

        // without item link
        foreach ($noLinks as $item) {
            if(!isset($resultMap[$item->id])) {
                if($this->navigationMenuItemType->hasCompleteUrl($item)) {
                    $resultMap[$item->id] = $this->navigationMenuItemType->getUrl($item);
                } else {
                    $resultMap[$item->id] = $this->getStaticUrl($this->navigationMenuItemType->getUrl($item));
                }
            }
        }

        return $resultMap;
    }

    public function getMenu($menuId) {
        if (!isset($this->menus[$menuId])) {
            return [];
            //throw new Exception("Menu ---{$menuId}--- does not exists. Please create that menu.");
        }
        return $this->menus[$menuId];
    }
    
    
    /**
     * Return the url part with placeholder for specific listing type
     * @param string $type
     * @return string
     */
    public function getUriForListingType($type) {
        $compatibleModule = NULL;
        $typeUrlPattern = NULL;
        
        foreach ($this->listingModules as $module => $types) {
            if (isset($types[$type])) {
                $compatibleModule = $module;
                $typeUrlPattern = $types[$type];
                break;
            }
        }
        
        if ($compatibleModule && $typeUrlPattern && isset($this->listingPages[$compatibleModule])) {
            // get the first available page
            if (count($this->listingPages[$compatibleModule]) > 0) {
                return $this->listingPages[$compatibleModule][0]->url . '/' . $typeUrlPattern;
            }
        }

        return '';
    }
    
    /**
     * Get navigation Item from prebuild navigation items for all menus across the website.
     * @param type $menuNavItem
     * @return type
     */
    public function getMenuNavItemUrl($menuNavItem) {
        return isset($this->allMenusItems[$menuNavItem->id]) ? $this->allMenusItems[$menuNavItem->id] : $this->getNavMenuItemUrl($menuNavItem);
    }
    
    private function buildWebsiteMenus() {
        $allNavigations = [];
        foreach($this->menus as $navItems) {
            $allNavigations = array_merge($allNavigations, $navItems);
        }
        $navItemsUrlMap = $this->getNavItemUrls($allNavigations);
        return $navItemsUrlMap;
    }
    
    public function getNavMenuItemUri($menuNavItem) {
        return $this->navigationMenuItemType->getUrl($menuNavItem);
    }
    
}
