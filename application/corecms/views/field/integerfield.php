<div class="form-group">
    <label class='col-sm-2 control-label'><?php echo $field->title; ?><?php echo getMandatoryHtml($field); ?></label>
    <div class='col-sm-10'>
        <input class='form-control' type='text' name='<?php echo $field->name; ?>' placeholder='<?php echo $field->title; ?>' <?php echo $readonly; ?> value='<?php echo handleTextValue($field); ?>' />
    </div>
</div>
