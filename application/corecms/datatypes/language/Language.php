<?php

class Language extends CmsDataType {

    public function objectAsString($object) {
        return $object->code;
    }

    public function typeAsString() {
        return "Language";
    }

    public function create($fields) {
        $result = $this->handleDefaultLanguage($fields);
        if($result) {
            parent::create($fields);
        }
    }

    public function edit($fields) {
        $result = $this->handleDefaultLanguage($fields);
        if($result) {
            parent::edit($fields);
        }
    }

    private function handleDefaultLanguage($fields) {
        $defLangField = FieldService::instance()->getFieldByName($fields, "defaultLanguage");
        $codeField = FieldService::instance()->getFieldByName($fields, "code");
        
        $lang = $this->search()->getRecord(array("code" => $codeField->value));
        if($lang !== NULL && $lang->defaultLanguage && !$defLangField->value) {
            return FALSE;
        }
        
        if ($defLangField->value == 1) {
            //remove default flag for all languages.
            $this->db->update($this->tableName, array($defLangField->name => 0 ));
        }
        
        return TRUE;
    }
    
    public function delete($fields) {
        $defLangField = FieldService::instance()->getFieldByName($fields, "defaultLanguage");
        if ($defLangField->value == 1) {
            return;
        }
        parent::delete($fields);
    }
    

}
