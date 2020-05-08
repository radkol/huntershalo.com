<?php
 
class WebsiteService extends SingletonClass {

    private $currentWebsite = NULL;
    private $defaultWebsite = NULL;

    /**
     * Get Default WebSite object.
     * If not available, find it and set it
     */
    public function getDefaultWebsite() {
        if (!$this->defaultWebsite) {
            $this->defaultWebsite = TypeService::instance()->getSearch("WebSite")->getRecord(array("defaultWebsite" => 1));
        }
        return $this->defaultWebsite;
    }

    public function getCurrentWebsite() {
        if ($this->currentWebsite == NULL) {
            return $this->getDefaultWebsite();
        }
        return $this->currentWebsite;
    }

    /**
     * Set WebSite object to the service.
     */
    public function setCurrentWebSite($website) {
        $this->currentWebsite = $website;
    }

    public function getWebSiteForDomain($domain) {
        $website = TypeService::instance()->getSearch("WebSite")->getRecord(array("name" => $domain));
        if (!$website) {
            throw new Exception("Cannot find website for that domain. Please create website object {$domain} ");
        }
        return $website;
    }

    public function getWebPage($uri) {
        $maxPageLevel = $this->ci->config->item('maxpagelevel');
        $count = 0;

        $page = NULL;
        $slashCount = substr_count($uri, "/");
        while ($slashCount > $maxPageLevel - 1) {
            $lastSlash = strrpos($uri, "/");
            $uri = substr($uri, 0, $lastSlash);
            $slashCount = substr_count($uri, "/");
        }

        // load webpage type and search
        $webPageSearch = TypeService::instance()->getSearch("WebPage");

        // find page to render             
        do {
            $page = $webPageSearch->getRecord(array("url" => $uri, "website" => $this->getCurrentWebsite()->id));
            if ($page != NULL) {
                break;
            }
            $lastSlash = strrpos($uri, "/");
            if ($lastSlash === FALSE) {
                break;
            }
            $uri = substr($uri, 0, $lastSlash);
            if (++$count > $maxPageLevel) {
                break;
            }
        } while ($uri);
        
        return $page;
    }

}