<?php
    $type = $objectData->typeName;
    $definition = $objectData->definition;
    $typeInstance = $objectData;
?>
<div class="content-box-large">
    <div class="row">
        <div class="col-md-12">
            <h1><?php echo $typeInstance->typeAsString(); ?></h1>
        </div>
    </div>
    <hr/>
    <?php if ($typeInstance->allowAddAction()) : ?>
        <div class="row">
            <div class="col-md-12">
                <a class="btn btn-default btn-med" href="<?php echo base_url(getAddActionForType($type)); ?>">Create New <?php echo $typeInstance->typeAsString(); ?></a>
            </div>
        </div>
        <hr/>
    <?php endif; ?>
    <!-- Handle search fields panel -->
    <?php if (count($searchFields)) : ?>
        <div class="row">
            <div class="col-md-12">
                <form method="GET" id="adminsearch-form" class="form-inline" role="form" action="<?php echo base_url(getListActionForType($type)); ?>">
                    <fieldset>
                        <?php foreach ($searchFields as $sField) : ?>
                            <div class="col-sm-3 col-lg-3 col-md-3">
                                <?php if($sField instanceof RelationField) : ?>
                                    <?php
                                        $fieldType = TypeService::instance()->getType($sField->linkTo);
                                        $allResultsForType = $fieldType->search()->getRecords();
                                    ?>
                                    <select class="selectpicker form-control" style="display: none;" name="<?php echo $sField->name; ?>">
                                        <option value="">Choose <?php echo $fieldType->typeAsString(); ?></option>
                                        <?php foreach($allResultsForType as $resultRow) : ?>
                                            <option value="<?php echo $resultRow->id; ?>"><?php echo $fieldType->objectAsString($resultRow); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <input class="form-control" type="text" name="<?php echo $sField->multiLanguage ? getLocalizedFieldName($sField->name)  : $sField->name; ?>" placeholder="<?php echo $sField->title; ?>">
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                        <div class="col-sm-1 col-lg-1 col-md-1">
                            <button class="btn btn-default btn-med" >Do Filter</button>
                        </div>
                        <div class="col-sm-1 col-lg-1 col-md-1">
                            <a class="btn btn-default btn-med" href="<?php echo base_url(getListActionForType($type)); ?>">Show All</a>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <hr>
    <?php endif; ?>
    <!-- Handle grid panel -->
    <div class="row">
        <div class="col-md-12">
            <?php if ($pagination->totalCount) : ?>
                <div class="panel-heading">
                    <div class="panel-title"><h3>Results(<?php echo $pagination->totalCount; ?>)</h3></div>
                </div>
                <div class="panel-body table-responsive">
                    <?php $this->load->view("util/cmspagination"); ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <?php foreach ($listFields as $field): ?>
                                        <th><?php echo ucfirst($field->title); ?></th>
                                    <?php endforeach; ?>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pagination->recordSet as $object): ?>
                                    <tr>
                                        <?php foreach ($listFields as $field): ?>
                                            <td>
                                                <?php if($field instanceof UploadField) : ?>
                                                    <?php echo renderUploadFieldForListing($object, $field, $assetFieldsMap); ?>
                                                <?php else : ?>
                                                    <?php echo renderFieldForListing($object, $field); ?>
                                                <?php endif; ?>
                                            </td>
                                        <?php endforeach; ?>
                                        <td>
                                            <a class="btn btn-info btn-xs" href="<?php echo base_url(getEditActionForType($type, $object->id)); ?>">
                                                <i class="glyphicon glyphicon-pencil"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-danger btn-xs" href="<?php echo base_url(getDeleteActionForType($type, $object->id)); ?>">
                                                <i class="glyphicon glyphicon-remove"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php $this->load->view("util/cmspagination"); ?>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    No results has been found for type <?php echo ucfirst($type); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>