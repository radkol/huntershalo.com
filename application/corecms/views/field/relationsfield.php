<div class="form-group">
    <label class='col-sm-2 control-label'><?php echo $field->title; ?><?php echo getMandatoryHtml($field); ?></label>
    <div class='col-sm-5'>
        <?php
            // load data for the select
            $fieldType = TypeService::instance()->getType($field->linkTo);
            $totalResults = $fieldType->search()->getResultsCount();
            $autocompleteDisplay = $totalResults > CmsConstants::AUTOCOMPLETE_THRESHOLD ? TRUE : FALSE;
            
            $existingRelations = [];
            // do we have form submitted? if we do, get these records.
            if(isEditOrAddFormSubmitted()) {
                $ids = $field->value;
                $existingRelations = $fieldType->search()->getWhereInRecords("id", $ids);
            } else if($mode == "edit") {
                $existingRelations = $relations[$field->name];
            }
            
            $allResultsForType = [];
            if(!$autocompleteDisplay) {
                $allResultsForType = $fieldType->search()->getRecords();
            }
        ?>
        
        <!-- RENDER SOURCE SELECT / AUTOCOMPLETE FIELD-->
        <?php if(!$autocompleteDisplay) : ?>
            <select id="<?php echo $field->name . '-source'; ?>" class="selectpicker form-control" style="display: none;" name="<?php echo $field->name . '-source'; ?>">
                <?php foreach ($allResultsForType as $resultRow) : ?>
                    <option value="<?php echo $resultRow->id; ?>"><?php echo $fieldType->objectAsString($resultRow); ?></option>
                <?php endforeach; ?>
            </select>
        <?php else: ?>
            <input class='form-control autocomplete' data-fieldtype="<?php echo $field->linkTo; ?>" data-fieldname="multiple-<?php echo $field->name; ?>" id="multiple-<?php echo $field->name; ?>" type='text' placeholder='<?php echo $field->title; ?>' <?php echo $readonly; ?> />
            <input type="hidden" id="autocomplete-multiple-<?php echo $field->name; ?>" value="" />
            <p class="help-block">Autocomplete field. Start typing...</p>
        <?php endif; ?>
        
        <!-- RENDER EXISTING RELATIONS -->
        <select id="<?php echo $field->name.'-storage'; ?>" multiple class="hidden" name="<?php echo $field->name; ?>[]">
            <?php foreach($existingRelations as $relation) : ?>
                <option selected><?php echo $relation->id; ?></option>
            <?php endforeach; ?>
        </select>
        
    </div>
    <div class='col-sm-2'>
         <button type="button" class="relationsfield-add btn btn-info" data-autocomplete="<?php echo $autocompleteDisplay ? 1 : 0; ?>" data-selectname="<?php echo $field->name;?>">Add Element</button>
    </div>
</div>

<div class="form-group">
    <label class='col-sm-2 control-label'>Selected <?php echo $field->title; ?><?php echo getMandatoryHtml($field); ?></label>
    <div class='col-sm-5'>
        <table class="table table-striped" id="<?php echo $field->name.'-display';?>" linkto="#<?php echo $field->name.'-storage'; ?>">
            <thead>
                <th>Element</th>
                <th>Remove</th> 
            </thead>
            <tbody>
                <?php $count = 0; foreach($existingRelations as $relation) : $count++; ?>
                    <tr>
                        <td><?php echo $fieldType->objectAsString($relation); ?></td>
                        <td>
                            <a href="" class="relationsfield-record-remove btn btn-danger btn-xs" element="<?php echo $count; ?>">
                                <i class="glyphicon glyphicon-remove"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
