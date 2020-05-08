<div class="form-group">
    <label class='col-sm-2 control-label'><?php echo $field->title; ?><?php echo getMandatoryHtml($field); ?></label>
    <div class=" col-sm-10">
        <div class="checkbox">
            <label>
                <input type='checkbox' name='<?php echo $field->name; ?>' value="1" <?php echo handleCheckboxValue($field,1); ?> <?php echo $readonly; ?> /><?php echo $field->title; ?>
            </label>
        </div>
    </div>
</div>