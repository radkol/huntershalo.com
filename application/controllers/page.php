<?php

class Page extends CI_Controller {

    private $data = array();
    
    public function __construct() {
        parent::__construct();
        
        $webSiteService = WebsiteService::instance();
        $requestService = RequestService::instance();
        
        $this->load->config("site");
        
        // mark that we are processing site, not admin request
        $requestService->setAttribute("adminRequest", FALSE);
      
        // search for site, based on the current domain
        $currentWebSite = $webSiteService->getWebSiteForDomain($requestService->getDomain());
        
        // set the current site
        $webSiteService->setCurrentWebSite($currentWebSite);
    }
    
    /**
     * Begin point of custom website.
     * All Urls will pass that point first!
     */
    public function index() {
        
        CmsCoreUtility::instance()->loadCustomServices();
        
        // init services
        $requestService = RequestService::instance();
        $webSiteService = WebsiteService::instance();
        $i18nService = I18NService::instance();
        
        $this->data["defaultLanguage"] = $i18nService->getDefaultLanguage();
        $this->data["availableLanguages"] = $i18nService->getAvailableLanguages();
        
        // extract language from the url.
        // if no language set, use default instead
        
        $lang = $this->retrieveLanguage($requestService->getUri(), $this->data["defaultLanguage"]->code, $this->data["availableLanguages"]);
        
        //$i18nService->setCurrentLanguage($requestService->getParam($languageParamName));
        $i18nService->setCurrentLanguage($lang);
        
        $website = $webSiteService->getCurrentWebSite();
        
        // make these available in the page controller items
        $this->data["website"] = $website;
        $this->data["request"] = $requestService;
        $this->data["currentLanguage"] = $i18nService->getCurrentLanguage();
         
        //$this->data["languageParamName"] = $lang;    
        
        $page = $webSiteService->getWebPage($requestService->getUri(FALSE));
        
        if ($page === NULL) {
            redirect(base_url(), "refresh");
        }
        
        $this->data["page"] = $page;
        
        $this->data["navigationService"] = NavigationService::instance();
        $this->data["resourceService"] = ResourceService::instance();
        $this->data["requestService"] = $requestService;
        $this->data["sessionService"] = SessionService::instance();
        
        CmsInterceptorProcessor::instance()->process(CmsConstants::FOLDER_INITRENDER);
        
        // load template engine and render current page.
        CmsTemplateRenderer::instance()->renderPage($page);
        
    }
    
    private function retrieveLanguage($uri ,$defaultCode, $availableLanguages) {
        if(empty($uri)) {
            return $defaultCode;
        }
        $langSegment = $this->uri->segment(1);
        
        foreach($availableLanguages as $lang) {
            if(strcmp($langSegment, $lang->code) == 0) {
                return $lang->code;
            }
        }
        return $defaultCode;
    }
    
    public function getPageItem($itemName) {
        if (isset($this->data[$itemName])) {
            return $this->data[$itemName];
        }
        return null;
    }
    
    public function getPageItems() {
        return $this->data;
    }
    
    public function setPageItem($itemKey,$itemData) {
        $this->data[$itemKey] = $itemData;
    }
    
    /**
     * Handle captcha submission
     */
    public function checkCaptcha($input) {
        $storedCaptcha = $this->session->userdata("captcha");
        $this->session->unset_userdata("captcha");
        $this->load->library("form_validation");
        if (strcasecmp($storedCaptcha, $input) != 0) {
            $this->form_validation->set_message('checkCaptcha', "%s is not valid. Try again");
            return FALSE;
        }
        return TRUE;
    }
    
}
