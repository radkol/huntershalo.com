<?php
    $type = $objectData->typeName; 
    $moduleOfType= $objectData;
    
    $fieldsData = array(
        "type" => $objectData->typeName,
        "fieldVisibleProperty" => "visibleEdit",
        "formId" => "form-adminedit",
        "formUrl" => base_url(getEditSubmitActionForType($type, $editId)),
        "mode" => "edit"
    );
    
?>
<div class="content-box-large">
    <div class="row">
        <div class="col-sm-10"><a class="btn btn-default btn-med" href="<?php echo base_url(getListActionForType($type)); ?>">Back to listing</a></div>
        <div class="col-sm-2 text-right"><a class="btn btn-danger btn-med" href="<?php echo base_url(getDeleteActionForType($type,$editId)); ?>">Delete</a></div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-md-10">
            <div class="panel-heading">
                <div class="panel-title"><h3>Edit <strong><?php echo $moduleOfType->objectAsString($object); ?></strong></h3></div>
            </div>
            <div class="panel-body">
                <?php $this->load->view("common/formfields", $fieldsData); ?>
            </div>
        </div>
    </div>
    <?php if(showModulesPanel($type)) : ?>
        <?php $this->load->view("modules"); ?>
    <?php endif; ?>
</div>