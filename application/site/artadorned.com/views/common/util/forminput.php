<?php 

extract($params);

/**
 * Params:
 * $fieldname - Form input name => string
 * $fieldtype - Form input type => string (text, select ....)
 * $required - BOOLEAN show * in label
 * $presetValue - Form is used for editing, there might be existing value => string
 * $fillValue - Do we have to try to populate the input at all =>  true / false
 * $inputValues - values for SELECT type
 */

$labelValue = $resourceService->getLabel("form.label.{$fieldname}");
$required = isset($required) ? $required : FALSE;
?>

<!--  RENDER LABEL -->
<?php $this->load->view("common/util/forminputlabel", ["params" => array("required" => $required, "fieldname" => $fieldname, "labelvalue" => $labelValue)] ); ?>
<?php 
    $existingValue = isset($presetValue) ? $presetValue : '';
    $fillValue  = isset($fillValue) ? $fillValue : TRUE ;
    $value = '';
    $errorMsg = form_error($fieldname); 
?>

<!--  RENDER SELECT -->
<?php if($fieldtype == "select") : ?>

    <select <?php echo $errorMsg ? "class='error'" : ""; ?> id="form_<?php echo $fieldname; ?>" name="<?php echo $fieldname; ?>">
        <?php foreach($inputValues as $key => $label) : ?>
            <option <?php echo $fillValue ? set_select($fieldname, $key, $existingValue) : ''; ?> value="<?php echo $label; ?>"><?php echo $label; ?></option>
        <?php endforeach; ?>
    </select>

<!--  RENDER TEXT -->
<?php elseif($fieldtype == "text" || $fieldtype == "password" || $fieldtype == "email" || $fieldtype == "tel") : ?>

    <?php
        if($fillValue) {
            $value = set_value($fieldname, $existingValue);
        }
    ?>
    
    <input <?php echo $errorMsg ? "class='error'" : ""; ?> type="<?php echo $fieldtype; ?>" id="form_<?php echo $fieldname; ?>" name="<?php echo $fieldname; ?>" value="<?php echo $value; ?>" />
<?php elseif($fieldtype == "textarea") : ?>
    <?php
        if($fillValue) {
            $value = set_value($fieldname, $existingValue);
        }
    ?>
    <textarea <?php echo $errorMsg ? "class='error'" : ""; ?> id="form_<?php echo $fieldname; ?>" name="<?php echo $fieldname; ?>"><?php echo $value; ?></textarea>
<?php endif; ?>