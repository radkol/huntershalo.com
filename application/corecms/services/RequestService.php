<?php

/*
 * @author Radko Lyutskanov
 */

class RequestService extends SingletonClass {

    const VAR_QUERY_STRING = "QUERY_STRING";

    public function __construct() {
        parent::__construct();
        $this->ci->load->library('user_agent');
    }

    private $attributes = array();

    public function isMobileRequest() {
        return $this->ci->agent->mobile();
    }

    public function getAttribute($name) {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }
        return NULL;
    }

    public function setAttribute($name, $value) {
        $this->attributes[$name] = $value;
    }

    /**
     * Get query string part without '?'
     */
    public function getQueryString() {
        return $this->ci->input->server(self::VAR_QUERY_STRING);
    }

    /**
     * Check if current request is ajax
     */
    public function isAjaxRequest() {
        return $this->ci->input->is_ajax_request();
    }

    /**
     * Get request header by name
     */
    public function getHeader($name) {
        return $this->ci->input->get_request_header($name, TRUE);
    }

    /**
     * Get request headers
     */
    public function getHeaders() {
        return $this->ci->input->get_request_headers();
    }

    /**
     * Get IP of the client 
     */
    public function getIpAddress() {
        return $this->ci->input->ip_address();
    }

    public function getCookie($name) {
        $this->ci->input->cookie($name);
    }

    /**
     * Search param in 'post' request data. If not found, seek it in 'get' request data
     */
    public function getParam($paramName) {
        return $this->ci->input->get_post($paramName, TRUE);
    }

    public function getDefaultParam($paramName, $defaultValue) {
        $paramValue = $this->getParam($paramName, TRUE);
        return $paramValue ? $paramValue : $defaultValue;
    }
    
    public function setCookie($cookieData) {
//        $cookie = array(
//            'name'   => 'The Cookie Name',
//            'value'  => 'The Value',
//            'expire' => '86500',
//            'domain' => '.some-domain.com',
//            'path'   => '/',
//            'prefix' => 'myprefix_',
//            'secure' => TRUE
//        );
        $this->ci->input->set_cookie($cookieData);
    }

    public function getDomain($removeWwwPrefix = TRUE) {

        $completeDomain = $this->ci->input->server("HTTP_HOST");
        if ($removeWwwPrefix && strpos($completeDomain, "www.") === 0) {
            return str_replace("www.", "", $completeDomain);
        }

        return $completeDomain;
    }

    /**
     * Get uri string with / without language part in front of the uri
     * @param type $stripLang
     */
    public function getUri($withQueryString = FALSE, $relative = FALSE, $stripLang = FALSE) {
        $currentUri = trim(uri_string());
        $currentLang = I18NService::instance()->getCurrentLanguage();
        
        if($currentLang && $stripLang) {
            $currentUri = preg_replace("/{$currentLang->code}\/?/", "", $currentUri);
        } 
        
        if($withQueryString) {
             $qs = $this->getQueryString();  
             $currentUri = $qs ? $currentUri . '?'. $qs : $currentUri;
        }
        
        if(!$relative) {
            return $currentUri;
        }
        
        return $this->relativeUri($currentUri);

    }
    
    public function hasQueryString($uri) {
        $items = explode("?", $uri);
        return count($items) == 2 && !empty($items[1]);
    }
    
    /**
     * Check if specific uri, or current uri has parameter in its query string
     * @param type $param
     * @param type $uri
     */
    public function hasParameter($uri, $param, $value = NULL) {
        if($value === NULL) {
            $value = "[^&]+";
        }
        
        if($this->isArrayParameter($param)) {
            $param = str_replace("]", "\]", $param);
            $param = str_replace("[", "\[", $param);
        }
        return preg_match("/{$param}={$value}/", $uri);
    }
    
    /**
     * Find param from query string and replace its value with new
     * @param type $uri
     * @param type $paramName
     * @param type $newParamValue
     */
    public function replaceParameter($uri, $paramName, $newParamValue, $addIfMissing = FALSE) {
        
        //exists param with that name
        $exists = $this->hasParameter($uri, $paramName, NULL);
        if(!$exists && $addIfMissing) {
            return $this->addParameter($uri, $paramName, $newParamValue);
        }
        
        $newParamValue = htmlspecialchars($newParamValue);
        if($exists) {
            return preg_replace("/({$paramName}=[^&]+)/", $paramName . '=' . $newParamValue, $uri);
        }
        
    }
    
    /**
     * Add additional param to query string
     * @param type $uri
     * @param type $paramName
     * @param type $paramValue
     */
    public function addParameter($uri, $paramName, $paramValue) {
        
        // exact match
        $exists = $this->hasParameter($uri, $paramName, $paramValue);
        
        // don't do anything
        if($exists) {
            return $uri;
        }
        
        // there is another parameter but with different value
        $exists = $this->hasParameter($uri, $paramName, NULL);
        if($exists && !$this->isArrayParameter($paramName)) {
            return $this->replaceParameter($uri, $paramName, $paramValue);
        }
        
        $paramValue = htmlspecialchars($paramValue);
        if($this->hasQueryString($uri)) {
            return $uri. '&'. $paramName. '='. $paramValue;
        } else {
            return $uri.'?'. $paramName. '='. $paramValue;
        }
        
    }
    
    public function isArrayParameter($paramName) {
        return strpos($paramName, "[]") !== FALSE;
    }
    
    /**
     * Remove parameter from query string
     * @param type $uri
     * @param type $paramName
     */
    public function removeParameter($uri, $paramName, $paramValue = NULL) {
        if($this->isArrayParameter($paramName)) {
            $paramName = str_replace("]", "\]", $paramName);
            $paramName = str_replace("[", "\[", $paramName);
        }
        
        if($paramValue === NULL) {
            $paramValue = "[^&]+";
        }
        
        
        $cleanedUri = preg_replace("/(&?{$paramName}={$paramValue})/", "", $uri);
        $cleanedUri = str_replace("?&", "?", $cleanedUri);
        // make sure to clean the ? if that was the only parameter
        if($this->hasQueryString($cleanedUri)) {
            return $cleanedUri;
        }
        
        // clean all ?& that might occur when there are two parameters and we are removing the first one.
        return rtrim($cleanedUri, "?");
    }
    
    public function relativeUri($uri) {
        return '/'. $uri;
    }
    
}
