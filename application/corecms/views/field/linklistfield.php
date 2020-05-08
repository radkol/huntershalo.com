<div class="form-group">
    <label class='col-sm-2 control-label'><?php echo $field->title; ?><?php echo getMandatoryHtml($field); ?>   </label>
    <div class='col-sm-3'>
        <select class="selectpicker linklistfield-select" data-fieldname="<?php echo $field->name; ?>" style="display: none;" name="<?php echo $field->name; ?>-type" id="<?php echo $field->name; ?>-type" <?php echo $readonly; ?>>
            <?php foreach($field->values as $value) : ?>
                <option value="<?php echo $value; ?>" <?php echo handleLinkListSelectValue($field, $value); ?>><?php echo $value; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class='col-sm-7'>
        <?php
            $selectedValueId = handleLinkListTextValue($field);
            $requestService = RequestService::instance();
            $inputText = '';
            if($selectedValueId) {
                $dataType = isEditOrAddFormSubmitted() ? $requestService->getParam($field->name.'-type') : explode(CmsConstants::LINKLIST_FIELD_DELIMITER, $field->value)[0];
                $fieldType = TypeService::instance()->getType($dataType);
                $inputText = $fieldType->objectAsString($fieldType->search()->getRecord(array('id' => $selectedValueId)));
            }
        ?>
        <input class='form-control autocomplete' id='autocomplete-<?php echo $field->name; ?>-placeholder' data-selecttype="1" data-fieldtype="#" data-fieldname="<?php echo $field->name; ?>" type='text' placeholder='<?php echo $field->title; ?>' <?php echo $readonly; ?> value='<?php echo $inputText; ?>' />
        <input type="hidden" name='<?php echo $field->name; ?>-value' id="autocomplete-<?php echo $field->name; ?>" value="<?php echo $selectedValueId; ?>" />
        <p class="help-block">Autocomplete field. Start typing...</p>
    </div>
</div>
