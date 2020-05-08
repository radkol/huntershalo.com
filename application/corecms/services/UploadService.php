<?php

/*
 * @author Radko Lyutskanov
 */

class UploadService extends SingletonClass {

    const WIDTH_HEIGHT_SEPARATOR = "x";

    public function upload($uploadFields) {
        $errors = array();
        $relativeUploadPath = getRelativeUploadPath();
        foreach ($uploadFields as $field) {
            $uploadConfig = $this->loadUploadConfig($field);
            $this->ci->load->library('upload', $uploadConfig);
            if (!$this->ci->upload->do_upload($field->name)) {
                if (strpos($field->validation, "required") !== FALSE && !$field->value) {
                    $errors[$field->name] = $this->ci->upload->display_errors('<p>', '</p>');
                }
            } else {
                $this->populateUploadFieldFromUploadInfo($field, $this->ci->upload->data());
                $hashedFilename = $this->generateUniqueFilename($field);
                rename($relativeUploadPath . $field->filename, $relativeUploadPath . $hashedFilename . $field->extension);
                $field->filename = $hashedFilename;
            }

            if (isset($errors[$field->name])) {
                continue;
            }

            $fileService = FileService::instance();
            $record = NULL;

            // -- check if we are editing record. if we do --
            // 1. retrieve that record , get its sizes  and check the submitted sizes. 
            // 2. if there is a difference, override the existings sizes with the new sizes.
            // 3. if there is no change, don't do anything
            // new sizes
            $newSizes = $this->buildNewSizes($field->name . '-sizes');
            
            $unchangedSizes = [];
            
            // we are editing record 
            if ($field->value) {
                $record = $fileService->getFileForId($field->value);
                if ($record) {
                    // we have new upload here so remove the old one with its sizes
                    if ($this->isNew($field)) {
                        $fileService->deleteFileUploads(array($field->value));
                    } else {
                        // there is no new field, but we might have change the sizes so check that
                        $oldSizes = json_decode($record->sizes);
                        
                        // sizes that should be removed
                        $obsoleteSizes = array_udiff($oldSizes, $newSizes, array($this, 'equalSizes'));
                        
                        // sizes that are unchanged
                        $unchangedSizes = $this->findUnchangedSizes($oldSizes,$newSizes);
                        
                        // sizes that are newly added
                        $newSizes = array_udiff($newSizes, $oldSizes, array($this, 'equalSizes'));

                        foreach($obsoleteSizes as $removeSize) {
                            unlink($relativeUploadPath.createFileNameForObject($record, $removeSize));
                        }
                    }
                }
            }

            if (!$this->isNew($field) && $record) {
                $field->filename = $record->filename;
                $field->extension = $record->extension;
            }
            
            $newSizes = array_merge($newSizes, $unchangedSizes);
            
            $this->generateSizes($field, $newSizes, $relativeUploadPath);
            $field->sizes = $newSizes;
            
//            if(!$field->saveOriginal) {
//                // remove the original upload. only its sizes will be used.
//                unlink($relativeUploadPath.createFileName($field->filename,$field->extension));
//            }
            
        }
        return $errors;
    }
    
    
    private function generateSizes($object, $newSizes, $relativeUploadPath) {
        if(count($newSizes) > 0) {
            // load the library
            $this->ci->load->library('image_lib');
            foreach ($newSizes as $sizeItem) {
                $config['image_library'] = 'gd2';
                $config['source_image'] = $relativeUploadPath . createFileName($object->filename, $object->extension);
                $config['new_image'] = createFileName($object->filename, $object->extension, $sizeItem);
                $config['quality'] = "80%";
                $config['width'] = $sizeItem->width;
                $config['height'] = $sizeItem->height;
                $config['maintain_ratio'] = FALSE;
                if ($sizeItem->resize) {
                    $config['master_dim'] = "auto";
                } else {
                    if ($sizeItem->xAxis) {
                        $config['x_axis'] = $sizeItem->xAxis;
                    }
                    if ($sizeItem->yAxis) {
                        $config['y_axis'] = $sizeItem->yAxis;
                    }
                }

                $this->ci->image_lib->initialize($config);

                if ($sizeItem->resize) {
                    $this->ci->image_lib->resize();
                } else {
                    $this->ci->image_lib->crop();
                }
                $this->ci->image_lib->clear();
            }
        }
        
    }
    
    
    private function findUnchangedSizes($oldSizes, $newSizes) {
        $unchanged = [];
        foreach($oldSizes as $oldSize) {
            $equalFound = false;
            foreach($newSizes as $newSize) {
                if($this->testEqualSizes($oldSize, $newSize)) {
                    $equalFound = true;
                    break;
                }
            }
            if($equalFound) {
                $unchanged[] = $oldSize;
            }
        }
        return $unchanged;
    }
    
    
    private function testEqualSizes($size1, $size2) {
        return 
            $size1->width == $size2->width &&
            $size1->height == $size2->height &&
            $size1->resize == $size2->resize &&
            $size1->crop == $size2->crop &&
            $size1->xAxis == $size2->xAxis &&
            $size1->yAxis == $size2->yAxis;
    }
    
    /**
     * Compare two size objects if they are equal
     */
    private function equalSizes($size1, $size2) {
        if($this->testEqualSizes($size1, $size2)) {
            return 0;
        } else if( $size1->width > $size2->width ) {
            return 1;
        } else {
            return -1;
        }
    }

    // "{$field->name}-sizes"
    private function buildNewSizes($requestParam) {
        $sizes = [];
        $inRequest = RequestService::instance()->getParam($requestParam);
        if ($inRequest && is_array($inRequest) && count($inRequest) > 0) {
            foreach ($inRequest as $size) {
                $sizeItemData = explode(":", $size);
                $sizeItem = new stdClass();
                $sizeItem->width = $sizeItemData[0];
                $sizeItem->height = $sizeItemData[1];
                $sizeItem->resize = $sizeItemData[2];
                $sizeItem->crop = $sizeItemData[3];
                $sizeItem->xAxis = $sizeItemData[4];
                $sizeItem->yAxis = $sizeItemData[5];
                $sizes[] = $sizeItem;
            }
        }
        return $sizes;
    }

    private function isNew($field) {
        return $field->filename && $field->extension;
    }

    public function uploadMany($uploadsFields, $existingRelations = array()) {
        $errors = array();

        $relativeUploadPath = getRelativeUploadPath();
        
        foreach ($uploadsFields as $field) {

            $uploadConfig = $this->loadUploadConfig($field);
            $this->ci->load->library('upload', $uploadConfig);
            $fieldname = $field->name;
            
            $existing = isset($existingRelations[$field->name]) ? $existingRelations[$field->name] : [];
            $existingCount = count($existing);
            
            $newSizes = $this->buildNewSizes($field->name . '-sizes');
            
            // take any size from any item they are all the same.
            $oldSizes = $existingCount > 0 ? json_decode($existing[0]->sizes) : [];

            // sizes that should be removed
            $obsoleteSizes = array_udiff($oldSizes, $newSizes, array($this, 'equalSizes'));
            
            // sizes that are unchanged
            $unchangedSizes = $this->findUnchangedSizes($oldSizes, $newSizes);
            
            // sizes that are newly added
            $newSizes = array_udiff($newSizes, $oldSizes, array($this, 'equalSizes'));
            
            // remove old sizes
            foreach($obsoleteSizes as $removeSize) {
                foreach($existing as $existingEntry) {
                    unlink($relativeUploadPath.createFileNameForObject($existingEntry, $removeSize));
                }
            }
            
            $newSizes = array_merge($newSizes, $unchangedSizes);
            $newUploadFields = [];
            
            for ($i = 0; $i <= CmsConstants::UPLOADS_FIELD_COUNT; $i++) {

                $dynamicFieldname = $fieldname . '-' . $i;
                if (!isset($_FILES[$dynamicFieldname])) {
                    continue;
                }

                if (!$this->ci->upload->do_upload($dynamicFieldname)) {
                    if (strpos($field->validation, "required") !== FALSE && !$field->value) {
                        $errors[$field->name] = $this->ci->upload->display_errors('<p>', '</p>');
                    }
                } else {
                    $uploadField = new UploadField("position{$i}", "position{$i}");
                    $this->populateUploadFieldFromUploadInfo($uploadField, $this->ci->upload->data());
                    $hashedFilename = $this->generateUniqueFilename($uploadField);
                    rename($relativeUploadPath . $uploadField->filename, $relativeUploadPath . $hashedFilename . $uploadField->extension);
                    $uploadField->filename = $hashedFilename;
                    $newUploadFields[$i] = $uploadField;
                }
                
            }
            
            $newUploadsCount = count($newUploadFields);
            $mergedCount = $existingCount + $newUploadsCount;
            
            for($i = 0 ; $i < $mergedCount ; $i++) {

                // do we have to generate size for `existing` or for the `new` one
                if(isset($existing[$i])) {
                    // `existing` exists, but `new` too so create for the new.
                    if(isset($newUploadFields[$i])) {
                        $this->generateSizes($newUploadFields[$i], $newSizes, $relativeUploadPath);
                        // in the mean time, remove that file with its sizes form the harddisk.
                        unlink($relativeUploadPath.createFileNameForObject($existing[$i]));
                        foreach($oldSizes as $size) {
                           unlink($relativeUploadPath.createFileNameForObject($existing[$i], $size));
                        }
                        
                    // `existing` exists, `new` doesn't so create for existing
                    } else {
                        $this->generateSizes($existing[$i], $newSizes, $relativeUploadPath);    
                    }
                // `existing` doesn't exists, `new` does so create for it
                } else if(isset($newUploadFields[$i])) {
                    $this->generateSizes($newUploadFields[$i], $newSizes, $relativeUploadPath);
                }

            }
            
//             // get rid of original images for all uploads if we do not want to store them.
//            if(!$field->saveOriginal) {
//                foreach($newUploadFields as $uplField) {
//                    unlink($relativeUploadPath.createFileName($uplField->filename,$uplField->extension));
//                }
//            }
            
            
            $field->uploadFields = $newUploadFields;
            $field->sizes = $newSizes;
        }

        return $errors;
    }

    /**
     * Create configuration based on the current field
     */
    private function loadUploadConfig($field) {
        return array(
            "upload_path" => getRelativeUploadPath(),
            "encrypt_name" => FALSE,
            "allowed_types" => implode("|", $field->allowedTypes)
        );
    }

    private function generateUniqueFilename($field) {
        return md5($field->filename . $field->extension . time());
    }

    private function populateUploadFieldFromUploadInfo($field, $info) {
        $field->filename = $info["file_name"];
        $field->filetype = $info["file_type"];
        $field->originalFilename = $info["orig_name"];
        $field->extension = $info["file_ext"];
        $field->filesize = $info["file_size"];
        if ($info["is_image"]) {
            $field->imageHeight = $info["image_height"];
            $field->imageWidth = $info["image_width"];
            $field->isImage = 1;
        } else {
            $field->isImage = 0;
        }
    }

}
