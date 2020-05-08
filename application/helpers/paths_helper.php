<?php

/*
 * @author Radko Lyutskanov
 */

/**
 * Define all paths utilized in the cms
 */

/**
 * ------ Define asset related paths -------
 */

/**
 * Relative path to the upload directory.
 */
function getRelativeUploadPath() {
    return "./".getUploadDir()."/";
}

/**
 * Path to the upload directory in the plugin site folder.
 */
function getUploadDir() {
    return APPPATH."site/_assets";
}

/**
 * Generate complete asset url based on the filename, extension 
 * and upload folder path.
 */
function getAssetPath($filename, $fileExtension, $width = '',$height = '') {
    $size = '';
    if($width && $height) {
        $size = $width. UploadService::WIDTH_HEIGHT_SEPARATOR. $height;
    }
    return base_url(getUploadDir()."/{$filename}{$size}{$fileExtension}");
}

function getAssetObjectPath($assetObject , $width = '', $height = '') {
    return getAssetPath($assetObject->filename, $assetObject->extension, $width, $height);
}

/**
 * Core cms and site paths
 */
function getCmsFolder() {
    return "corecms/";
}

function getCmsFolderPath() {
    return APPPATH.getCmsFolder();
}

function getSiteFolder($sitename) {
    return "site/{$sitename}/";
}

function getInterceptorsFolder($stateFolder) {
    return APPPATH."site/interceptors/{$stateFolder}/";
}

function getAjaxFolder() {
    return APPPATH.'site/ajax/';
}
