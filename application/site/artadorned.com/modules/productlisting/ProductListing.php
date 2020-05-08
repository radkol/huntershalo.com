<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Radko Lyutskanov
 */

/*
 * Default Controller for Product Listing
 */

class ProductListing extends CmsModuleType {

    const START_PAGE = 1;
    const RELATED_PRODUCTS_COUNT = 8;
    const COLLECTION_PRODUCTS_COUNT = 3;
    
    private $typeService;
    private $requestService;
    private $paginationService;
    
    public function __construct(){
        parent::__construct();
        $this->typeService = TypeService::instance();
        $this->requestService = RequestService::instance();
        $this->paginationService = PaginationService::instance();
    }
    
    public function typeAsString() {
        return "Product Listing";
    }

    public function process($moduleInstance) {
        $data = parent::process($moduleInstance);

        $categoryService = CategoryService::instance();
        $cartService = CartService::instance();
        $collectionService = CollectionService::instance();
        $listingService = ListingService::instance();

        $productType = $this->typeService->getType("Product");
        $category = $this->requestService->getAttribute("category");
        $product = $this->requestService->getAttribute("product");
        
        if ($category) {

            // set request params nicely parsed
            $data["requestParams"] = $listingService->getListingParameters();

            $paginationConfig = $this->paginationService->getPaginationConfig($data["requestParams"]["pageSize"], $data["requestParams"]["page"]);

            // set current uri for all links in listing page
            $data["currentUri"] = $this->requestService->getUri(TRUE, TRUE);
            
            
            // requested category in uri is not top level
            if (!$categoryService->isTopLevelCategory($category->id)) {
                $data["topLevelCategory"] = array_values($categoryService->getCategoryPath($category->id))[0];
            } else {
                $data["topLevelCategory"] = $category;
            }
            
            $data["category"] = $data["requestParams"]["category"] ? $categoryService->getCategory($data["requestParams"]["category"]) : $category;
            $refinementsFilter = $listingService->getRefinementsFilterForCategoryListing($data["requestParams"], $data["topLevelCategory"], $data["category"]);
            $noRefinementsFilter = $listingService->getNonRefinementsFilterForCategoryListing($data["topLevelCategory"]);
            
            //debug($noRefinementsFilter);
            
            // grouped refinements
            $data["refinements"] = $listingService->getGroupedFilters($noRefinementsFilter, $data["requestParams"]["refinementOptions"]);

            //debug($filters);
            // records
            $data["pagination"] = $this->paginationService->getPagination("Product", $refinementsFilter, $paginationConfig, $data["requestParams"]["sortby"], $data["requestParams"]["sortorder"]);
            // records images
            $data["productImages"] = $productType->search()->getFileUploadsForObjects($data["pagination"]->recordSet, "images", 1);
            
            
            $this->view("listing", $data);
            
            
        } else if ($product) {

            $data["product"] = $product;
            $data["collectionContent"] = $this->getRandomCollectionContent($product->collection, $this->typeService);
            $data["deliveryAndReturnContent"] = $this->typeService->getType("Content")->search()->getRecord(array("name" => "deliveryandreturns"));
            $data["collection"] = $collectionService->getCollection($product->collection);
            $data["productImages"] = $this->requestService->getAttribute("productImages");
            
            $relatedProducts = $this->getRelatedProducts($product, $productType);
            $collectionProducts = $this->getCollectionProducts($product, $productType);
            
            $data = array_merge($data, $relatedProducts ,$collectionProducts);
            $this->view("detail", $data);
        }
    }
    
    
    
    private function getCollectionProducts($product, $productType) {
        $result = ["collectionProducts" => NULL, "collectionProductsImages" => NULL];
        $filter = ['where' => ["collection" => $product->collection,'id !=' => $product->id]];
        $result["collectionProducts"] = $productType->search()->getPaginatedRecordsColumnSet($filter, array('id', 'category', 'name','price'), self::START_PAGE, self::COLLECTION_PRODUCTS_COUNT, "dateModified", "desc");
        $result["collectionProductsImages"] = $productType->search()->getFileUploadsForObjects($result["collectionProducts"], "images", 1);
        //debug($result["collectionProductsImages"]);
        return $result;
    }

    private function getRelatedProducts($product, $productType) {

        $result = ["relatedProducts" => NULL, "relatedProductsImages" => NULL];
        $relatedFacet = ResourceService::instance()->getConfig("productdetail.related.facet");
        // gets records that are from the current collection, with the same refinement, but from different category
        $filter = ['where' => ["collection" => $product->collection, "category !=" => $product->category, $relatedFacet => $product->$relatedFacet]];

        $result["relatedProducts"] = $productType->search()->getPaginatedRecordsColumnSet($filter, array('id', 'category', 'name'), self::START_PAGE, self::RELATED_PRODUCTS_COUNT);
        $result["relatedProductsImages"] = $productType->search()->getFileUploadsForObjects($result["relatedProducts"], "images", 1);

        return $result;
    }

    private function getRandomCollectionContent($collectionId, $typeService) {
        $collectionRelatedContents = $typeService->getType("CollectionContent")->search()->getWhereRecords(array("collection" => $collectionId));
        if (empty($collectionRelatedContents)) {
            return NULL;
        }
        $index = rand(0, count($collectionRelatedContents) - 1);
        return $collectionRelatedContents[$index];
    }

}
