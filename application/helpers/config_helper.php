<?php

/*
 * @author Radko Lyutskanov
 */

function getContentModules() {
    $ci = &get_instance();
    $publicModules = $ci->config->item('contentmodules');
    return $publicModules;
}

function enableDbSiteGeneration() {
    $ci = &get_instance();
    $enableDbGeneration = $ci->config->item('enable_db_site_generation');
    return $enableDbGeneration;
}

function getDefaultLanguageCode() {
    $ci = &get_instance();
    $defCode = $ci->config->item('default_language_code');
    return $defCode;
}

function enableDBCoreGeneration() {
    $ci = &get_instance();
    $publicModules = $ci->config->item('enable_db_core_generation');
    return $publicModules;
}

function getEmailConfig() {
    $ci = &get_instance();
    $emailConfig = $ci->config->item('emailconfig');
    return $emailConfig;
}

function getEmailSenderConfig() {
    $ci = &get_instance();
    $emailConfig = $ci->config->item('emailsender');
    return $emailConfig;
}

function getEmailSubjects() {
    $ci = &get_instance();
    $emailConfig = $ci->config->item('emailsubjects');
    return $emailConfig; 
}

function getInternalEmailAddress() {
    $ci = &get_instance();
    $internalEmails = $ci->config->item('internalemail');
    return $internalEmails[0]; 
}

function getInternalEmailAddresses() {
    $ci = &get_instance();
    $internalEmails = $ci->config->item('internalemail');
    return $internalEmails; 
}


function getDatatypes() {
    $ci = &get_instance();
    $publicTemplates = $ci->config->item('datatypes');
    return $publicTemplates;
}

function getNavigationMenus() {
    $ci = &get_instance();
    $menus = $ci->config->item('menus');
    return $menus;
}

/**
 * Get Default Language set
 */
function getTemplates() {
    $ci = &get_instance();
    $templates = $ci->config->item('templates');
    return $templates;
}

/**
 * Admin navigation
 */
function getAdminNavigation() {
    $ci = &get_instance();
    $adminNavigation = $ci->config->item('adminNavigation');
    return $adminNavigation;
}

function getCustomSitesNavigation() {
    $ci = &get_instance();
    $adminNavigation = $ci->config->item('customsites');
    return $adminNavigation;
}

function getLanguageParamName() {
    $ci = &get_instance();
    $adminNavigation = $ci->config->item('language_param_name');
    return $adminNavigation;
}
