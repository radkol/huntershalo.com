<?php

class CategoryService extends SingletonClass {

    private $categoryHierarchy;
    private $allCategories;

    public function __construct() {
        parent::__construct();
        $records = TypeService::instance()->getType("Category")->search()->getRecords();
        $this->allCategories = [];
        
        foreach($records as $cat) {
            $this->allCategories[$cat->id] = $cat;
        }
        
        $this->categoryHierarchy = $this->loadCategoryHierarchy();
        
        //debug($this->categoryHierarchy);
    }

    /**
     * Get Category from Id
     * @param type $categoryId
     * @return type
     */
    public function getCategory($categoryId) {
        
        if(!$categoryId) {
            return NULL;
        }
        
        if(isset($this->allCategories[$categoryId])) {
            return $this->allCategories[$categoryId];
        }
         
        return NULL;
    }

    /**
     * Get parent category of specified category
     * @param type $categoryId
     * @return type
     */
    public function getParentCategory($categoryId) {
        $cat = $this->getCategory($categoryId);
        return $cat == NULL ? NULL : $this->getCategory($cat->parent);
    }

    /**
     * Get category chain for certain category.
     * @param type $categoryId
     * @return array
     */
    public function getCategoryPath($categoryId) {
        $path = [];
        $this->getCategoryPathInternal($path, $categoryId);
        return $path;
    }
    
    
    private function getCategoryPathInternal(&$path, $categoryId) {
        $currentCategory = $this->getCategory($categoryId);
        if($currentCategory !== NULL && $currentCategory->id != $currentCategory->parent) {
            $this->getCategoryPathInternal($path, $currentCategory->parent);
            $path[$currentCategory->id] = $currentCategory;
        }
        
    }
    
    public function getTopLevelCategories() {
        
        $top = [];
        foreach($this->categoryHierarchy as $catKey => $catData) {
            $top[$catKey] = $catData["category"];
        }
        
        return $top;
    }
    
    public function isTopLevelCategory($categoryId) {
        return isset($this->categoryHierarchy[$categoryId]);
    }
    
    public function getBottomLevelCategories($categoryId) {
        
        $bottomCategories = [];
        $categoryData = $this->getCategoryHierarchy($categoryId);
        
        if(!$categoryData) {
            return $bottomCategories;
        }
        $this->getBottomLevelCategoriesInternal($bottomCategories, $categoryData["childs"]);
        return $bottomCategories;
    }
    
    private function getBottomLevelCategoriesInternal(&$bottomCategories, $categoryLevel) {
        
        foreach($categoryLevel as $catKey => $catData) {
            if(empty($catData["childs"])) {
                $bottomCategories[$catKey] = $catData;
            } else {
                $this->getBottomLevelCategoriesInternal($bottomCategories, $catData["childs"]);
            }
        }
    }
    
    /**
     * Get Entire Category Hierarchy. 
     * Category will be positioned as top level category
     * 
     * @param type $categoryId
     * @return array
     */
    public function getCategoryHierarchy($categoryId) {
        $category = NULL;
        $this->getCategoryHierarchyInternal($category, $this->categoryHierarchy, $categoryId);
        return $category;
    }
    
    private function getCategoryHierarchyInternal(&$category, $currentLevel, $categoryId) {
        
        if($category !== NULL) {
            return;
        }
        
        foreach($currentLevel as $catKey => $catData) {
            if($catKey == $categoryId) {
                $category = $catData;
                return;
            }
            $this->getCategoryHierarchyInternal($category, $catData["childs"], $categoryId);
        }
    }
    
    
    private function loadCategoryHierarchy() {
        $result = [];

        $topLevel = array_filter($this->allCategories, create_function('$item', 'return !$item->parent;'));

        foreach ($topLevel as $category) {
            $result[$category->id] = array("category" => $category, "childs" => array());
            $this->loadCategoryLevels($result[$category->id]["childs"], $category, $this->allCategories);
        }

        return $result;
    }

    private function loadCategoryLevels(&$result, $category, $allCategories) {

        foreach ($allCategories as $cat) {
            if ($cat->parent == $category->id) {
                $result[$cat->id] = array("category" => $cat, "childs" => array());
                $this->loadCategoryLevels($result[$cat->id]["childs"], $cat, $allCategories);
            }
        }
    }

}
