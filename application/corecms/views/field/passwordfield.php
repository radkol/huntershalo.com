<div class="form-group">
    <label class='col-sm-2 control-label'><?php echo $field->title; ?><?php echo getMandatoryHtml($field); ?></label>
    <div class='col-sm-10'>
        <input class='form-control' type='password' name='<?php echo $field->name; ?>' <?php echo $readonly; ?>  />
    </div>
</div>