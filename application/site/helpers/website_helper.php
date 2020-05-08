<?php

function getSortingOptions() {
    return array(
        "dateCreated#desc" => array('sortby' => 'dateCreated','sortorder'=> 'desc'),
        "price#asc" => array('sortby' => 'price','sortorder'=> 'asc'),
        "price#desc" => array('sortby' => 'price','sortorder'=> 'desc'),
    );
}

function getRefinementOptions() {
    $ci = &get_instance();
    $refinements = $ci->config->item('refinements');
    return $refinements;
}

function getPageSizeOptions() {
    $base = ListingService::PAGESIZE;
    $sizes = [];
    for($i = 0; $i < 4; $i++) {
        $sizes[] = $base;
        $base += 12;
    }
    return $sizes;
}

/**
 * Get Navigation Types
 */
function getNavigationTypes() {
    $ci = &get_instance();
    $types = $ci->config->item('navigationTypes');
    return $types;
}

/**
 * Get Navigation Modules
 */
function getNavigationModules() {
    $ci = &get_instance();
    $types = $ci->config->item('navigationModules');
    return $types;
}

/**
 * Get Order Statuses
 */
function getOrderStatuses() {
    $ci = &get_instance();
    $types = $ci->config->item('orderStatuses');
    return $types;
}

function convertPrintToRegexPattern($printPattern) {
    $printPattern = str_replace("%s", ".+", $printPattern);
    $printPattern = str_replace("%d", "(\d+)", $printPattern);
    $printPattern = str_replace("/", "\/", $printPattern);
    $printPattern = wrapString($printPattern, "/^", "$/");
    return $printPattern;
}

function withCurrency($price) {
    return "&dollar;".$price;
}

function withFormat($price) {
    return number_format($price, 2, '.','');
}

function getOrderStatusLabel($orderStatusCode) {
    foreach(getOrderStatuses() as $code => $label) {
        if($code == $orderStatusCode) {
            return $label;
        }
    }
    return null;
}

/**
 * Set validation for add address form
 */
function setAddressValidation() {
    $ci = &get_instance();
    $ci->load->library('form_validation');
    //$ci->form_validation->set_error_delimiters('<p>', '</p>');
    $ci->form_validation->set_rules("firstName", "First Name", "required");
    $ci->form_validation->set_rules("lastName", "Last Name", "required");
    $ci->form_validation->set_rules("addressLine1", "Address Line 1", "required");
    $ci->form_validation->set_rules("addressLine2", "Address Line 2", "");
    $ci->form_validation->set_rules("country", "Country", "required");
    $ci->form_validation->set_rules("city", "City", "required");
    $ci->form_validation->set_rules("postcode", "Postcode", "required");
    $ci->form_validation->set_rules("phone", "Phone", "required");
}

/**
 * Check if specific form is submitted
 */
function isFormSubmitted($submitButtonParamName) {
    $requestService = RequestService::instance();
    if ($requestService->getParam($submitButtonParamName)) {
        return TRUE;
    }

    return FALSE;
}
