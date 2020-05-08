<?php

/*
 * @author Radko Lyutskanov
 */

class FieldService extends SingletonClass {

    /**
     * Populate all fields in the field set with data in the request.
     * Apply fields filtering where required.
     * 
     * @param type $fieldSet
     * @param type $excludedFieldNames
     * @param type $excludedFieldTypes
     */
    public function populateFieldsFromRequest($fieldSet, $checkLang = FALSE, $excludedFieldNames = array("id"), $excludedFieldTypes = array("UploadField","UploadsField")) {
        $requestService = RequestService::instance();
        foreach ($fieldSet as $field) {
            if (in_array(get_class($field), $excludedFieldTypes)) {
                continue;
            }
            if (in_array($field->name, $excludedFieldNames)) {
                continue;
            }

            
            if ($field instanceof LinkListField) {
                $type = $requestService->getParam($field->name . '-type');
                $value = $requestService->getParam($field->name . '-value');
                $field->value = $type && $value ? "{$type}" . CmsConstants::LINKLIST_FIELD_DELIMITER . "{$value}" : '';
                continue;
            }
            
            $fieldname = $field->name;

            if ($checkLang && $field->multiLanguage) {
                $fieldname = getLocalizedFieldName($fieldname);
            }
            $field->value = $requestService->getParam($fieldname);
        }
    }

    /**
     * Populate all fields in the field set with data in object.
     * Apply fields filtering where required.
     */
    public function populateFieldsFromObject($fieldSet, $object, $excludedFieldNames = array(), $excludedFieldTypes = array("RelationsField", "ChildField", "UploadsField")) {
        foreach ($fieldSet as $field) {
            $fieldName = $field->name;
            if (in_array(get_class($field), $excludedFieldTypes)) {
                continue;
            }
            if (in_array($fieldName, $excludedFieldNames)) {
                continue;
            }
            if ($field->multiLanguage) {
                $field->value = getLocalizedValueForField($object, $fieldName);
            } else {
                $field->value = $object->$fieldName;
            }
        }
    }

    /**
     * This method will produce array which can be directly passed to 
     * 'where' sql statement based on the request parameters.
     */
    public function createFilterForFields($filterFields) {

        $resultArray = array("like" => array(), "where" => array());
        foreach ($filterFields as $searchField) {

            //is there such filter in the request ?
            if ($searchField->value) {

                // what is the type of the field -> produce either "like" query 
                // or exact match for integers for instance
                if ($searchField instanceof IntegerField || $searchField instanceof DecimalField) {
                    $resultArray["where"][$searchField->name] = $searchField->value;
                } else {
                    $fieldname = $searchField->name;
                    if ($searchField->multiLanguage) {
                        $fieldname = getLocalizedFieldName($fieldname);
                    }
                    $resultArray["like"][$fieldname] = $searchField->value;
                }
            }
        }
        return $resultArray;
    }

    public function getFieldByName($fields, $fieldName) {
        if(isset($fields[$fieldName])) {
            return $fields[$fieldName];
        }
        return null;
    }

    public function getFieldsByNames($fields, $names) {
        $result = [];
        foreach ($names as $name) {
            $f = $this->getFieldByName($fields, $name);
            if ($f) {
                $result[] = $f;
            }
        }
        return $result;
    }

    public function getAllRelationFields($fields, $type = NULL) {
        $result = array();
        foreach ($fields as $field) {
            if ($field instanceof RelationField) {
                if ($type) {
                    if ($field->linkTo == $type) {
                        $result[] = $field;
                    }
                } else {
                    $result[] = $field;
                }
            }
        }
        return $result;
    }

    public function getAllStringFields($fields) {
        $result = array();
        foreach ($fields as $field) {
            if ($field instanceof StringField) {
                $result[] = $field;
            }
        }
        return $result;
    }

    public function getAllRelationsFields($fields, $type = NULL) {
        $result = array();
        foreach ($fields as $field) {
            if ($field instanceof RelationsField) {
                if ($type) {
                    if ($field->linkTo == $type) {
                        $result[] = $field;
                    }
                } else {
                    $result[] = $field;
                }
            }
        }
        return $result;
    }

    public function getAllChildFields($fields) {
        $result = array();
        foreach ($fields as $field) {
            if ($field instanceof ChildField) {
                $result[] = $field;
            }
        }
        return $result;
    }

    public function getAllUploadFields($fields) {
        $result = array();
        foreach ($fields as $field) {
            if ($field instanceof UploadField) {
                $result[] = $field;
            }
        }
        return $result;
    }
    
    public function getAllUploadsFields($fields) {
        $result = array();
        foreach ($fields as $field) {
            if ($field instanceof UploadsField) {
                $result[] = $field;
            }
        }
        return $result;
    }
    
    public function fillFieldsWithValues($type, $fieldNames, $fieldValues) {
        $typeDef = $type->getDefinition();
        $iterations = count($fieldNames);
        $fields = [];
        for($i = 0 ; $i < $iterations; $i++) {
            $field = $typeDef->getField($fieldNames[$i]);
            $field->value = $fieldValues[$i];
            $fields[$fieldNames[$i]] = $field;
        }
        $type = null;
        $typeDef = null;
        $fieldNames = null;
        $fieldValues = null;
        
        return $fields;
        
    }
}
