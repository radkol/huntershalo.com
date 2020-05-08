<?php

function debug($source, $exit = TRUE) {
    echo "<pre>";
    print_r($source);
    echo "</pre>";
    if ($exit) {
        die();
    }
}

function getStringWithWordLimit($source, $limit, $includeDots = false) {
    $words = explode(" ", $source);
    if (count($words) > $limit) {
        return implode(" ", array_slice($words, 0, $limit)) . ($includeDots ? '...' : '');
    }
    return $source;
}

function getRichTextWithWordLimit($source, $limit, $includeDots = false) {
    $source = strip_tags($source);
    $words = explode(" ", $source);
    if (count($words) > $limit) {
        return implode(" ", array_slice($words, 0, $limit)) . ($includeDots ? '...' : '');
    }
    return $source;
}

function getTextWithLimit($text, $limit = 50, $includeDots = false) {
    $text = strip_tags($text);
    if (strlen($text) > $limit) {
        return substr($text, 0, $limit) . ($includeDots ? '...' : '');
    }
    return $text . ($includeDots ? '...' : '');
}

function siteResourcePath($filename, $folder = 'css') {
    return base_url("application/site/static/{$folder}/{$filename}");
}

/**
 * Get correct property name while dealing with multi language fields
 * Try to retrieve the current language.
 * Check if we are in the cms console or at the frontsite itself.
 * based on that, obtain the language and produce correct property name for that
 * multi language field
 * @return string
 */
function getLocalizedFieldName($property) {
    
    $currentLanguageCode = getCurrentLanguage()->code;
    return "{$property}_{$currentLanguageCode}";
}

function getCurrentLanguage() {
    if (RequestService::instance()->getAttribute("adminRequest")) {
        return CmsService::instance()->getCurrentAdminLanguage();
    } else {
        return I18NService::instance()->getCurrentLanguage();
    }
}


function localizedValue($object,$property,$langCode = NULL) {
    return getLocalizedValueForField($object,$property,$langCode = NULL);
}

/**
 * Retrieve correct value from the object property based on the current language
 * @return string
 */
function getLocalizedValueForField($object, $property, $languageCode = NULL) {

    $localizedPropertyName = '';
    if (!$languageCode) {
        $localizedPropertyName = getLocalizedFieldName($property);
    } else {
        $localizedPropertyName = "{$property}_{$languageCode}";
    }

    $value = $object->$localizedPropertyName;
    if (!$value) {
        $defaultProperty = $property . '_' . I18NService::instance()->getDefaultLanguage()->code;
        $value = $object->$defaultProperty;
    }
    return $value;
}

/**
 * Get value that is suppose to be rendered in the table cell.
 * If empty, add placeholder "-", by default 
 */
function getValueForTableCell($value, $ifEmpty = "-") {
    if (!$value) {
        return $ifEmpty;
    }
    return $value;
}

/**
 * Get formatted date string from DateField value YYYY-MM-dd
 * Default to that format '16th May, 2015';
 */
function getFormattedDateFieldValue($dateString, $format = 'jS F\, Y') {
    return date_format(date_create($dateString), $format);
}

/**
 *  Strip all forbidden characters and remove whitespaces.
 */
function getUrlCompatibleString($sourceString, $separator = '-') {
    
    $sourceString = trim($sourceString);
    
    $patterns = array (
       "/-/" => "",
       "/\s+/" => $separator,
       "/[%\?\.\,'\"]/" => ""
    );
    
    foreach($patterns as $regex => $replace) {
        $sourceString = preg_replace($regex, $replace, $sourceString);
    }
    
    return $sourceString;
}

/**
 * Wrap String with another String from left & right
 */
function wrapString($source,$leftString = '', $rightString = '') {
    return ($leftString ? $leftString : ''). $source. ($rightString ? $rightString : '');
}

/**
 * Build File Name from object
 */
function createFileNameForObject($fileObject, $size = NULL) {
    return createFileName($fileObject->filename, $fileObject->extension, $size);
}

/**
 * Build File Name from string params
 */
function createFileName($filename, $extension , $size = NULL) {
    if($size === NULL) {
        return  $filename . $extension;
    } else {
        return  $filename . 
                $size->width. 
                UploadService::WIDTH_HEIGHT_SEPARATOR . 
                $size->height . 
                $extension;
    }
}

/**
 * Create date time compatible string
 */
function createDateTime($timestamp = NULL) {
    $format = "Y-m-d H:i:s";
    if($timestamp === NULL) {
        return date($format);
    }
    return date($format, $timestamp);
}

/**
 * Create date compatible string
 */
function createDate($timestamp = NULL) {
    $format = "Y-m-d";
    if($timestamp === NULL) {
        return date($format);
    }
    return date($format, $timestamp);
}

/**
 * Temporary solution for these 2 functions. they should not be here ...
 */

function adminResourcePath($filename,$folder = 'css') {
    return base_url(adminStaticPath()."{$folder}/{$filename}");
}

function adminStaticPath() {
    return APPPATH."corecms/static/";
}

