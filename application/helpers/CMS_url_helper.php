<?php

//function base_url($uri = '') {
//    $currentLanguageCode = I18NService::instance()->getCurrentLanguage()->code;
//    $CI = & get_instance();
//    return $CI->config->base_url($uri."{$currentLanguageCode}/");
//}

function lang_url($uri = '', $hideDefaultLanguage = TRUE) {
    $currentLanguageCode = I18NService::instance()->getCurrentLanguage()->code;
    $defaultLanguage = I18NService::instance()->getDefaultLanguage()->code;
    
    $CI = & get_instance();
    if($defaultLanguage == $currentLanguageCode && $hideDefaultLanguage) {
         return $CI->config->base_url($uri);
    } else {
         return $CI->config->base_url("{$currentLanguageCode}/" . $uri);
    }
   
}

function uri_with_lang($lang) {
    $CI = & get_instance();
    return $CI->config->base_url($lang .'/'. uri());
}

/**
 * Return complete current URI, without host.
 *
 */
function uri() {
    $CI = & get_instance();
    $currentLanguageCode = I18NService::instance()->getCurrentLanguage()->code;
    $currentUri = $CI->uri->uri_string();
    
    if(strpos($currentUri, $currentLanguageCode. "/" ) === 0) {
        return substr($currentUri, strlen($currentLanguageCode) + 1);
    }
    
    if(strpos($currentUri, $currentLanguageCode) === 0) {
        return substr($currentUri, strlen($currentLanguageCode) + 1);
    }
    
    return $currentUri;
}

function no_lang_url() {
    return uri();
}