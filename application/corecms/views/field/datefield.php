<div class="form-group">
    <label class='col-sm-2 control-label'><?php echo $field->title; ?><?php echo getMandatoryHtml($field); ?></label>
    <div class='col-sm-10 datefield'>
        <input class='form-control' type='text' name='<?php echo $field->name; ?>' placeholder='<?php echo $field->title; ?>' <?php echo $readonly; ?> value='<?php echo handleTextValue($field); ?>' /><!--        <div class="bfh-datepicker" data-placeholder="<?php echo $field->value; ?>" data-format="d/m/y" data-date="today" data-name="<?php echo $field->name; ?>">
        </div>-->
    </div>
</div>




