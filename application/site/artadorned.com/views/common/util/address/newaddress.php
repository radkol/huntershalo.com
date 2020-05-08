<?php
/**
 *  To use this , we need form validation set in the current module.
 *  Also we need countries set stored in the request
 */
?>

<?php $selectCountries = $requestService->getAttribute("selectCountries"); ?>

<!-- SHOW ALL ERRORS -->
<?php if(!isset($showErrors) || $showErrors) : ?>

    <?php
        
        $formfieldserrors = array(
            ["fieldname" => "firstName", "labelvalue" => form_error("firstName")],
            ["fieldname" => "lastName", "labelvalue" => form_error("lastName")],
            ["fieldname" => "phone", "labelvalue" => form_error("phone")],
            ["fieldname" => "addressLine1", "labelvalue" => form_error("addressLine1")],
            ["fieldname" => "addressLine2", "labelvalue" => form_error("addressLine2")],
            ["fieldname" => "country", "labelvalue" => form_error("country")],
            ["fieldname" => "city", "labelvalue" => form_error("city")],
            ["fieldname" => "postcode", "labelvalue" => form_error("postcode")],
        );
        
    ?>

    <div id="errors">
        <?php foreach ($formfieldserrors as $data) : ?>
            <?php $this->load->view("common/util/forminputlabel", ["params" => $data]); ?>
        <?php endforeach; ?>
    </div>

<?php endif; ?>
<!-- END ALL ERRORS -->


<!-- SHOW FORM -->

<?php
    $formfields = array(
        ["fieldname" => "firstName" , "required" => TRUE, "fieldtype" => "text", "fillValue" => $fillValue],
        ["fieldname" => "lastName" , "required" => TRUE, "fieldtype" => "text", "fillValue" => $fillValue],
        ["fieldname" => "phone" , "required" => TRUE, "fieldtype" => "tel", "fillValue" => $fillValue],
        ["fieldname" => "addressLine1" , "required" => TRUE, "fieldtype" => "text", "fillValue" => $fillValue],
        ["fieldname" => "addressLine2" , "required" => FALSE, "fieldtype" => "text", "fillValue" => $fillValue],
        ["fieldname" => "country" , "required" => TRUE, "fieldtype" => "select", "inputValues" => $selectCountries, "fillValue" => $fillValue],
        ["fieldname" => "city" , "required" => TRUE, "fieldtype" => "text", "fillValue" => $fillValue],
        ["fieldname" => "postcode" , "required" => TRUE, "fieldtype" => "text", "fillValue" => $fillValue],
        ["fieldname" => "state" , "required" => FALSE, "fieldtype" => "text" , "fillValue" => $fillValue],

    );
?>

<?php $formUrl = $navigationService->getWebPageUrl($page); 
      if(isset($formUri)) {
          $formUrl .= $formUri;
      }
?>

<form action="<?php echo $formUrl; ?>" method="POST" novalidate>
    <?php foreach($formfields as $data) : ?>
        <?php $this->load->view("common/util/forminput", ["params" => $data]); ?>
    <?php endforeach; ?>
        
    <input type="hidden" name="addressType" value="<?php echo $addressType; ?>" />
    <button type="submit" name="new<?php echo $addressType ?>address" value="1" class="btn"><?php echo $submitButtonLabel; ?></button>
    
</form>

<!-- END FORM -->