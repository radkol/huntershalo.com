<?php

class Adminajax extends CMS_Controller {
    
    public function __construct() {
        parent::__construct();

        if (!RequestService::instance()->isAjaxRequest()) {
            redirect(base_url());
        }
    }
    
    public function autocomplete($type, $limit) {
        
        $searchTerm = RequestService::instance()->getParam("term");

        $filter = array ('or_like' => array());
        
        $type = TypeService::instance()->getType($type);
        $fields = $type->definition->fields();
        $stringFields = FieldService::instance()->getAllStringFields($fields);
        
        $imageField = $this->imageField($fields);
        $currentLanguage = getCurrentLanguage();
        
        //debug($currentLanguage);
        foreach($stringFields as $sField) {
            if($sField->multiLanguage) {
                 $localizedFieldName = getLocalizedFieldName($sField->name);
                 $filter['or_like'][getLocalizedFieldName($sField->name)] = $searchTerm;
            } else {
               $filter['or_like'][$sField->name] = $searchTerm;
            }
        }
        
        $data = $type->search()->getPaginatedRecords($filter, 1, $limit);
        $resultImages = $this->resultImages($type, $data, $imageField);
        $autocompleteResult = [];
        
        foreach($data as $obj) {
            $returnData = new stdClass();
            $returnData->id = $obj->id;
            $returnData->label = '';
            $returnData->image = '';
            if($imageField["exists"]) {
                $fieldName = $imageField["field"]->name;
                if($imageField['asset']) {
                    $returnData->image = isset($resultImages[$obj->$fieldName]) ? getAssetObjectPath($resultImages[$obj->$fieldName]) : '';
                } else {
                    $returnData->image = isset($resultImages[$obj->id]) ? getAssetObjectPath($resultImages[$obj->id]) : '';
                }
            } 
            
            $returnData->label = $type->objectAsString($obj);
            
            $autocompleteResult[] = $returnData;
        }
        echo json_encode($autocompleteResult);
    }
    
    
    private function imageField($fields) {
        
        $assetFields = FieldService::instance()->getAllRelationFields($fields, "Asset");
        $uploadFields = FieldService::instance()->getAllUploadFields($fields);
        
        //find image to render.
        $uploadField = NULL;
        $assetField = NULL;
        
        if(count($uploadFields) > 0) {
            $uploadField = $uploadFields[0];
        } else if (count($assetFields) > 0) {
            $assetField = $assetFields[0];
        }
        
        if($uploadField) {
            return array("exists" => true, "asset" => false, "field" => $uploadField);
        } else if ($assetField) {
            return array("exists" => true, "asset" => true, "field" => $assetField);
        }
        
        return array("exists" => false);
    }
    
    
    private function resultImages($type, $data, $imageField) {
        if(!$imageField["exists"]) {
            return [];
        } else if ($imageField["asset"]) {
             return $type->search()->getFileForAssetRelations($data, $imageField["field"]->name);
        } else {
             return $type->search()->getFileUploadsForObjects($data, $imageField["field"]->name );
        }
    }
    
}

