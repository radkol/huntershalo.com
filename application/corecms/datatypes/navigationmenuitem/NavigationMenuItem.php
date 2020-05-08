<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Radko Lyutskanov
 */

/*
 * Default Controller for NavigationMenuItem.
 */
class NavigationMenuItem extends CmsDataType {

    public function objectAsString($object) {
        
        $str = getLocalizedValueForField($object, "name");
        
        if ($object->webpage) {
            $str .= " page url: {$object->webpageUrl} ";
        }
        
        if ($object->itemLinkData) {
            $str .= " link item: {$object->itemLinkData} ";
        }
        
        if ($object->completeUrl) {
            $str .= " completeUrl: {$object->completeUrl} ";
        }

        if ($object->documentLink) {
            $str .= " documentLink: {$object->documentLink} ";
        }
        
        if ($object->relativeUrl) {
            $str .= " relativeUrl: {$object->relativeUrl} ";
        }

        return $str;
    }

    public function typeAsString() {
        return "Navigation Menu Item";
    }

    /**
     * 
     * Override create method to add direct page url, along with complete page reference.
     */
    public function create($fields) {
        $this->handleWebPageUrl($fields);
        parent::create($fields);
    }

    public function edit($fields) {
        $this->handleWebPageUrl($fields);
        parent::edit($fields);
    }

    private function handleWebPageUrl($fields) {
        $webpageField = FieldService::instance()->getFieldByName($fields, "webpage");
        $webpageUrlField = FieldService::instance()->getFieldByName($fields, "webpageUrl");
        if ($webpageField && $webpageField->value) {
            $webPageInstance = TypeService::instance()->getSearch("WebPage")->getRecord(array("id" => $webpageField->value));
            if ($webPageInstance) {
                $webpageUrlField->value = $webPageInstance->url;
            }
        }
    }
    
    public function hasItemLink($object) {
        return !empty($object->itemLinkData);
    }
    
    
    public function getItemLink($object) {
        return $object->itemLinkData;
    }
    
    public function hasCompleteUrl($object) {
        return $object->completeUrl ? TRUE : FALSE;
    }
    
    /**
     * Examine all properties for Navigation Item and check which one to return,
     * depends on which is populated
     */
    public function getUrl($object) {
        
        if($object->webpage) {
            // return hidden page url. this is due to ease the process of retrieving the url
            // rather than querying the database.
            return $object->webpageUrl;
        }
        
        if($object->completeUrl) {
            return $object->completeUrl;
        }
        
        if($object->relativeUrl) {
            return $object->relativeUrl;
        }
        
        if($object->documentLink) {
            return $object->documentLink;
        }
        
        return '';
    }

}
