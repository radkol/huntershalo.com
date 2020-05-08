<?php

class FileService extends SingletonClass {
    
    public function deleteFileUploads($fileUploadIds = []) {
        
        if(!$fileUploadIds || empty($fileUploadIds)) {
            return;
        }
        
        $this->ci->db->where_in("id", $fileUploadIds);
        $query = $this->ci->db->get(getFileTablename());
        $records = $query->result();
        $relativePath = getRelativeUploadPath();
        $fileTablename = getFileTablename();
        
        foreach($records as $record) {
            $sizes = json_decode($record->sizes);
            
            unlink($relativePath.createFileNameForObject($record));
            foreach($sizes as $size) {
                unlink($relativePath.createFileNameForObject($record, $size));
            }
        }
        
        $delQuery = "DELETE FROM {$fileTablename} WHERE id IN (".implode(",", $fileUploadIds).')';
        $this->ci->db->query($delQuery);
        
    }
    
    public function getFileForId($fileId) {
        $query = $this->ci->db->get_where(getFileTablename(), array("id" => $fileId))->result();
        return count($query) ? $query[0] : NULL;
    }
}

