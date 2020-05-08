<?php

class I18NService extends SingletonClass {
    
    const SESSION_LANGUAGE_ATTR = "language";
    
    private $defaultSiteLanguage = NULL;
    private $availableLanguages = array();
    private $multiLanguageSite = FALSE;
    
    public function __construct() {
        parent::__construct();
        $currentWebSite = WebsiteService::instance()->getCurrentWebSite();
        $this->setAvailableLanguages($currentWebSite);
        $this->setDefaultLanguage($currentWebSite);
        $this->multiLanguageSite = count($this->getAvailableLanguages()) > 1;
    }
    
    /**
     * Get available languages for Current WebSite, and store them 
     */
    private function setAvailableLanguages($currentWebSite) {
        $webSiteType = TypeService::instance()->getType("WebSite");
        $availableLanguagesField = FieldService::instance()->getFieldByName($webSiteType->definition->fields(), "availableLanguages");
        $this->availableLanguages = $webSiteType->search()->getRelations($availableLanguagesField, $currentWebSite->id);
    }
    
    /**
     * Get Default language for Current WebSite, and store it
     */    
    private function setDefaultLanguage($currentWebSite) {
        foreach($this->availableLanguages as $avLanguage) {
            if($avLanguage->id == $currentWebSite->defaultLanguage) {
                $this->defaultSiteLanguage = $avLanguage;
                break;
            }
        }
    }
    
    /**
     * Get Default Language
     */
    public function getDefaultLanguage() {
        return $this->defaultSiteLanguage;
    }
    
    /**
     * Get Available Languages
     */
    public function getAvailableLanguages() {
        return $this->availableLanguages;
    }
    
    /**
     * Get Current Language From Session (To be reconsidered)
     */
    public function getCurrentLanguage() {
        return $this->ci->session->userdata("language");
    }

    public function setCurrentLanguage($newLanguageCode = NULL) {
        $lang = $this->getCurrentLanguage();
        $sessionService = SessionService::instance();
        
        if ($lang) {
            if ($newLanguageCode && $newLanguageCode != $lang->code) {
                // We could set new language, check if valid.
                $newLang = $this->getLanguageByCode($newLanguageCode);
                if ($newLang) {
                    $sessionService->setAttribute(self::SESSION_LANGUAGE_ATTR, $newLang);
                }
            }
        } else {
            if ($newLanguageCode) {
                $newLang = $this->getLanguageByCode($newLanguageCode);
                if (!$newLang) {
                    $newLang = $this->getDefaultLanguage();
                }
            } else {
                $newLang = $this->getDefaultLanguage();
            }
            $sessionService->setAttribute(self::SESSION_LANGUAGE_ATTR, $newLang);
        }
    }
    
    public function getLanguageByCode($code) {
        foreach($this->availableLanguages as $lang) {
            if($lang->code == $code) {
                return $lang;
            }
        }
        return NULL;
    }
    
    public function isMultiLanguageWebsite() {
        return $this->multiLanguageSite;
    }
    
}
