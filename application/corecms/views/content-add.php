<?php
    $type = $objectData->typeName;
    $moduleOfType= $objectData;
    
    $fieldsData = array(
        "type" => $objectData->typeName,
        "fieldVisibleProperty" => "visibleAdd",
        "formId" => "form-adminadd",
        "formUrl" => base_url(getAddSubmitActionForType($type)),
        "mode" => "add"
    );
?>
<div class="content-box-large">
    <a class="btn btn-default btn-med" href="<?php echo base_url(getListActionForType($type)); ?>">Back to listing</a>
    <hr/>
    <div class="row">
        <div class="col-md-10">
            <div class="panel-heading">
                <div class="panel-title"><h3>Add new <strong><?php echo $moduleOfType->typeAsString(); ?></strong></h3></div>
            </div>
            <div class="panel-body">
                 <?php $this->load->view("common/formfields", $fieldsData); ?>
            </div>
        </div>
    </div>
</div>