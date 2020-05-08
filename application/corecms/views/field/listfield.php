<div class="form-group">
    <label class='col-sm-2 control-label'><?php echo $field->title; ?><?php echo getMandatoryHtml($field); ?></label>
    <div class='col-sm-10'>
        <select class="selectpicker " style="display: none;" name="<?php echo $field->name; ?>" <?php echo $readonly; ?>>
            <?php foreach($field->values as $key => $value) : ?>
            <?php
                 $actualValue = is_array($value) ? $key : $value;
            ?>
            <option value="<?php echo $key; ?>" <?php echo handleSelectValue($field, $key); ?>>
                        <?php echo $actualValue; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
