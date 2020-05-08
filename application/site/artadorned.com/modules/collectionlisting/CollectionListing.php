<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Radko Lyutskanov
 */

/*
 * Default Controller for Collection Listing.
 */

class CollectionListing extends CmsModuleType {
    
    const PAGESIZE_COLLECTIONS = 5;
    
    public function typeAsString() {
        return "Collection Listing";
    }

    public function process($moduleInstance) {
        $data = parent::process($moduleInstance);
        
        $typeService = TypeService::instance();
        $requestService = RequestService::instance();
        $paginationService = PaginationService::instance();
        $listingService = ListingService::instance();

        $collection = $requestService->getAttribute("collection");
        // set request params nicely parsed
        $data["requestParams"] = $listingService->getListingParameters();
        
        if ($collection) {

            $collectionType = $typeService->getType("Collection");
            $productType = $typeService->getType("Product");
            $filters = $listingService->getRefinementsFilterForCollectionListing($data["requestParams"], $collection, TRUE);
            $filterNoRefinements = $listingService->getRefinementsFilterForCollectionListing($data["requestParams"], $collection, FALSE);
            $paginationConfig = $paginationService->getPaginationConfig($data["requestParams"]["pageSize"], $data["requestParams"]["page"]);
         
            // set current uri for all links in listing page
            $data["currentUri"] = $requestService->getUri(TRUE, TRUE);
            // set the collecton
            $data["collection"] = $collection;
            $data["category"] = NULL;
            // set the landing collection image
            $data["collectionLandingImage"] = $collectionType->search()->getFileUploadForObject($collection, "landingImage");
            
            // grouped refinements
            $data["refinements"] = $listingService->getGroupedFilters($filterNoRefinements, $data["requestParams"]["refinementOptions"], TRUE);
            // records
            $data["pagination"] = $paginationService->getPagination("Product", $filters, $paginationConfig, $data["requestParams"]["sortby"], $data["requestParams"]["sortorder"]);
            // records images
            $data["productImages"] = $productType->search()->getFileUploadsForObjects($data["pagination"]->recordSet, "images", 1);
            
            // LISTING VIEW
            $this->view("listing", $data);
            
        } else {
            //DEFAULT VIEW
            $paginationConfig = $paginationService->getPaginationConfig(self::PAGESIZE_COLLECTIONS, $data["requestParams"]["page"]);
            $typeService = TypeService::instance();
            $data["pagination"] = $paginationService->getPagination("Collection", [], $paginationConfig, "id", "asc");
            $data["collectionsImages"] = $typeService->getType("Collection")->search()->getFileUploadForObjects($data["pagination"]->recordSet, "image");
            
            $this->view("landing", $data);
            
        }
    }

}
