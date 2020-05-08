<?php

/**
 * Class that will handle creation / update of database tables.
 */
class DbGenerator {

    private $db = NULL;
    private $type = NULL;

    public function __construct($type) {
        $this->type = $type;
        $this->db = $type->db;
    }

    public function process() {

        $tablename = $this->type->tableName;
        
        $query = $this->db->query("SHOW TABLES LIKE '{$tablename}'");
        // table doesn't exists. Hence create it.

        if ($query->num_rows() != 1) {
            $this->create($tablename);
            return;
        }

        // check for alterations.
        $this->update($tablename);
    }

    private function getNullOption($field) {
        return (strpos($field->validation, "required") !== FALSE) ? " NOT NULL " : "";
    }

    private function create($tablename) {

        $allLanguages = TypeService::instance()->getSearch("Language")->getRecords();

        $createQuery = "CREATE TABLE IF NOT EXISTS `{$tablename}` (\n";
        $typeFields = $this->type->definition->fields();
        
        foreach ($typeFields as $field) {
            
            if ($field instanceof RelationsField || $field instanceof UploadsField) {
                continue;
            }
            
            if ($field->name == 'id') {
                $createQuery .= "`{$field->name}` {$field->dbColumnType} NOT NULL, \n";
                continue;
            }
            
            $generatedNull = $this->getNullOption($field);
            if ($field->multiLanguage) {
                foreach ($allLanguages as $language) {
                    $createQuery .= "`{$field->name}_{$language->code}` {$field->dbColumnType} {$generatedNull},\n";
                }
            } else {
                $createQuery .= "`{$field->name}` {$field->dbColumnType} {$generatedNull},\n";
            }
        }

        $createQuery = substr($createQuery, 0, strrpos($createQuery, ","));
        $createQuery .= ") ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8; \n";
        // $createQuery .= "ALTER TABLE `{$tablename}` ADD PRIMARY KEY (`id`); \n";
        //  $createQuery .= "ALTER TABLE `{$tablename}` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";
        // debug($createQuery);
        $this->db->query($createQuery);
        $this->db->query("ALTER TABLE `{$tablename}` ADD PRIMARY KEY (`id`)");
        $this->db->query("ALTER TABLE `{$tablename}` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1");

//                CREATE TABLE IF NOT EXISTS `asset` (
//                    `id` int(11) NOT NULL,
//                      `name` varchar(50) NOT NULL,
//                      `description` varchar(100) NOT NULL,
//                      `file` int(11) NOT NULL
//                    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
//            ;
    }

    private function getLanguageCompatibleFieldSet($languages, $fieldName) {
        $result = array();
        foreach ($languages as $language) {
            $result[] = "{$fieldName}_{$language->code}";
        }
        return $result;
    }

    private function update($tablename) {
        
        $query = $this->db->query("DESCRIBE {$tablename}");
        $dbFieldsData = $query->result();

        $allLanguages = TypeService::instance()->getSearch("Language")->getRecords();
        
        $defaultLanguage = NULL;
        foreach($allLanguages as $lang) {
            if($lang->defaultLanguage) {
                $defaultLanguage = $lang;
            }
        }
        
        //first try to create non existing fields (new fields).
        $typeFields = $this->type->definition->fields();

        // for every field
        foreach ($typeFields as $field) {

            if ($field->name == "id" || $field instanceof RelationsField || $field instanceof UploadsField) {
                continue;
            }

            // check if we have to add 
            $generatedNull = $this->getNullOption($field);

            if ($field->multiLanguage) {
                
                // check if field exists but it is not multiLanguage
                $notMultiLanguageField = $this->findFieldDescription($dbFieldsData, $field);
                
                // if non multi language exists, we need to convert it to multi language
                // so rename the column to the one with default language suffix.
                if ($notMultiLanguageField) {
                    // for every language available ...
                    foreach ($allLanguages as $language) {
                        $query = '';
                        // rename the normal field name to field with default language suffix
                        if ($language->code == $defaultLanguage->code) {
                            $query = "ALTER TABLE `{$tablename}` CHANGE `{$field->name}` `{$field->name}_{$language->code}` {$field->dbColumnType} {$generatedNull}";
                        } else {
                            $query = "ALTER TABLE `{$tablename}` ADD `{$field->name}_{$language->code}` {$field->dbColumnType} {$generatedNull}";
                        }
                        //debug($query, false);
                        $this->db->query($query);
                        // execute query.
                    }
                } else {
                    foreach ($allLanguages as $language) {
                        $query = '';
                        $existingLanguageField = $this->findFieldDescription($dbFieldsData, $field, $language->code);
                        if ($existingLanguageField) {
                            if (strcasecmp($existingLanguageField->Type, $field->dbColumnType) != 0) {
                                $query = "ALTER TABLE `{$tablename}` CHANGE `{$field->name}_{$language->code}` `{$field->name}_{$language->code}` {$field->dbColumnType} {$generatedNull}";
                            }
                        } else {
                            $query = "ALTER TABLE `{$tablename}` ADD `{$field->name}_{$language->code}` {$field->dbColumnType} {$generatedNull}";
                        }
                        if ($query) {
                            $this->db->query($query);
                        }
                    }
                }
            } else {
                
                // check if there was language field with that name.
                $existingLanguageField = $this->findFieldDescription($dbFieldsData, $field, $defaultLanguage->code);
                //debug($existingLanguageField,false);
                $tbType = $this->findFieldDescription($dbFieldsData, $field);
                
                if ($existingLanguageField) {
                    // rename the language field and drop everything else
                    $query = "ALTER TABLE `{$tablename}` CHANGE `{$field->name}_{$defaultLanguage->code}` `{$field->name}` {$field->dbColumnType} {$generatedNull}";
                    // execute query
                    $this->db->query($query);
                    foreach ($allLanguages as $language) {
                        if ($language->code != $defaultLanguage->code) {
                            $langField = $this->findFieldDescription($dbFieldsData, $field, $language->code);
                            if ($langField) {
                                $query = "ALTER TABLE `{$tablename}` DROP `{$field->name}_{$language->code}`";
                                $this->db->query($query);
                            }
                        }
                    }
                } else {
                    $query = '';
                    // create completely new field
                    if ($tbType === NULL) {
                        $query = "ALTER TABLE `{$tablename}` ADD `{$field->name}` {$field->dbColumnType} {$generatedNull}";
                    } else {
                        // field found check if change
                        if (strcasecmp($tbType->Type, $field->dbColumnType) != 0) {
                            $query = "ALTER TABLE `{$tablename}` CHANGE `{$field->name}` `{$field->name}` {$field->dbColumnType} {$generatedNull}";
                        }
                    }
                    if ($query) {
                        $this->db->query($query);
                    }
                }

//        (
//            [Field] => id
//            [Type] => int(11)
//            [Null] => NO
//            [Key] => PRI
//            [Default] => 
//            [Extra] => auto_increment
//        )
            }
        }
    }

    private function findFieldDescription($dbFieldsData, $field, $languageCode = NULL) {

        $fieldName = $languageCode !== NULL ? $field->name . '_' . $languageCode : $field->name;

        foreach ($dbFieldsData as $fieldDescription) {
            if ($fieldName == $fieldDescription->Field) {
                return $fieldDescription;
            }
        }

        return NULL;
    }

}
