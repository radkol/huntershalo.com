

<?php 
    $countUploadFields = count(FieldService::instance()->getAllUploadFields($fields));
    $countUploadsFields = count(FieldService::instance()->getAllUploadsFields($fields));
    $countRelationFields = count(FieldService::instance()->getAllRelationFields($fields));
    $countRelationsFields = count(FieldService::instance()->getAllRelationsFields($fields));
?>

<!-- VALIDATION ERRORS -->
<?php $errorsString = validation_errors(); ?>
<?php if ($errorsString != '' || !empty($uploadErrors)) : ?>
    <div class="alert alert-danger">
        <?php echo $errorsString; ?>
        <?php foreach ($uploadErrors as $uploadError) : ?>
            <p class="adminerror"><?php echo $uploadError; ?></p>
        <?php endforeach; ?> 
    </div>
<?php endif; ?>

<!-- FORM ENC TYPE -->
<?php // check if there is a upload field to add form correct type ?>
<?php if ( $countUploadFields > 0 || $countUploadsFields > 0) : $formMultipart = " enctype='multipart/form-data' " ?>
<?php else : $formMultipart = ""; ?>
<?php endif; ?>

<!-- FORM -->
<form method="POST" id="<?php echo $formId; ?>" class='form-horizontal' role="form" action="<?php echo $formUrl; ?>" <?php echo $formMultipart; ?> >
    
    <!-- TABS -->
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab1" data-toggle="tab">General</a>
        <?php if($countRelationFields > 0 || $countRelationsFields > 0) : ?>
            <li><a href="#tab2" data-toggle="tab">Relations</a>
        <?php endif; ?>
        <?php if($countUploadsFields > 0 || $countUploadFields > 0) : ?>
            <li><a href="#tab3" data-toggle="tab">Media</a>
        <?php endif; ?>
    </ul>
    
    <!-- TABS CONTENT FIELDS -->
    <div class="tab-content">
        <div class="tab-pane active" id="tab1">
            <?php foreach ($fields as $field): ?>
                <?php if ($field->$fieldVisibleProperty && !$field instanceof UploadsField && !$field instanceof UploadField && !$field instanceof RelationField && !$field instanceof RelationsField): ?>
                    <?php
                        $this->load->view("field/" . strtolower(get_class($field)), array("field" => $field, "readonly" => $field->readOnly && $mode != 'add' ? 'readonly' : ''));
                    ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php if($countRelationFields > 0 || $countRelationsFields > 0) : ?>
            <div class="tab-pane" id="tab2">
                <?php foreach ($fields as $field): ?>
                    <?php if ($field->$fieldVisibleProperty && ($field instanceof RelationField || $field instanceof RelationsField)): ?>
                        <?php
                            $this->load->view("field/" . strtolower(get_class($field)), array("field" => $field, "readonly" => ''));
                        ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if($countUploadsFields > 0 || $countUploadFields > 0) : ?>
            <div class="tab-pane" id="tab3">
                <?php foreach ($fields as $field): ?>
                    <?php if ($field->$fieldVisibleProperty && ($field instanceof UploadsField || $field instanceof UploadField)): ?>
                        <?php
                            $this->load->view("field/" . strtolower(get_class($field)), array("field" => $field, "readonly" => ''));
                        ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- FORM ACTION -->
    <div>
        <button class="btn btn-primary" name="submit" type="submit">Submit</button>
    </div>
</form>
