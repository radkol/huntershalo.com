<?php

class WebPage extends CmsDataType {

    public function objectAsString($instance) {
        return getLocalizedValueForField($instance, "name") .' Page';
    }

    public function typeAsString() {
        return "Web Page";
    }
    
    public function create($fields) {
        $this->handleUrl($fields);
        parent::create($fields);
    }
    
    public function delete($fields) {
        $fieldService = FieldService::instance();
        $idField = $fieldService->getFieldByName($fields, "id");
        $this->db->delete(getModuleToPageTablename(), array('pageid' => $idField->value));
        parent::delete($fields);
    }

    public function edit($fields) {
        $this->handleUrl($fields);
        parent::edit($fields);
    }
    
    private function handleUrl($fields) {
        
        $cmsService = CmsService::instance();
        $currentAdminSite = $cmsService->getCurrentAdminSite();
        $currentAdminLanguage = $cmsService->getCurrentAdminLanguage();
        
        // prevent overriding the URL which is based on the multilinguagel Page Name property.
        if($currentAdminLanguage->code !== CmsConstants::ADMIN_LANGUAGE_CODE) {
            return;
        }
        
        $fieldService = FieldService::instance();
        $parentField = $fieldService->getFieldByName($fields, "parent");
        $homePageField = $fieldService->getFieldByName($fields, "homePage");
        $urlField = $fieldService->getFieldByName($fields, "url");
        $websiteField = $fieldService->getFieldByName($fields, "website");
        $idField = $fieldService->getFieldByName($fields, "id");
        
        if(!$homePageField->value) {
            $currentPageUrl = url_title($fieldService->getFieldByName($fields, "name")->value,"-");
            if ($parentField->value) {
                $parentPage = $this->search()->getRecord(array("id" => $parentField->value, "website" => $websiteField->value));
                if ($parentPage != null && $parentPage->url != '') {
                    $currentPageUrl = $parentPage->url . "/" . $currentPageUrl;
                } 
            }
            $urlField->value = $currentPageUrl;
        } else {
            $urlField->value = "";
        }
        
        // check previous url is there an actual change, change all links in NavigationMenuItem
        if($idField->value) {
            $currentPage = $this->search()->getRecord(array("id" => $idField->value, "website" => $websiteField->value));
            if(isset($currentPageUrl) && $currentPage->url != $currentPageUrl) {
                $navItemType = TypeService::instance()->getType("NavigationMenuItem");
                $navItemSearch = $navItemType->search();
                $allInfluencedItems = $navItemSearch->getWhereRecords(array("webpage" => $idField->value));
                
                $navItemIds = UtilityService::instance()->getPropertyArray($allInfluencedItems, "id");
                if(count($navItemIds) > 0) {
                    $this->ci->db->where_in('id', $navItemIds);
                    $this->ci->db->update($navItemSearch->getTableName(), array("webpageUrl" => $currentPageUrl));
                }
            }
        }
        
    }
    
    public function getAllModulesForPage($pageId) {
        
        $this->db->where("pageid", $pageId);
        $this->db->order_by("position","asc");
        
        $query = $this->db->get(getModuleToPageTablename());
        $result = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $recordObj) {
                if (!isset($result[$recordObj->contentzone])) {
                    $result[$recordObj->contentzone] = array();
                }
                $result[$recordObj->contentzone][] = array(
                    "moduleId" => $recordObj->moduleid,
                    "moduleType" => $recordObj->moduletype,
                    "position" => $recordObj->position,
                    "stringRepresentation" => $recordObj->stringrepresentation
                );
            }
        }
        return $result;
    }
    
}
