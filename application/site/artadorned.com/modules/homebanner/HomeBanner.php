<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Radko Lyutskanov
 */
    
/*
 * Default Controller for Web Pages.
 */

class HomeBanner extends CmsModuleType {
    
    public function typeAsString() {
        return "Home Banner Carousel";
    }
    
    public function process($moduleInstance) {
        
        $data = parent::process($moduleInstance);
        
        //debug($this->definition);
        $slidesDefinitionField = FieldService::instance()->getFieldByName($this->definition->fields(),"slideItems");
        $moduleSearch = $this->search();
        $slideSearch = TypeService::instance()->getSearch("HomeBannerItem");
        $navigationService = NavigationService::instance();
        
        // get banner slides
        $bannerSlides = $moduleSearch->getChildRelations($slidesDefinitionField,$moduleInstance->id);
        
        // now for every banner find the page
        $data["slides"] = $bannerSlides;
        
        // find background image assets
        $data["images"] = $moduleSearch->getFileForAssetRelations($bannerSlides, "backgroundImage");
        
        $data["slideNavigations"] = $slideSearch->getRelationForObjects($bannerSlides, "linkLocation");
        $data["slideNavigationsUrls"] = $navigationService->getNavItemUrls($data["slideNavigations"]);
        
        $data["moduleNavigations"] = $moduleSearch->getRelations("links", $moduleInstance);
        $data["moduleNavigationsUrls"] = $navigationService->getNavItemUrls($data["moduleNavigations"]);
        
        $this->view("default",$data);
    }
    

    
}
