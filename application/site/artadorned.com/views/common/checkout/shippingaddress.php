<?php
    $expandable = FALSE;
    if($currentStep > 1) {
        $expandable = TRUE;
    }

    //preselected
    $preselectUseCheckbox = FALSE;
    if($selectedShippingAddress && $selectedBillingAddress && $selectedShippingAddress->uid == $selectedBillingAddress->uid) {
        $preselectUseCheckbox = TRUE;
    }

?>

<!-- STEP 2 - SHIPPING -->
<section id="checkout-shipping" class="toggle-container

<?php if($currentStep == 2) : ?>
    active
<?php elseif ($expandable) : ?>
    closed
<?php else: ?>
    disabled
<?php endif; ?>

">

    <section class="toggle-handle">
        <?php echo $module->shippingTitle; ?>
    </section>



    <!-- SHIPPING TITLE -->


    <?php if($expandable) : ?>
    <section class="toggle-content">
        <!-- EXISTING SHIPPING ADDRESSES SHOW IF EXPANDABLE -->
        <div class="content_column">
            <h5 class="content_title"><?php echo $module->shippingCurrentTitle; ?></h5>

            <?php if (isset($shippingError)) : ?>
                <p><?php echo $resourceService->getLabel("checkout.error.selectshippingaddress"); ?></p>
            <?php endif; ?>

            <form action="<?php echo $navigationService->getWebPageUrl($page); ?>" method="POST" role="form">
                <label for="shippingAsBilling">
                    <input data-address="<?php echo $selectedBillingAddress->uid; ?>" id="shippingAsBilling" type="checkbox" <?php echo $preselectUseCheckbox ? "checked" : "" ; ?> name="shippingAsBilling" value="1" />
                    <?php echo $resourceService->getLabel("checkout.label.sameasbilling"); ?>
                </label>
                <?php foreach ($shippingAddresses as $shippingAddress) : ?>
                    <label class="address-box <?php echo $selectedShippingAddress && $selectedShippingAddress->uid == $shippingAddress->uid ? 'selected' : ''; ?>">
                        <input <?php echo $preselectUseCheckbox ? "disabled" : "" ; ?> data-address="<?php echo $shippingAddress->uid; ?>" type="radio" name="selectedShipping" value="<?php echo $shippingAddress->uid; ?>" <?php echo $selectedShippingAddress && $selectedShippingAddress->uid == $shippingAddress->uid ? 'checked' : ''; ?> />
                        <address>
                            <?php echo $shippingAddress->addressLine1; ?><br>
                            <?php if ($shippingAddress->addressLine2) : ?>
                                <?php echo $shippingAddress->addressLine2; ?><br>
                            <?php endif; ?>
                            <?php echo $shippingAddress->city; ?><br>
                            <?php echo $shippingAddress->postcode; ?><br>
                            <?php echo $shippingAddress->country; ?>
                        </address>

                    </label>
                <?php endforeach; ?>

                <!-- SELECT EXISTING SHIPPING AND STORE IT -->
                <button type="submit" name="continueshippingaddress" value="1" class="btn" ><?php echo $resourceService->getLabel("checkout.button.continue"); ?></button>
            </form>
        </div>

        <!-- NEW SHIPPING ADDRESS -->
        <?php
            $formfieldserrors = array(
                "firstName" => form_error("firstName"),
                "lastName" => form_error("lastName"),
                "phone" => form_error("phone"),
                "addressLine1" => form_error("addressLine1"),
                "addressLine2" => form_error("addressLine2"),
                "country" => form_error("country"),
                "city" => form_error("city"),
                "postcode" => form_error("postcode"),
            );
        ?>

        <div id="checkout-newshippingform" class="content_column"
            <?php if($preselectUseCheckbox) : ?>
                style="display: none;"
            <?php endif ; ?>
        >

            <h5 class="content_title"><?php echo $module->shippingNewTitle; ?></h5>

            <?php if(isset($addressSection) && $addressSection == "shipping") : ?>
                <div id="errors">
                    <?php foreach($formfieldserrors as $fieldname => $errorMessage) : ?>
                        <?php $this->load->view("common/util/forminputlabel", array("fieldname" => $fieldname, "labelvalue" => $errorMessage)); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php $repopulateForm = (isset($addressSection) && $addressSection == "shipping") && !isset($newShippingAdded) ? TRUE : FALSE; ?>

            <?php
                $formfields = array(
                    "firstName" => "text",
                    "lastName" => "text",
                    "phone" => "text",
                    "addressLine1" => "text",
                    "addressLine2" => "text",
                    "country" => "text",
                    "city" => "text",
                    "postcode" => "text",
                );
            ?>

            <form action="<?php echo $navigationService->getWebPageUrl($page); ?>" method="POST">
                <?php foreach($formfields as $fieldname => $fieldtype) : ?>
                    <?php $this->load->view("common/util/forminput", array("fieldname" => $fieldname, "fieldtype" => $fieldtype, "valuecondition" => $repopulateForm)); ?>
                <?php endforeach; ?>
                <button type="submit" name="newshippingaddress" value="1" class="btn"><?php echo $resourceService->getLabel("checkout.button.saveandcontinue"); ?></button>
            </form>
        </div>

    </section>
    <?php endif; ?>

</section>