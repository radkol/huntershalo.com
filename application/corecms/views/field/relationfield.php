<div class="form-group">
    <label class='col-sm-2 control-label'><?php echo $field->title; ?><?php echo getMandatoryHtml($field); ?></label>
    <?php
        $fieldType = TypeService::instance()->getType($field->linkTo);
        $totalResults = $fieldType->search()->getResultsCount();
        $autocompleteDisplay = $totalResults > CmsConstants::AUTOCOMPLETE_THRESHOLD ? TRUE : FALSE;
    ?>
        <?php if(!$autocompleteDisplay) : ?>
            <div class='col-sm-6'>
                <?php
                    $totalResults = $fieldType->search()->getResultsCount();
                    $allResultsForType = $fieldType->search()->getRecords();
                ?>
                <select class="selectpicker form-control" style="display: none;" name="<?php echo $field->name; ?>" <?php echo $readonly; ?>>
                    <option value="">Choose <?php echo $fieldType->typeAsString(); ?></option>
                    <?php foreach($allResultsForType as $resultRow) : ?>
                        <option value="<?php echo $resultRow->id; ?>" <?php echo handleSelectValue($field, $resultRow->id); ?>>
                                <?php echo $fieldType->objectAsString($resultRow); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php else : ?>
            <div class='col-sm-6'>
                <?php $selectedValueId = handleTextValue($field);
                    $inputText = '';
                    if($selectedValueId) {
                        $inputText = $fieldType->objectAsString($fieldType->search()->getRecord(array('id' => $selectedValueId)));
                    }
                ?>
                <input class='form-control autocomplete' data-fieldtype="<?php echo $field->linkTo; ?>" data-fieldname="<?php echo $field->name; ?>" type='text' name='<?php echo $field->name; ?>' placeholder='<?php echo $field->title; ?>' <?php echo $readonly; ?> value='<?php echo $inputText; ?>' />
                <input type="hidden" name="<?php echo $field->name; ?>" id="autocomplete-<?php echo $field->name; ?>" value="<?php echo $selectedValueId; ?>" />
                <p class="help-block">Autocomplete field. Start typing...</p>
            </div>
        <?php endif; ?>
</div>
