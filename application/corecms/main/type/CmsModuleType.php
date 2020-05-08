<?php

abstract class CmsModuleType extends CmsGenericType {
    
    public function objectAsString($object) {
        return $object->name;
    }
    
    public function typeAsString() {
        return ucfirst(get_called_class());
    }
    
    public function getItem($itemKey) {
        return $this->ci->getPageItem($itemKey);
    }
    
    public function process($instance) {
        $data = [];
        $data["moduleController"] = $this;
        $data["module"] = $instance;
        return $data;
    }

    public function post($paramName) {
        return RequestService::instance()->getParam($paramName);
    }
    
    public function view($viewName, $viewData) {
        $this->ci->load->view(strtolower(get_called_class()).'/'.$viewName, $viewData);
    }

    protected function detailPage() {
        $completeUrl = no_lang_url();
        $pagePartUrl = $this->getItem("page")->url;

        $detailPart = substr($completeUrl, strlen($pagePartUrl) + 1);
        $arguments = explode("/", $detailPart);
        if ($arguments && count($arguments) > 1 && is_numeric($arguments[0])) {
            return array(
                "detailpage" => true,
                "id" => $arguments[0],
                "name" => $arguments[1]
            );
        }
        return array(
            "detailpage" => false
        );
    }

    public function allowAddAction() { 
        return true;
    }
    
    public function allowDeleteAction() {
        return true;
    }
    
    public function allowEditAction() { 
        return true;
    }

    public function delete($fields) {
       parent::delete($fields);
       $idField = FieldService::instance()->getFieldByName($fields, "id");
       $this->db->where("moduletype", $this->typeName);
       $this->db->where("moduleid", $idField->value);
       $this->db->delete(getModuleToPageTablename());
    }
    
}
