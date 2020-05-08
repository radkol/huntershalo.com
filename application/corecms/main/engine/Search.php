<?php

/**
 * Perform DB Queries
 */
class Search {

    private $tableName = NULL;
    private $typeName = NULL;
    private $db = NULL;
    private $fields = NULL;

    public function __construct($type) {
        $this->tableName = $type->tableName;
        $this->typeName = $type->typeName;
        $this->fields = $type->definition->fields();
        $this->db = $type->ci->db;
        $type = null;
    }

    public function __get($name) {
        return $this->$name;
    }

    /**
     * Get list of records of current object type
     * @return Array of Objects
     */
    public function getRecords($orderByColumn = 'id', $orderByType = 'asc') {
        return $this->getRecordsFromTable($this->getTableName(), $orderByColumn, $orderByType);
    }

    /**
     * Get list of records of specific object type / tablename
     * @return Array of Objects
     */
    public function getRecordsFromTable($tablename, $orderByColumn = 'id', $orderByType = 'asc') {
        $this->db->order_by($orderByColumn, $orderByType);
        $query = $this->db->get($tablename);
        return $query->result();
    }

    public function getFilteredRecords($filterData, $orderByColumn = 'id', $orderByType = 'ASC') {
        return $this->getFilteredRecordsFromTable($this->getTableName(), $filterData, $orderByColumn, $orderByType);
    }

    public function getFilteredRecordsFromTable($tablename, $filterData, $orderByColumn = 'id', $orderByType = 'ASC') {
        $this->populateFilterQuery($filterData, $orderByColumn, $orderByType);
        $query = $this->db->get($tablename);
        return $query->result();
    }

    /**
     * $filterData is array produced from @FieldsService->createFilterForFields or it is custom created array
     *  array("where" => ... , "like" =>
     */
    private function populateFilterQuery($filterData, $orderByColumn = 'id', $orderByType = 'asc') {
        if (!empty($filterData["like"])) {
            foreach ($filterData["like"] as $likeFilterKey => $likeFilterValue) {
                $this->db->like($likeFilterKey, $likeFilterValue);
            }
        }
        if (!empty($filterData["where"])) {
            foreach ($filterData["where"] as $whereFilterKey => $whereFilterValue) {
                $this->db->where($whereFilterKey, $whereFilterValue);
            }
        }
        if (!empty($filterData["or_like"])) {
            foreach ($filterData["or_like"] as $orLikeFilterKey => $orLikeFilterValue) {
                $this->db->or_like($orLikeFilterKey, $orLikeFilterValue);
            }
        }
        if (!empty($filterData["where_in"])) {
            foreach ($filterData["where_in"] as $whereInKey => $whereInValue) {
                $this->db->where_in($whereInKey, $whereInValue);
            }
        }
        $this->db->order_by($orderByColumn, $orderByType);
    }

    public function getResultsCount($filterData = array()) {
        $this->populateFilterQuery($filterData);
        return $this->db->count_all_results($this->getTableName());
    }

    public function getPaginatedRecords($filterData, $pageNumber = 1, $pageSize = 10, $orderByColumn = 'id', $orderByType = 'asc') {

        $this->populateFilterQuery($filterData, $orderByColumn, $orderByType);
        if ($pageNumber == 1) {
            $this->db->limit($pageSize);
        } else {
            $this->db->limit($pageSize, ($pageNumber - 1) * $pageSize);
        }

        $query = $this->db->get($this->getTableName());
        return $query->result();
    }

    public function getPaginatedRecordsColumnSet($filterData, $columnSet, $pageNumber = 1, $pageSize = 10, $orderByColumn = 'id', $orderByType = 'asc') {
        $this->db->select(implode(",", $columnSet));
        $this->populateFilterQuery($filterData, $orderByColumn, $orderByType);
        
        if($pageNumber > 0) {
            if ($pageNumber == 1) {
                $this->db->limit($pageSize);
            } else {
                $this->db->limit($pageSize, ($pageNumber - 1) * $pageSize);
            }
        }
        
        $query = $this->db->get($this->getTableName());
        return $query->result();
    }

    public function getWhereRecords($where, $orderByColumn = 'id', $orderByType = 'ASC') {
        $this->db->order_by($orderByColumn, $orderByType);
        $query = $this->db->get_where($this->getTableName(), $where);
        return $query->result();
    }

    public function getWhereRecordsColumnSet($where, $columnSet, $orderByColumn = 'id', $orderByType = 'ASC') {
        $this->db->select($columnSet);
        $this->db->order_by($orderByColumn, $orderByType);
        $query = $this->db->get_where($this->getTableName(), $where);
        return $query->result();
    }

    /**
     * Get Array of filtered records. Return only specified columns, not entire object
     * $columnSet -> array  array("id","name") etc.
     * $filterData is array produced from @FieldsService->createFilterForFields or it is custom created array
     *  array("where" => ... , "like" =>
     */
    public function getFilteredRecordsColumnSet($filterData, $columnSet, $orderByColumn = 'id', $orderByType = 'ASC') {
        $this->db->select(implode(",", $columnSet));
        $this->populateFilterQuery($filterData, $orderByColumn, $orderByType);
        $query = $this->db->get($this->getTableName());
        return $query->result();
    }

    /**
     * Get list of records of specific object type / tablename with IN filtering
     * @return Array of Objects
     */
    public function getWhereInRecordsFromTable($tablename, $whereField, $data, $orderByColumn = 'id', $orderByType = 'asc') {
        if ($orderByColumn && $orderByType) {
            $this->db->order_by($orderByColumn, $orderByType);
        }
        $this->db->where_in($whereField, $data);
        $query = $this->db->get($tablename);
        return $query->result();
    }

    /**
     * Get list of records of specific object type with IN filtering
     * @return Array of Objects
     */
    public function getWhereInRecords($whereField, $data, $orderByColumn = 'id', $orderByType = 'asc') {
        return $this->getWhereInRecordsFromTable($this->getTableName(), $whereField, $data, $orderByColumn, $orderByType);
    }

    /**
     * Get single record of current object type with filter conditions applied
     * @return Single Object
     */
    public function getRecord($whereData) {
        return $this->getRecordFromTable($this->getTableName(), $whereData);
    }
    
    public function getRecordColumnSet($columnSet, $whereData) {
        $this->db->select(implode(",", $columnSet));
        return $this->getRecord($whereData);
    }
    
    /**
     * Get single record of specific type with filter conditions applied
     * @return Single Object
     */
    public function getRecordFromTable($tablename, $whereData) {

        $query = $this->db->get_where($tablename, $whereData);

        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return null;
    }

    /**
     * Retrieve list of objects of type, which is linked to current object type.
     */
    public function getRelations($field, $object) {
        if (is_string($field)) {
            $field = FieldService::instance()->getFieldByName($this->fields, $field);
        }
        if (is_object($object)) {
            $objectId = $object->id;
        } else {
            $objectId = $object;
        }
        
        $relationType = TypeService::instance()->getType($field->linkTo);
        $fromTypeName = $this->getTypeName();
        $relationsTable = getRelationsTablename();
        $sqlQuery = "SELECT tbl.* FROM {$relationType->tableName} tbl INNER JOIN {$relationsTable} rel "
                . "ON tbl.id = rel.toid WHERE rel.fromid={$objectId} AND "
                . "rel.fromtype='{$fromTypeName}' AND rel.field='{$field->name}' "
                . "AND totype='{$relationType->typeName}' ORDER BY rel.id ASC";
        $query = $this->db->query($sqlQuery);
        return $query->result();
    }
    
    /**
     * Get relations for multiple objects
     * @param type $field
     * @param type $objects
     */
    public function getRelationsForObjects($field, $objects) {
        if (is_string($field)) {
            $field = FieldService::instance()->getFieldByName($this->fields, $field);
        }
        $objectIds = UtilityService::instance()->getPropertyArray($objects,"id");
        $inQuery = implode(",", $objectIds);
        $relationType = TypeService::instance()->getType($field->linkTo);
        $fromTypeName = $this->getTypeName();
        $relationsTable = getRelationsTablename();
        $sqlQuery = "SELECT rel.fromid as sourceid, tbl.* FROM {$relationType->tableName} tbl INNER JOIN {$relationsTable} rel "
                . "ON tbl.id = rel.toid WHERE rel.fromid IN ({$inQuery}) AND "
                . "rel.fromtype='{$fromTypeName}' AND rel.field='{$field->name}' "
                . "AND totype='{$relationType->typeName}' ORDER BY rel.id ASC";
        $query = $this->db->query($sqlQuery);
        
        $result = [];
        foreach($query->result() as $resultItem) {
            if(isset($result[$resultItem->sourceid])) {
                $result[$resultItem->sourceid][] = $resultItem;
            } else {
                $result[$resultItem->sourceid] = [$resultItem];
            }
        }
        
        return $result;
        
    }
    
    /**
     * Retrieve list of child objects of type, which is linked to current object type.
     */
    public function getChildRelations($field, $objectId) {
        $relationType = TypeService::instance()->getType($field->linkTo);
        $fromTypeName = $this->getTypeName();
        $relationsTable = getChildRelationsTablename();
        $sqlQuery = "SELECT tbl.* FROM {$relationType->tableName} tbl INNER JOIN {$relationsTable} rel "
                . "ON tbl.id = rel.toid WHERE rel.fromid={$objectId} AND "
                . "rel.fromtype='{$fromTypeName}' AND rel.field='{$field->name}' "
                . "AND totype='{$relationType->typeName}' ORDER BY rel.id ASC";
        $query = $this->db->query($sqlQuery);
        return $query->result();
    }

    public function getTableName() {
        return $this->tableName;
    }

    protected function getTypeName() {
        return $this->typeName;
    }

    public function getRelationForObjects($objects, $fieldName) {
        $objectIds = UtilityService::instance()->getPropertyArray($objects, "id");
        $inQuery = implode(",", $objectIds);
        $field = FieldService::instance()->getFieldByName($this->fields, $fieldName);
        $linkType = TypeService::instance()->getType($field->linkTo);
        $linkTablename = $linkType->tableName;
        $sourceTablename = $this->getTableName();
        $sqlQuery = "SELECT source.id as sourceid, target.* from {$sourceTablename} as source "
                . "INNER JOIN {$linkTablename} as target ON source.{$fieldName} = target.id "
                . "WHERE source.id IN ({$inQuery})";

        $query = $this->db->query($sqlQuery);
        $result = array();
        foreach ($query->result() as $row) {
            $result[$row->sourceid] = $row;
        }
        return $result;
    }

    public function getRelationForObject($object, $fieldName) {
        $result = $this->getRelationForObjects(array($object), $fieldName);
        if (count($result) > 0) {
            return $result[$object->id];
        }
        return NULL;
    }

    /**
     * Get list of all files, attached to object as assets, not as upload field.
     */
    public function getFilesForObject($object, $fieldName) {
        $result = $this->getFilesForObjects(array($object), $fieldName);
        if (isset($result[$object->id])) {
            return $result[$object->id];
        }
        return array();
    }

    /**
     * Get list of all files, attached to set of objects as assets, not as upload field.
     */
    public function getFilesForObjects($objects, $fieldName) {
        $objectIds = UtilityService::instance()->getPropertyArray($objects, "id");
        $inQuery = implode(",", $objectIds);
        $assetTable = getAssetTablename();
        $objectType = $this->getTypeName();
        $sqlQuery = "SELECT r.fromid as sourceId,f.* from `file` f inner join {$assetTable} a  ON f.id = a.file "
                . "inner join relations r ON a.id=r.toid WHERE r.totype='asset' AND r.fromid IN ({$inQuery}) and r.fromtype='{$objectType}' and r.field='{$fieldName}' ORDER BY r.id";
        $query = $this->db->query($sqlQuery);

        $resultAsObjectIdToFiles = array();

        foreach ($query->result() as $fileData) {
            if (!isset($resultAsObjectIdToFiles[$fileData->sourceId])) {
                $resultAsObjectIdToFiles[$fileData->sourceId] = array($fileData);
            } else {
                $resultAsObjectIdToFiles[$fileData->sourceId][] = $fileData;
            }
        }
        return $resultAsObjectIdToFiles;
    }

    public function getFileForAsset($asset) {
        $results = $this->getFilesForAssets(array($asset));
        if (isset($results[$assetId])) {
            return $results[$assetId];
        }
        return NULL;
    }

    public function getFileForAssetRelation($object, $fieldname) {
        return $this->getFileForAssetRelations(array($object), $fieldname);
    }

    public function getFileForAssetRelations($objects, $fieldname) {
        if (empty($objects)) {
            return array();
        }
        $assetIds = UtilityService::instance()->getPropertyArray($objects, $fieldname);
        return $this->getFilesForAssets($assetIds);
    }

    public function getFilesForAssets($assetIdSet) {

        if (empty($assetIdSet)) {
            return array();
        }

        $assetTable = getAssetTablename();
        $fileTable = getFileTablename();

        $inCondition = implode(",", $assetIdSet);
        $sqlQuery = "SELECT a.id as assetid,f.* from `{$fileTable}` f INNER JOIN `{$assetTable}` a ON f.id=a.file WHERE a.id IN ({$inCondition}) order by a.id";

        $query = $this->db->query($sqlQuery);
        $resultAsAssetIdFileObject = array();
        foreach ($query->result() as $row) {
            $resultAsAssetIdFileObject[$row->assetid] = $row;
        }

        return $resultAsAssetIdFileObject;
    }

    public function getFileUploadForObjects($objects, $uploadFieldName) {
        $objectIds = UtilityService::instance()->getPropertyArray($objects, "id");
        $result = array();
        if (empty($objectIds)) {
            return $result;
        }
        $inQuery = implode(",", $objectIds);
        $tblName = $this->getTableName();
        $filename = getFileTablename();
        $sqlQuery = "SELECT source.id as sourceid,target.* from {$tblName} as source "
                . "INNER JOIN {$filename} as target ON source.{$uploadFieldName} = target.id "
                . "WHERE source.id IN ({$inQuery})";

        $query = $this->db->query($sqlQuery);

        foreach ($query->result() as $row) {
            $result[$row->sourceid] = $row;
        }
        return $result;
    }

    public function getFileUploadForObject($object, $uploadFieldName) {
        $result = $this->getFileUploadForObjects(array($object), $uploadFieldName);
        if (!empty($result)) {
            return $result[$object->id];
        }
        return NULL;
    }

    public function getFileUploadsForObject($object, $uploadsFieldName, $limit = FALSE) {
        $result = $this->getFileUploadsForObjects(array($object), $uploadsFieldName, $limit);
        if (!empty($result)) {
            return $result[$object->id];
        }
        return [];
    }

    public function getFileUploadsForObjects($objects, $uploadsFieldName, $limit = FALSE) {
        $objectIds = UtilityService::instance()->getPropertyArray($objects, "id");
        $result = array();
        if (empty($objectIds)) {
            return $result;
        }
        $inQuery = implode(",", $objectIds);
        $tblName = $this->getTableName();
        $filename = getFileTablename();
        $fileuploadsname = getFileUploadsRelationsTablename();
        $sqlQuery = "SELECT filerelations.id as relationid, source.id as sourceid, files.* from {$tblName} as source"
                . " INNER JOIN {$fileuploadsname} as filerelations ON source.id=filerelations.typeid"
                . " INNER JOIN {$filename} as files ON filerelations.fileuploadid=files.id"
                . " WHERE source.id IN ({$inQuery}) AND filerelations.typename='{$this->typeName}'"
                . " AND filerelations.fieldname='{$uploadsFieldName}' ORDER BY filerelations.id";

        $query = $this->db->query($sqlQuery);

        foreach ($query->result() as $row) {
            
            if (isset($result[$row->sourceid])) {
                if($limit && (count($result[$row->sourceid]) > $limit) ) {
                    continue;
                }
                $result[$row->sourceid][] = $row;
            } else {
                $result[$row->sourceid] = array($row);
            }
        }
        return $result;
    }

}
