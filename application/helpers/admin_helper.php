<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/*
 * @author Radko Lyutskanov
 */

function getAdminPrefix() {
    return "admin/";
}

/**
 * Get url prefix which is used to render content on admin panel
 */
function getAdminPanelPrefix() {
    return getAdminPrefix()."content/";
}

/**
 * Get addition action for requested type
 */
function getAddActionForType($type) {
    return getAdminPanelPrefix() . $type . '/add/';
}

/**
 * Get edit action for requested type
 */
function getEditActionForType($type, $id) {
    return getAdminPanelPrefix() . $type . '/edit/' . $id;
}

/**
 * Get delete action for requested type
 */
function getDeleteActionForType($type, $id) {
    return getAdminPanelPrefix() . $type . '/delete/' . $id;
}

/**
 * Get delete action for particular module from particular content zone
 */
function getDeleteActionForModule($type, $id) {
    return getAdminPanelPrefix() . $type . '/delete/' . $id;
}

/**
 * Get list action for requested type
 */
function getListActionForType($type) {
    return getAdminPanelPrefix() . $type . '/list/';
}

/**
 * Get add submit action for requested type
 */
function getAddSubmitActionForType($type) {
    return getAdminPanelPrefix() . $type . '/add/submit';
}

/**
 * Get add submit action for requested type
 */
function getEditSubmitActionForType($type, $id) {
    return getAdminPanelPrefix() . $type . '/edit/' . $id . '/submit';
}

/**
 * Get delete file action
 */
function getDeleteResourceAction($type,$objectId,$fieldname,$fileId) {
    return base_url("admin/task/deleteresource/{$type}/{$objectId}/{$fieldname}/{$fileId}");
}

/**
 * Get delete file action
 */
function getDeleteUploadsResourceAction($type,$objectId,$fieldname,$fileId) {
    return base_url("admin/task/deleteuploadsresource/{$type}/{$objectId}/{$fieldname}/{$fileId}");
}

/**
 * Get system path for system activities
 */
function getTaskAction($operation) {
    return getAdminPrefix()."task/{$operation}";
}


/**
 * Get Filtered Field Set. Return either name=title pairs , or set with entire field object.
 */
function getFilteredFieldsSet($allFields, $requiredFields) {

    $result = array();
    foreach ($allFields as $field) {
        if (in_array($field->name, $requiredFields)) {
            $result[] = $field;
        }
    }
    return $result;
}

function getHeaderForContentZone() {
    return array(
        "moduleId" => "Id",
        "stringRepresentation" => "Module description",
        "position" => "Position",
        "moduleType" => "Module Type"
    );
}

/**
 * Check if module panel should be shown
 */
function showModulesPanel($type) {
    return $type == "WebPage";
}

function isEditOrAddFormSubmitted() {
    $ci = &get_instance();
    if ($ci->uri->segment(6) == "submit" || $ci->uri->segment(5) == "submit") {
        return true;
    }
     
    return false;
}

function handleTextValue($field) {
    if (isEditOrAddFormSubmitted()) {
        return set_value($field->name);
    }
    return html_escape($field->value);
}

function handleCheckboxValue($field, $value) {
    if (isEditOrAddFormSubmitted()) {
        set_checkbox($field->name, $value);
    }
    if ($field->value == 1) {
        return "checked";
    }
    return "";
}

function handleSelectValue($field, $value) {
    if (isEditOrAddFormSubmitted()) {
        return set_select($field->name, $value);
    }
    if ($field->value == $value) {
        return "selected";
    }
    return "";
}

function handleLinkListSelectValue($field, $value) {
    
    if (isEditOrAddFormSubmitted()) {
        if(isset($_POST[$field->name.'-type']) && $_POST[$field->name.'-type'] == $value) {
            return "selected='selected'";
        }
        return set_select($field->name.'-type', $value);
    }
    
    if(strpos($field->value, $value.CmsConstants::LINKLIST_FIELD_DELIMITER) !== FALSE) {
        return "selected='selected'";
    }
    
    return '';
}

function handleLinkListTextValue($field) {
    if (isEditOrAddFormSubmitted()) {
        return set_value($field->name.'-value', isset($_POST[$field->name.'-value']) ? $_POST[$field->name.'-value'] : FALSE);
    }
    
    if(strpos($field->value, CmsConstants::LINKLIST_FIELD_DELIMITER) !== FALSE) {
        return explode(CmsConstants::LINKLIST_FIELD_DELIMITER, $field->value)[1];
    }
    
    return '';
}

function handleMultiSelectValue($field, $value) {
    if (isEditOrAddFormSubmitted()) {
        return set_select($field->name, $value);
    }
    if ($field->value && in_array($value, $field->value)) {
        return "selected";
    }
    return "";
}

/**
 * Return read only attribute for field
 * 
 */
function getReadOnlyForField($field) {
    if($field->readOnly) {
        if($field instanceof RelationField || $field instanceof RelationsField) {
            return "disabled";
        } else {
            return "readonly";
        }
    }
    return "";
}

/**
 * Render content in the table for listing
 * Check the content of the current field
 * and set limit of the chars displayed to
 * prevent text overflow
 */
function renderFieldForListing($object, $field, $wordCount = 6) {
    
    $fieldName = $field->name;
    
    if($field->multiLanguage) {
        $value = getLocalizedValueForField($object, $fieldName);
    } else {
        $value = $object->$fieldName;
    }
    
    if($field instanceof RichTextField) {
        $value = strip_tags($value);
    }
    
    $words = explode(" ", $value);
    if(count($words) > $wordCount) {
        $value = implode(" ",  array_slice($words, 0, $wordCount)) . " ...";
    }
    
    return $value;
}

/**
 * Render asset thumb in the listing.
 */
function renderUploadFieldForListing($object, $uploadField, $assetFieldsMap) {
    
    if(count($assetFieldsMap) < 1 || !isset($assetFieldsMap[$uploadField->name]) || !isset($assetFieldsMap[$uploadField->name][$object->id])) {
        return '-';
    }
    $imageSrc = getAssetObjectPath($assetFieldsMap[$uploadField->name][$object->id]);
    return "<img src='{$imageSrc}' width='100' height='50' />";
}

function getMandatoryHtml($field, $withSpace = TRUE) {
    
    if(!$field || !$field->validation) {
        return '';
    }
    if(strpos($field->validation, "required") !== FALSE) {
        return $withSpace ? ' <span>*</span>' : '<span>*</span>';
    }
    return '';
}