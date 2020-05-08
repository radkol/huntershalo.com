<?php

class ListingService extends SingletonClass {

    const PAGESIZE = 24;
    
    private $categoryService;
    private $requestService;
    private $paginationService;
    private $typeService;

    public function __construct() {

        // load services
        parent::__construct();
        $this->typeService = TypeService::instance();
        $this->requestService = RequestService::instance();
        $this->paginationService = PaginationService::instance();
        $this->categoryService = CategoryService::instance();
        $this->collectionService = CollectionService::instance();
    }

    /**
     * Get all parameters that can be passed in the GET request
     * for listing pages.
     * @return array()
     */
    public function getListingParameters() {
        $params = [];
        $params["sortby"] = $this->requestService->getDefaultParam("sortby", "dateCreated");
        $params["sortorder"] = $this->requestService->getDefaultParam("sortorder", "desc");
        $params["page"] = $this->requestService->getDefaultParam("page", 1);
        $params["pageSize"] = $this->requestService->getDefaultParam("pageSize", self::PAGESIZE);
        $params["category"] = $this->requestService->getDefaultParam("category", NULL);
        $params["refinementOptions"] = getRefinementOptions();

        foreach ($params["refinementOptions"] as $option => $desc) {
            $params[$option] = $this->requestService->getDefaultParam($option, NULL);
        }

        return $params;
    }

    public function getRefinementsFilterForCategoryListing($params, $topLevelCategory, $currentCategory) {
        $filter = ['where' => array(), "where_in" => array()];
        
        // uri category is top level category, check if we have parameter.
        if($currentCategory->id == $topLevelCategory->id) {
            if($params["category"]) {
                $filter["where"]["category"] = $params["category"];
            } else {
                $leafCategories = $this->categoryService->getBottomLevelCategories($topLevelCategory->id);
                $catIds = array_keys($leafCategories);
                if (!empty($catIds)) {
                    $filter["where_in"]["category"] = $catIds;
                }
            }
        } else {
            // uri category is not top level
            if($params["category"]) {
                $filter["where"]["category"] = $params["category"];
            } else {
                $filter["where"]["category"] = $currentCategory->id;
            }
        }
        
        $this->addRefinementsFilter($filter, $params);
        
        return $filter;
        
    }

    public function getRefinementsFilterForCollectionListing($params, $collection, $withRefinements = TRUE) {
        $filter = ['where' => array("collection" => $collection->id), "where_in" => array()];
        
        if($withRefinements) {
            if ($params["category"]) {
                $this->addCategoriesFilter($filter, $params["category"]);
            }
            $this->addRefinementsFilter($filter, $params);
        }
        
        return $filter;
        
    }
    
    public function getNonRefinementsFilterForCategoryListing($topLevelCategory) {
        $filter = ["where_in" => array()];
        $this->addCategoriesFilter($filter, $topLevelCategory->id);
        return $filter;
    }
    
    public function getListingFilters($params, $item, $listingType, $setAllFilters = TRUE) {
        $filter = ['where' => array($listingType => $item->id), "where_in" => array()];

        $typeFunctionName = "for" . ucfirst($listingType) . "Listing";
        $this->$typeFunctionName($filter, $params, $item, $setAllFilters);
        if ($setAllFilters) {
            $this->addRefinementsFilter($filter, $params);
        }
        return $filter;
    }

    /**
     * Called when category listing
     * @param type $filter
     * @param type $params
     * @param type $item
     */
    private function forCategoryListing(&$filter, $params, $item, $setAllFilters) {
        
        
        if (!$setAllFilters && !$params["category"]) {
            unset($filter["where"]["category"]);
            $this->addCategoriesFilter($filter, $item->id);
        } else if ($params["category"]) {
            $filter["where"]["category"] = $params["category"];
        }
    }

    /**
     * Called when collection listing
     * @param type $filter
     * @param type $params
     * @param type $item
     */
    private function forCollectionListing(&$filter, $params, $item, $setAllFilters) {
        // category filter
        if ($params["category"]) {
            $this->addCategoriesFilter($filter, $params["category"]);
        }
    }

    /**
     * Common functionality for adding refinement filters.
     * @param type $filter
     * @param type $params
     */
    public function addRefinementsFilter(&$filter, $params) {
        // refinements filter.
        foreach ($params["refinementOptions"] as $option => $desc) {
            if (isset($params[$option]) && $params[$option]) {
                $filter["where_in"][$option] = $params[$option];
            }
        }
    }

    /**
     * Common functionality for filtering by bottom level categories
     * @param array $filter
     * @param int $categoryId
     */
    private function addCategoriesFilter(&$filter, $categoryId) {
        $leafCategories = $this->categoryService->getBottomLevelCategories($categoryId);
        $catIds = array_keys($leafCategories);
        if (!empty($catIds)) {
            $filter["where_in"]["category"] = $catIds;
        }
    }

    public function getGroupedFilters($filters, $options, $withTopLevelCategories = FALSE) {

        $productType = $this->typeService->getType("Product");
        $options = array_merge(array("category"), array_keys($options));
        $refinmentsData = $productType->search()->getPaginatedRecordsColumnSet($filters, $options, -1);

        $grouped = [];

        foreach ($options as $option) {
            $grouped[$option] = [];
        }

        foreach ($refinmentsData as $refinment) {
            foreach ($options as $option) {
                if ($refinment->$option && !in_array($refinment->$option, $grouped[$option])) {
                    $grouped[$option][] = $refinment->$option;
                }
            }
        }

        $categories = $grouped["category"];
        $catObjects = [];
        foreach ($categories as $cId) {
            
            $category = $this->categoryService->getCategory($cId);
            
            if($withTopLevelCategories && !$this->categoryService->isTopLevelCategory($cId)) {
                $category = array_values($this->categoryService->getCategoryPath($cId))[0];
            }
            
            if(!isset($catObjects[$category->id])) {
                $catObjects[$category->id] = $category;
            }
        }
        
        $grouped["category"] = $catObjects;

        return $grouped;
    }

}
