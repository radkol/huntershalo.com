<div class="form-group">
    <label class='col-sm-2 control-label'><?php echo $field->title; ?><?php echo getMandatoryHtml($field); ?></label>
    <div class='col-sm-10'>
        <textarea class='richtext-field' rows="4" name='<?php echo $field->name; ?>' <?php echo $readonly; ?>><?php echo handleTextValue($field); ?></textarea>
    </div>
</div>
