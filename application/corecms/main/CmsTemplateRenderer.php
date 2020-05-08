<?php

/*
 * @author Radko Lyutskanov
 */

class CmsTemplateRenderer extends SingletonClass {
    
    public function renderPage($page) {
        
        $appPageController = &get_instance();
        
        // load modules for current page
        $webPageType = TypeService::instance()->getType("WebPage");
        $appPageController->setPageItem("pageModules", $webPageType->getAllModulesForPage($page->id));
        
        $templateItems = $appPageController->getPageItems();
        
        CmsInterceptorProcessor::instance()->process(CmsConstants::FOLDER_BEFORERENDER);
        
        //load the header
        $appPageController->load->view("common/head", $templateItems);
        // load page template
        $appPageController->load->view($page->template, $templateItems);
        // load the footer
        $appPageController->load->view("common/foot", $templateItems);
        
        CmsInterceptorProcessor::instance()->process(CmsConstants::FOLDER_AFTERRENDER);
    }
    
    public function renderZone($zoneName, $wrapHtmlOpen = '', $wrapHtmlClose = '') {
        
        $appPageController = &get_instance();
        
        $contentZoneData = $appPageController->getPageItem("pageModules");

        if (!array_key_exists($zoneName, $contentZoneData)) {
            return;
        }

        $currentZoneData = $contentZoneData[$zoneName];

        if (count($currentZoneData) && $wrapHtmlOpen) {
            echo $wrapHtmlOpen;
        }
        
        foreach ($currentZoneData as $moduleData) {
            $moduleType = TypeService::instance()->getType($moduleData["moduleType"]);
            $moduleInstance = $moduleType->search()->getRecord(array("id" => $moduleData["moduleId"]));
            $moduleType->page = $appPageController->getPageItem("page");
            $moduleType->process($moduleInstance);
        }

        if (count($currentZoneData) && $wrapHtmlClose) {
            echo $wrapHtmlClose;
        }
    }
    
}
