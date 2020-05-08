<?php

class CmsService extends SingletonClass {

    public function addModuleToPage($data) {
        $this->ci->db->insert(getModuleToPageTablename(), $data);
    }

    public function removeModuleFromPage($data) {
        $this->ci->db->delete(getModuleToPageTablename(), $data);
    }

    public function getWebsiteConfigurations() {
        return $this->ci->db->get(getSiteConfigTablename())->result();
    }

    public function unsetFileFromObject($objectType, $objectId, $fieldname, $fileId) {
        // update object record

        $typeObject = TypeService::instance()->getType($objectType);
        $fileService = FileService::instance();
        
        $this->ci->db->where('id', $objectId);
        $this->ci->db->update($typeObject->tableName, array($fieldname => NULL));
        
        $fileService->deleteFileUploads(array($fileId));
    }

    public function getAdmin($data) {

        $query = $this->ci->db->get_where("user", $data);
        $result = $query->result();

        if (count($result) != 1) {
            return null;
        }

        return $result[0];
    }
    
    public function getCurrentAdminSite() {
        return  $this->ci->session->userdata("admin_website");
    }
     
    public function getCurrentAdminLanguage() {
         return $this->ci->session->userdata("admin_language");
    }
    
    public function setAdminSessionSite($siteParam) {
        $adminSite = $this->ci->session->userdata("admin_website");
        $websiteSearch = TypeService::instance()->getSearch("WebSite");

        if ($adminSite) {
            if ($siteParam) {
                $newSite = $websiteSearch->getRecord(array("id" => $siteParam));
                $this->ci->session->set_userdata("admin_website", $newSite);
            }
        } else {
            if($siteParam) {
                $newSite = $websiteSearch->getRecord(array("id" => $siteParam));
            } else {
                $newSite = $websiteSearch->getRecord(array("defaultWebsite" => 1));
            }
            $this->ci->session->set_userdata("admin_website", $newSite);
        }
    }
    
    public function setAdminSessionLanguage($langParam) {
        $lang = $this->ci->session->userdata("admin_language");
        $langSearch = TypeService::instance()->getSearch("Language");

        if ($lang) {
            if ($langParam) {
                $newLang = $langSearch->getRecord(array("code" => $langParam));
                $this->ci->session->set_userdata("admin_language", $newLang);
            }
        } else {
            if($langParam) {
                $newLang = $langSearch->getRecord(array("code" => $langParam));
            } else {
                $newLang = $langSearch->getRecord(array("id" => $this->getCurrentAdminSite()->defaultLanguage));
            }
            $this->ci->session->set_userdata("admin_language", $newLang);
        }
    }

}
