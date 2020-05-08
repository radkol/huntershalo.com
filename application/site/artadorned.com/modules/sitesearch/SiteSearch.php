<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Radko Lyutskanov
 */
    
/*
 * Default Controller for Site Search.
 */

class SiteSearch extends CmsModuleType {
    
    public function typeAsString() {
        return "Site Search";
    }
    
    public function process($moduleInstance) {
        
        $data = parent::process($moduleInstance);
        
        $requestService = RequestService::instance();
        $typeService = TypeService::instance();
        $productType = $typeService->getType("Product");
        $listingService = ListingService::instance();
        $paginationService = PaginationService::instance();
        
        $data["requestParams"] = $listingService->getListingParameters();
        // set current uri for all links in listing page
        $data["currentUri"] = $requestService->getUri(TRUE, TRUE);
        $paginationConfig = $paginationService->getPaginationConfig($data["requestParams"]["pageSize"], $data["requestParams"]["page"]);
        
        $queryString = $requestService->getParam("q");
        
        $data["searchQuery"] = $queryString;
        
        if(strlen($queryString) > 2) {
            $filter = [
                "or_like" => [
                    "name" => $queryString,
                    "shortDescription" => $queryString,
                    "longDescription" => $queryString,
                    "description1" => $queryString,
                    "description2" => $queryString,
                    "description3" => $queryString,
                ]
            ];

            $data["pagination"] = $paginationService->getPagination("Product", $filter, $paginationConfig, $data["requestParams"]["sortby"], $data["requestParams"]["sortorder"]);
            $data["productImages"] = $productType->search()->getFileUploadsForObjects($data["pagination"]->recordSet, "images", 1);
            $data["noresults"] = FALSE;
        } else {
            $data["noresults"] = TRUE;
            $data["pagination"] = NULL;
        }
        $this->view("default", $data);
    }
    

    
}
