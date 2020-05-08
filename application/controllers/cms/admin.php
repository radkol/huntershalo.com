<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
 * @author Radko Lyutskanov
 */

class Admin extends CMS_Controller {
    
    private $data = array();
    private $requestedType;

    public function __construct() {
        parent::__construct();

        $this->load->helper(array("admin", "form"));
        $this->load->config("cms");
        $this->load->config("site");

        $this->handleAdminSiteAndLanguage();

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="adminerror">', '</p>');
        $this->requestedType = $this->uri->segment(3);

        $typeService = TypeService::instance();
        $this->data["webSiteType"] = $typeService->getType("WebSite");
        $this->data["languageType"] = $typeService->getType("Language");
        $this->data["webPageType"] = $typeService->getType("WebPage");
    }

    private function handleAdminSiteAndLanguage() {
        $cmsService = CmsService::instance();
        $requestService = RequestService::instance();

        $siteParam = $requestService->getParam("website");
        $languageParam = $requestService->getParam("language");

        $cmsService->setAdminSessionSite($siteParam);
        $cmsService->setAdminSessionLanguage($languageParam);

        $this->data["currentSite"] = $cmsService->getCurrentAdminSite();
        $this->data["currentLanguage"] = $cmsService->getCurrentAdminLanguage();
    }

    public function index() {

        $this->data["view"] = "dashboard";
        $this->load->view("administration", $this->data);
    }

    public function logout() {
        AuthorizationService::instance()->unsetUser();
        redirect(base_url("adminlogin"));
    }

    /**
     * Handle list operation for type
     */
    private function admin_list() {
        
        $fieldService = FieldService::instance();
        
        $objectDefinition = $this->data["objectData"]->definition;
        $objectTypeSearch = $this->data["objectData"]->search();
        $orderBy = explode("#", $objectDefinition->orderBy());
        $moduleFields = $objectDefinition->fields();
        $fieldsForListing = $objectDefinition->listFields();
        $fieldsForSearching = $objectDefinition->searchFields();

        $this->data["searchFields"] = getFilteredFieldsSet($moduleFields, $fieldsForSearching);

        FieldService::instance()->populateFieldsFromRequest($this->data["searchFields"], TRUE);

        // do we have filter request ?
        $whereClause = FieldService::instance()->createFilterForFields($this->data["searchFields"]);
        
        //debug($this->data["searchFields"]);
        // load pagination
        $paginationService = PaginationService::instance();
        $paginationConfig = $paginationService->getPaginationConfig();
        $this->data["pagination"] = $paginationService->getPagination($this->requestedType, $whereClause, $paginationConfig,$orderBy[0],$orderBy[1]);

        $this->data["fields"] = $moduleFields;
        $this->data["view"] = "content-list";
        $this->data["listFields"] = $fieldService->getFieldsByNames($moduleFields, $fieldsForListing);
        
         // Do we have asset to display ? if we do find all Assets so we can display their thumbs.
        $listingUploadFields = $fieldService->getAllUploadFields($this->data["listFields"]);
        $assetFieldsMap = [];
        
        if(count($listingUploadFields) > 0) {
            foreach($listingUploadFields as $uploadField) {
                $assetFieldsMap[$uploadField->name] = $objectTypeSearch->getFileUploadsForObjects($this->data["pagination"]->recordSet, $uploadField->name);
            }
        }
        
        $this->data["assetFieldsMap"] = $assetFieldsMap;
        
    }

    /**
     * Handle add operation for type
     */
    private function admin_add() {
        $fieldService = FieldService::instance();
        $uploadService = UploadService::instance();
        
        $typeData = $this->data["objectData"];
        $this->data["fields"] = $typeData->definition->fields();
        
        //load module for selected type and its model
        $this->data["view"] = "content-add";
        $this->data["mode"] = "add";
        $this->setValidationRules($this->data["fields"]);
        
        if (isEditOrAddFormSubmitted() && $this->form_validation->run($this) !== FALSE) {
            //check if file uploads ...
             
            $this->data["uploadErrors"] = $uploadService->upload($fieldService->getAllUploadFields($this->data["fields"]));
            $this->data["uploadErrors"] = array_merge($this->data["uploadErrors"], $uploadService->uploadMany($fieldService->getAllUploadsFields($this->data["fields"])));
            
            if (empty($this->data["uploadErrors"])) {
                $fieldService->populateFieldsFromRequest($this->data["fields"]);
                $typeData->create($this->data["fields"]);
                $this->session->set_flashdata("message", "Successfully added new {$typeData->typeName}");
                redirect(getListActionForType($this->requestedType));
            }
        }
    }

    /**
     * Handle edit operation for type
     */
    private function admin_edit() {

        $fieldService = FieldService::instance();
        $uploadService = UploadService::instance();

        $editId = $this->uri->segment(5);
        $this->data["fields"] = $this->data["objectData"]->definition->fields();

        $typeSearch = $this->data["objectData"]->search();

        // retrieve object from db
        $objectForEditing = $typeSearch->getRecord(array("id" => $editId));
        $fieldService->populateFieldsFromObject($this->data["fields"], $objectForEditing);
        
        $this->data["view"] = "content-edit";
        $this->data["mode"] = "edit";
        $this->data["editId"] = $editId;
        $this->data["object"] = $objectForEditing;
        $this->data["relations"] = array();
        
        $relationsFields = $fieldService->getAllRelationsFields($this->data["fields"]);
        // handle all relations. Populate all records
        foreach ($relationsFields as $relField) {
            $this->data["relations"][$relField->name] = $typeSearch->getRelations($relField, $editId);
        }
        
        $fileUploadsFields = $fieldService->getAllUploadsFields($this->data["fields"]);
        foreach ($fileUploadsFields as $uField) {
             $this->data["fileuploads"][$uField->name] = $typeSearch->getFileUploadsForObject($objectForEditing, $uField->name);
        }
        
        if(!isset($this->data["fileuploads"])) {
            $this->data["fileuploads"] = [];
        }
        
        if (isEditOrAddFormSubmitted()) {
            //override data from edit object with data from the request.
            $fieldService->populateFieldsFromRequest($this->data["fields"]);
            
            $this->setValidationRules($this->data["fields"]);

            if ($this->form_validation->run($this) !== FALSE) {
                $this->data["uploadErrors"] = $uploadService->upload($fieldService->getAllUploadFields($this->data["fields"]));
                $this->data["uploadErrors"] = array_merge($this->data["uploadErrors"], $uploadService->uploadMany($fieldService->getAllUploadsFields($this->data["fields"]), $this->data["fileuploads"]));
                
                // edit object
                if (empty($this->data["uploadErrors"])) {
                    $this->data["objectData"]->edit($this->data["fields"]);
                    $this->session->set_flashdata("message", "Successfully edited {$this->data["objectData"]->typeName} with id: {$editId}");
                    redirect(getListActionForType($this->data["objectData"]->typeName));
                }
            }
        }
        
        // process modules to page related actions
        $this->processRequestForModules();
    }

    /**
     * Handle delete operation for type
     */
    private function admin_delete() {

        $deleteId = $this->uri->segment(5);
        //load module for selected type and its model

        $objectForDeletion = $this->data["objectData"]->search()->getRecord(array("id" => $deleteId));
        $moduleFields = $this->data["objectData"]->definition->fields();
        FieldService::instance()->populateFieldsFromObject($moduleFields, $objectForDeletion);
        $this->data["objectData"]->delete($moduleFields);
        $this->session->set_flashdata("message", "Successfully deleted {$this->data["objectData"]->typeName} with Id: {$deleteId}");
        redirect(getListActionForType($this->data["objectData"]->typeName));
    }

    /**
     * Initial point for edit / list / add of content modules and data types
     */
    public function content() {
        $objectType = $this->requestedType;
        $objectOperation = $this->uri->segment(4);
        $objectData = TypeService::instance()->getType($objectType);

        $this->data["objectData"] = $objectData;
        $this->data["uploadErrors"] = array();
        if (!$objectOperation) {
            $objectOperation = "list";
        }
        $functionToCall = "admin_" . $objectOperation;
        $this->$functionToCall();

        $this->load->view("administration", $this->data);
    }

    private function setValidationRules($fields) {
        foreach ($fields as $field) {
            if (!$field instanceof UploadField) {
                $this->form_validation->set_rules($field->name, $field->title, $field->validation);
            }
        }
    }

    /**
     * Handle module attach, remove and select actions for web page type
     */
    private function processRequestForModules() {

        //Only instane of WebPage type will continue here...
        $moduleOperationsAvailable = showModulesPanel($this->data["objectData"]->typeName);
        if (!$moduleOperationsAvailable) {
            return;
        }
        $this->data["header"] = getHeaderForContentZone();
        $this->data["currentModules"] = $this->data["objectData"]->getAllModulesForPage($this->data["editId"]);
        $this->data["listModules"] = array();
        $this->data["listContentZone"] = array();

        $contentZone = $this->input->post("contentZone");
        $moduleType = $this->input->post("moduleType");
        $position = $this->input->post("position");
        $selectModuleButton = $this->input->post("selectModule");
        $addModuleButton = $this->input->post("addModule");
        $removeModuleButton = $this->input->post("removeModule");

        // check what kind of action we need to perform
        // 2. show modules of type
        // 3. add module to content zone
        // 4. remove module from content zone
        // action 2.
        if ($selectModuleButton) {
            $moduleTypeData = TypeService::instance()->getType($moduleType);
            $this->data["listModules"] = $moduleTypeData->search()->getRecords();
            $this->data["listModuleController"] = $moduleTypeData;
            $this->data["listContentZone"] = $contentZone;
            $this->data["moduleType"] = $moduleTypeData->typeName;
            return;
        }
        // action 3
        if ($addModuleButton && $position > 0) {
            // instance has been added to that content zone
            // create insert array
            $moduleData = $this->input->post("moduleData");
            if ($moduleData) {
                $splitData = explode("|", $moduleData);

                $insertData = array(
                    "pageid" => $this->data["editId"],
                    "moduleid" => $splitData[0],
                    "moduletype" => $moduleType,
                    "position" => $position,
                    "contentzone" => $contentZone,
                    "template" => $this->data["object"]->template,
                    "stringrepresentation" => $splitData[1]
                );
                CmsService::instance()->addModuleToPage($insertData);
                $this->session->set_flashdata("message", "Successfully attached module {$splitData[1]} to {$objectForEditing->title} page");
                redirect(base_url(getEditActionForType($this->data["objectData"]->typeName, $this->data["editId"])));
            }
            return;
        }
        
        // action 4
        if ($removeModuleButton) {
            $moduleId = $this->input->post("moduleId");
            $removeData = array(
                "pageid" => $this->data["editId"],
                "moduleid" => $moduleId,
                "moduletype" => $moduleType,
                "position" => $position,
                "contentzone" => $contentZone,
            );
            CmsService::instance()->removeModuleFromPage($removeData);
            $this->session->set_flashdata("message", "Successfully removed module of type {$moduleType} from page : {$this->data["editId"]}");
            redirect(base_url(getEditActionForType($this->data["objectData"]->typeName, $this->data["editId"])));
        }
    }

}
