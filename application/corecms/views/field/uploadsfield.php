<?php
    $moduleOfType = $objectData;
    $type = $objectData->typeName;
    
    $existingRelations = array();
    if($mode == "edit") {
        $existingRelations = $fileuploads[$field->name];
    }
?>
<div class="form-group form-group-uploadsfield form-group-uploadable">
    <label class='col-sm-2 control-label'><?php echo $field->title; ?><?php echo getMandatoryHtml($field); ?></label>
    <div class='col-sm-10'>
        <?php if($field->isImage) : ?>
            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#<?php echo $field->name; ?>-modal" data-backdrop="static">
                <i class="glyphicon glyphicon-edit"></i> Sizes
            </button>
        <?php endif; ?>        
        <button type="button" class="uploadsfield-add btn btn-info" data-fieldname="<?php echo $field->name; ?>">
            <i class="glyphicon glyphicon-plus"></i>Add New
        </button>
    </div>
    <div class="col-sm-12 uploadsfield-existing">
        <?php if(!empty($existingRelations)) : ?>
            <?php $count = 0; foreach($existingRelations as $fileObject) : ?>
            <div class="col-sm-4 uploaded-box">
                <input type="file"  name="<?php echo $field->name.'-'.$count; ?>" class="btn btn-default" />
                <p class="help-block">
                    Upload New File
                </p>
                <span>
                    <?php if ($fileObject->isImage) : ?>
                        <strong>Original size:<br><?php echo $fileObject->imageWidth . 'x' . $fileObject->imageHeight. ' / '. $fileObject->extension; ?></strong>
                    <?php endif; ?>
                    <a href="<?php echo getDeleteUploadsResourceAction($type, $editId, $field->name, $fileObject->id) . '/'; ?>" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></a>
                </span>
                <?php if ($fileObject) : ?>
                    <?php if ($fileObject->isImage) : ?>
                        <img src="<?php echo getAssetPath($fileObject->filename, $fileObject->extension); ?>" alt="<?php echo $fileObject->filename; ?>" width="80px" height='80px'>
                    <?php else : ?>
                        <strong>Uploaded: </br></strong>
                        <?php echo $fileObject->originalFilename; ?>
                    <?php endif; ?>
                <?php else: ?>
                    <br/>No uploaded file
                <?php endif; ?> 
            </div>
            <?php $count++; endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div class="col-sm-12">
        <h4>Add New Image</h4>
        <div class="col-sm-12 uploadsfield-add-container"></div>
    </div>
    
    <?php 
        
        // find read only sizes. These will be defined from the Definition of the field.
        // they can be modified only from the Definition.
        $readOnlySizes = $field->sizes;
        $modifySizes = array();
        
        $storedSizes = !empty($existingRelations) ? json_decode($existingRelations[0]->sizes) : array();
        
        foreach($storedSizes as $fileSize) {
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
    
    ?>
    <?php $this->load->view("common/sizesmodaldialog", $modalData); ?>
</div>
