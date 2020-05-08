<?php
$moduleOfType = $objectData;
$type = $objectData->typeName;
?>
<div class="form-group form-group-uploadfield form-group-uploadable">
    <label class='col-sm-2 control-label'><?php echo $field->title; ?><?php echo getMandatoryHtml($field); ?></label>
    <div class='col-sm-4'>
        <input type="file"  name="<?php echo $field->name; ?>" class="btn btn-default" <?php echo $readonly; ?>>
        <p class="help-block">
            Upload New File
        </p>
    </div>

    <div class='col-sm-6'>
        <?php if($field->isImage) : ?>
            <div class='col-sm-3'>
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#<?php echo $field->name; ?>-modal" data-backdrop="static"> Sizes</button>
            </div>
        <?php endif; ?>
        <?php if ($field->value) : ?>
            <?php $fileObject = $moduleOfType->search()->getRecordFromTable(getFileTablename(), array("id" => $field->value)); ?>
            <?php if($fileObject) : ?>
                <div class='col-sm-4'>
                    <span>
                        <?php if ($fileObject->isImage) : ?>
                            <strong>Original size:<br><?php echo $fileObject->imageWidth . 'x' . $fileObject->imageHeight. ' / '. $fileObject->extension; ?></strong>
                        <?php endif; ?>
                        <a href="<?php echo getDeleteResourceAction($type, $editId, $field->name, $fileObject->id) . '/'; ?>" class="btn btn-danger btn-xs" name="removeModule" ><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;Remove</a>
                    </span>
                </div>
                <div class='col-sm-5'>
                    <?php if ($fileObject->isImage) : ?>
                        <img src="<?php echo getAssetPath($fileObject->filename, $fileObject->extension); ?>" alt="<?php echo $fileObject->filename; ?>" width="135">
                    <?php else : ?>
                        <strong>Uploaded: </br></strong>
                        <?php echo $fileObject->originalFilename; ?>
                    <?php endif; ?>
            <?php else : ?>
                <br/>No uploaded file
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php 
    
    // find read only sizes. These will be defined from the Definition of the field.
    // they can be modified only from the Definition.
        $readOnlySizes = $field->sizes;
        $modifySizes = array();
        
        $existingSizes = isset($fileObject) && ($fileObject->sizes)  ? json_decode($fileObject->sizes) : array();
        
        foreach($existingSizes as $fileSize) {
            $readOnly = false;
            foreach($readOnlySizes as $definitionSize) {
                if($fileSize->width == $definitionSize->width && $fileSize->height == $definitionSize->height) {
                    $readOnly = true;
                    break;
                }
            }
            if(!$readOnly) {
                $modifySizes[] = $fileSize;
            }
        }
        
        $modalData = [];
        $modalData["readOnlySizes"] = $readOnlySizes;
        $modalData["modifySizes"] = $modifySizes;
        $modalData["field"] = $field;
        
        
//        $sizeItem->width = 515;
//        $sizeItem->height = 335;
//        $sizeItem->resize = TRUE;
//        $sizeItem->crop = FALSE;
//        $sizeItem->xAxis = 0;
//        $sizeItem->yAxis = 0;
    ?>
    
    <?php $this->load->view("common/sizesmodaldialog", $modalData); ?>
    
</div>
