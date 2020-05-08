<?php

/**
 * Check what page we are in.
 */
class SetupSearchableItemsPage implements CmsInterceptor {

    public function priority() {
        return 1;
    }

    public function run() {
        
        // debug(CategoryService::instance()->getBottomLevelCategories(3));
        $ci = &get_instance();
        
        $utilityService = UtilityService::instance();
        $requestService = RequestService::instance();
        $currentUri = $requestService->getUri();
        $currentPage = $ci->getPageItem("page");
        $currentPageUri = $currentPage->url;
        $restOfTheUri = substr($currentUri, strlen($currentPageUri)+1);
        
        // debug($restOfTheUri);
        $availableNavigationModules = getNavigationModules();
        $currentPageModules = $utilityService->extractModulesFromContentZones($ci->getPageItem("pageModules"));
        
        //debug($availableNavigationModules);
        
        $availableSearchableItems = NULL;
        $currentModuleType = NULL;
        
        foreach($availableNavigationModules as $moduleType => $searchableItems) {
            if(isset($currentPageModules[$moduleType])) {
                $availableSearchableItems = $searchableItems;
                $currentModuleType= $moduleType;
                break;
            }
        }
        
        $requestService->setAttribute("currentListingType", $currentModuleType);
        //debug($availableNavigationModules, false);
        //debug($currentModuleType);
        
        if(!$availableSearchableItems || !$currentModuleType) {
            return;
        }
        
        $foundSearchItem = NULL;
        $foundSearchUriMatch = NULL;
        
        foreach($availableSearchableItems as $searchItemType => $searchItemPattern) {
            // make it compatible for regex search
            preg_match(convertPrintToRegexPattern($searchItemPattern), $restOfTheUri, $result);
            if($result && count($result) > 1) {
                $foundSearchItem = $searchItemType;
                $foundSearchUriMatch = $result;
                break;
            }
        }
        $requestService->setAttribute("searchItemType", $searchItemType);
        
        //debug($foundSearchItem, false);
        //debug($foundSearchUriMatch);
        
        if(!$foundSearchItem || !$foundSearchUriMatch) {
            return;
        }
        
        switch($foundSearchItem) {
            
            case 'Collection' : 
                $requestService->setAttribute("collection", CollectionService::instance()->getCollection($foundSearchUriMatch[1]));
                break;
            
            case 'Category' :
                $requestService->setAttribute("category", CategoryService::instance()->getCategory($foundSearchUriMatch[1]));
                break;
            
            case 'Product' :
                $prodSearch = TypeService::instance()->getSearch("Product");
                $prod = $prodSearch->getRecord(array('id' => $foundSearchUriMatch[2]));
                $requestService->setAttribute("product", $prod);
                if($prod) {
                    $requestService->setAttribute("productImages", $prodSearch->getFileUploadsForObject($prod, "images", 4));
                }
                break;
        }
        
    }
    
    
    
}
