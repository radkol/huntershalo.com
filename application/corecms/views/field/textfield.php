<div class="form-group">
    <label class='col-sm-2 control-label'><?php echo $field->title; ?><?php echo getMandatoryHtml($field); ?></label>
    <div class='col-sm-10'>
        <textarea rows="7" class='form-control' name='<?php echo $field->name; ?>' placeholder='<?php echo $field->title; ?>' <?php echo $readonly; ?>><?php echo handleTextValue($field); ?></textarea>
    </div>
</div>
