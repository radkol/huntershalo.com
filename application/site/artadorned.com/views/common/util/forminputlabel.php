<?php extract($params); ?>

<?php if($labelvalue) : ?>
    <label for="form_<?php echo $fieldname; ?>">
        <?php echo $labelvalue; ?>
        
        <?php if(isset($required) && $required) : ?>
            <sup>*</sup>
        <?php endif; ?>
            
    </label>
<?php endif; ?>