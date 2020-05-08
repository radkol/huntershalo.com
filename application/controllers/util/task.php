<?php

class Task extends CMS_Controller {

    public function __construct() {
        parent::__construct();
    }
    
    public function cleanassets() {
        $this->load->helper("file");
        $this->db->select('filename, extension');
        $query = $this->db->get('file');

        $dbFiles = array();
        foreach ($query->result() as $item) {
            $dbFiles[$item->filename . $item->extension] = TRUE;
        }

        $uploadedFiles = get_filenames(getRelativeUploadPath());
        foreach ($uploadedFiles as $filename) {
            if (!isset($dbFiles[$filename])) {
                unlink(getRelativeUploadPath() . $filename);
            }
        }
        
        $this->session->set_flashdata("message", "File cleanup operation finished successfully");
        redirect(getAdminPrefix());
    }
    
    public function cleanfiles() {
        
        $types  = ["Asset" => "file", "Product" => "image"];
        
        $this->db->select('id');
        
        $fileIds = [];
        $query = $this->db->get("file");
        foreach ($query->result() as $item) {
            $fileIds[] = $item->id;
        }
        
        $usedFileIds = [];
        foreach($types as $type => $property) {
            $records = TypeService::instance()->getSearch($type)->getRecords();
            foreach($records as $record) {
                $usedFileIds[$record->$property] = FALSE;
            }
        }
        
        foreach($fileIds as $fileId) {
            if(isset($usedFileIds[$fileId])) {
                unset($usedFileIds[$fileId]);
            }
        }
        
        //debug($usedFileIds);
    }
    
    /**
     * Remove file from upload field from the edit action.
     */
    public function deleteresource() {
        $objectType = $this->uri->segment(4);
        $objectId = $this->uri->segment(5);
        $fieldName = $this->uri->segment(6);
        $fileId = $this->uri->segment(7);
        CmsService::instance()->unsetFileFromObject($objectType, $objectId, $fieldName, $fileId);
        redirect(getEditActionForType($objectType, $objectId));
    }
    
    /**
     * Remove file from upload field from the edit action.
     */
    public function deleteuploadsresource() {
        $objectType = $this->uri->segment(4);
        $objectId = $this->uri->segment(5);
        $fieldName = $this->uri->segment(6);
        $fileId = $this->uri->segment(7);
        
        // delete fileuploadsrelations record
        $where = array(
            "typename" => $objectType,
            "typeid" => $objectId,
            "fieldname" => $fieldName,
            "fileuploadid" => $fileId
        );
        $this->db->delete(getFileUploadsRelationsTablename(), $where);
        
        $fileService = FileService::instance();
        
        // delete files from asset folder.
        $fileObject = $this->db->get_where(getFileTablename(), array("id" => $fileId))->row();
        $fileService->deleteFileUploads(array($fileObject->id));
        
        redirect(getEditActionForType($objectType, $objectId));
    }
    
    

}
