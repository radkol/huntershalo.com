<?php

abstract class CmsGenericType {
    
    /**
     * @var type Contains Definition instance for that type
     */
    private $definition;
    
    /**
     * @var type Contains folder name that type is placed in
     */
    private $folderName;
    
    /**
     * @var type Complete path, relative to application where this type resides.
     */
    private $completePath;
    
   /**
    * @var type real type name as string
    */
    private $typeName;
    
    /**
     * @var type Plugin name / folder where this type resides - custom or cms 
     */
    private $pluginName;
    
    /**
     * @var type Name of the table where that type is placed in the db.
     */
    private $tableName;
    
    protected $ci;
    
    protected $db;
    
    public function __construct() {
       $this->ci = &get_instance();
       $this->db = $this->ci->db;
    }
    
    public function __get($name) {
        return $this->$name;
    }
    
    public function __set($name,$value) {
        $this->$name = $value;
    }
    
    /**
     * Method that will display object as string
     */
    abstract function objectAsString($object);
    
    /**
     * Method that will display type as String
     */
    abstract function typeAsString();
    
    /**
     * Specify whether that type can be added from the admin panel
     */
    abstract function allowAddAction();
    
     /**
     * Specify whether that type can be edited from the admin panel
     */   
    abstract function allowEditAction();
    
    /**
     * Specify whether that type can be deleted from the admin panel
     */
    abstract function allowDeleteAction();
    
    /**
     * Return Definition Object for that type.
     * @return type
     */
    public function getDefinition() {
        return $this->definition;
    }
    
    private function notManyToManyRelation($field) {
        return !$field instanceof RelationsField && !$field instanceof ChildField && !$field instanceof UploadsField;
    }
    
    /**
     * Produce array with object data stored in fields, adjusted to be inserted into db
     * @return array
     */
    private function prepareData($fields) {
        
        $insertData = array();
        
        foreach ($fields as $field) {
            if ($this->notManyToManyRelation($field)) {
                
                if($field->multiLanguage) {
                    $name = getLocalizedFieldName($field->name);
                } else {
                    $name = $field->name;
                }
                $insertData[$name] = $field->value;
            }
            if ($field instanceof PasswordField) {
                $insertData[$field->name] = md5($field->value);
            }
        }
        // auto generated unique id
        unset($insertData["id"]);
        return $insertData;
    }

    private function deleteRelationsFields($objectId) {
        $relationsTablename = getRelationsTablename();
        $objectType = $this->typeName;
        $sqlQuery = "DELETE FROM {$relationsTablename} WHERE (fromtype='{$objectType}' AND fromid={$objectId}) OR (totype='{$objectType}' AND toid={$objectId})";
        $this->db->query($sqlQuery);
    }
    
    private function deleteChildRelationFields($objectId) {
        $relationsTablename = getChildRelationsTablename();
        $objectType = $this->typeName;
        $sqlQuery = "DELETE FROM {$relationsTablename} WHERE (fromtype='{$objectType}' AND fromid={$objectId}) OR (totype='{$objectType}' AND toid={$objectId})";
        $this->db->query($sqlQuery);
    }

    /**
     * Add relations records to table storing the relations between objects
     * @return void
     */
    private function addRelationsFields($relationsFields, $insertId) {

        $objectType = $this->typeName;
        
        foreach ($relationsFields as $field) {
            foreach ($field->value as $toId) {
                $insertData = array(
                    "fromid" => $insertId,
                    "fromtype" => $objectType,
                    "toid" => $toId,
                    "totype" => $field->linkTo,
                    "field" => $field->name
                );
                //debug($insertData,false);
                $this->db->insert(getRelationsTablename(), $insertData);
            }
        }
    }
    
    /**
     * Add child records to table storing the relations between objects
     * @return void
     */
    private function addChildFields($childRelationsFields, $insertId) {

        $objectType = $this->typeName;
        foreach ($childRelationsFields as $field) {
            foreach ($field->value as $toId) {
                $insertData = array(
                    "fromid" => $insertId,
                    "fromtype" => $objectType,
                    "toid" => $toId,
                    "totype" => $field->linkTo,
                    "field" => $field->name
                );
                $this->db->insert(getChildRelationsTablename(), $insertData);
            }
        }
    }
    
    public function handleUploadsFields($allFields, $objectId) {
        
        $uploadsFields = FieldService::instance()->getAllUploadsFields($allFields);
        $currentObject = $this->search()->getRecord(array("id" => $objectId));
        $fileTablename = getFileTablename();
        
        foreach ($uploadsFields as $uploadsField) {
            
            $sizes = json_encode($uploadsField->sizes);
            $existing = $this->search()->getFileUploadsForObject($currentObject, $uploadsField->name);
            
            // for easiness just update the existing with the sizes
            
            $fileIds = UtilityService::instance()->getPropertyArray($existing, "id");
            
            if(!empty($fileIds)) {
                $sql = "UPDATE {$fileTablename} SET sizes='{$sizes}' WHERE id IN (". implode(",", $fileIds) . ")";
                $this->db->query($sql);
            }
            
            foreach($uploadsField->uploadFields as $field) {
                
                // name is position{$i}
                $newFileIndex = substr($field->name, -1);
                
                //debug($field->name);
                if ($field->filename && $field->extension) {
                    
                    $data = array(
                        "filename" => $field->filename,
                        "filetype" => $field->filetype,
                        "extension" => $field->extension,
                        "filesize" => $field->filesize,
                        "imageWidth" => $field->imageWidth,
                        "imageHeight" => $field->imageHeight,
                        "originalFilename" => $field->originalFilename,
                        "isImage" => $field->isImage,
                        "sizes" => $sizes
                    );
                                    
                    if(isset($existing[$newFileIndex])) {
                        // update record.
                        $this->db->where('id', $existing[$newFileIndex]->id);
                        $this->db->update(getFileTablename(), $data);
                    } else {
                        $this->db->insert(getFileTablename(), $data);
                        $insertId = $this->db->insert_id();
                        
                        // insert into file upload relations table now
                        $data = array(
                            "typeid" => $objectId,
                            "typename" => $this->typeName,
                            "fileuploadid" => $insertId,
                            "fieldname" => $uploadsField->name
                        );
                        $this->db->insert(getFileUploadsRelationsTablename(), $data);
                        
                    }
                }
                
            }
            
        }
    }
    
    /**
     * Handle field upload. Since every file should be unique based on its filename
     * which is generated as md5(filename+extension + timestamp), first check if currently uploaded file
     * is overriding file with same filename. If it does, update its properties,
     * otherwise create new record in database.
     */
    private function handleUploadFields($allFields) {
        $uploadFields = FieldService::instance()->getAllUploadFields($allFields);

        foreach ($uploadFields as $field) {
            if ($field->filename && $field->extension) {
                $insertData = array(
                    "filename" => $field->filename,
                    "filetype" => $field->filetype,
                    "extension" => $field->extension,
                    "filesize" => $field->filesize,
                    "imageWidth" => $field->imageWidth,
                    "imageHeight" => $field->imageHeight,
                    "originalFilename" => $field->originalFilename,
                    "isImage" => $field->isImage,
                    "sizes" => json_encode($field->sizes)
                );
                
                $fileObject = $this->db->get_where(getFileTablename(), array("filename" => $field->filename));
                if ($fileObject->num_rows() == 1) {
                   
                    // update only sizes array.
                    $fileObject =  $fileObject->result()[0];
                    $this->db->where('id', $fileObject->id);
                    $this->db->update(getFileTablename(), array("sizes" => $insertData["sizes"]));
                    $field->value = $fileObject->id;
                } else {
                    $this->db->insert(getFileTablename(), $insertData);
                    $field->value = $this->db->insert_id();
                }
//                if ($fileObject && $fileObject->id) {
//                    $this->db->where('id', $fileObject->id);
//                    $this->db->update(getFileTablename(), $insertData);
//                    $field->value = $fileObject->id;
//                } else {      //check if there is already file with that unique filename. If there is, override it
//                $fileObject = $this->getRecordFromTable(getFileTablename(), array("filename" => $field->filename));
//                if ($fileObject && $fileObject->id) {
//                    $this->db->where('id', $fileObject->id);
//                    $this->db->update(getFileTablename(), $insertData);
//                    $field->value = $fileObject->id;
//                } else {
//                    $this->db->insert(getFileTablename(), $insertData);
//                    $field->value = $this->db->insert_id();
//                }
            }
        }
    }
    
    /**
     * Edit instance of current object type using its fields populated with data
     * @return void
     */
    public function edit($fields) {
        
        $this->handleUploadFields($fields);
        
        $tablename = $this->tableName;
        $idField = FieldService::instance()->getFieldByName($fields, "id");
        $insertData = $this->prepareData($fields);
        
        $relationsFields = FieldService::instance()->getAllRelationsFields($fields);
        $childFields = FieldService::instance()->getAllChildFields($fields);
        
        if(count($relationsFields) > 0) {
            //remove all previous relations and recreate them.
            $this->db->delete(getRelationsTablename(), array('fromid' => $idField->value, "fromtype" => $this->typeName));
        }
        
        if(count($childFields) > 0) {
            $this->db->delete(getChildRelationsTablename(), array('fromid' => $idField->value, "fromtype" => $this->typeName));
        }
        
        if(!empty($insertData)) {
            $this->db->where('id', $idField->value);
            $this->db->update($tablename, $insertData);
        }
        $this->addRelationsFields($relationsFields, $idField->value);        
        $this->addChildFields($childFields, $idField->value);
        
        // handle uploads fields
        $this->handleUploadsFields($fields, $idField->value);
        
        return $idField->value;
    }

    
    public function deleteObjects($objectIds) {
        foreach($objectIds as $id) {
            $this->db->delete($this->tableName, array('id' => $id));
        }
    }
    
    /**
     * Delete instance of current object type using its fields as filter
     * @return void
     */
    public function delete($fields) {
        $idField = FieldService::instance()->getFieldByName($fields, "id");
        $this->db->delete($this->tableName, array('id' => $idField->value));
        
        $relationsFields = FieldService::instance()->getAllRelationsFields($fields);
        $childFields = FieldService::instance()->getAllChildFields($fields);
        
        if(count($relationsFields) > 0) {
            //delete relations too..
            $this->deleteRelationsFields($idField->value);
        }
        
        if(count($childFields) > 0) {
            //delete child relations too..
            $this->deleteChildRelationFields($idField->value);
        }

        $fileService = FileService::instance();
        
        //delete upload files too..
        $uploadFilesIds = array();
        foreach (FieldService::instance()->getAllUploadFields($fields) as $field) {
            if($field->value) {
                $uploadFilesIds[] = $field->value;
            }
        }
        $fileService->deleteFileUploads($uploadFilesIds);
        
        // delete uploads files too
        foreach (FieldService::instance()->getAllUploadsFields($fields) as $field) {
            
            $whereData = array('typeid' => $idField->value, "typename" => $this->typeName, "fieldname" => $field->name);
            //first get all files that needs to be removed.
            $relations = $this->db->get_where(getFileUploadsRelationsTablename(), $whereData)->result();
            
            $fileids = UtilityService::instance()->getPropertyArray($relations, "fileuploadid");
            $this->db->delete(getFileUploadsRelationsTablename(), array('typeid' => $idField->value, "typename" => $this->typeName));
            
            $fileService->deleteFileUploads($fileids);
        }
    }
    
    /**
     * Create instance of current object type using its fields populated with data
     * @return void
     */
    public function create($fields) {
        
        $fieldService = FieldService::instance();
        $this->handleUploadFields($fields);
        $insertData = $this->prepareData($fields);
        
        //debug($insertData);
        
        $this->db->insert($this->tableName, $insertData);
        
        $insertedId = $this->db->insert_id();
        
        //handle relation fields
        $this->addRelationsFields($fieldService->getAllRelationsFields($fields), $insertedId);
        
        //handle child fields
        $this->addChildFields($fieldService->getAllChildFields($fields), $insertedId);
        
        // handle uploads fields
        $this->handleUploadsFields($fields, $insertedId);
        
        return $insertedId;
    }
    
    public function rawInsert($rawStdObject) {
        $insertData = (array)$rawStdObject;
        $this->db->insert($this->tableName, $insertData);
    }
    
    
    public function search() {
        return TypeService::instance()->getSearch($this->typeName);
    }
}
