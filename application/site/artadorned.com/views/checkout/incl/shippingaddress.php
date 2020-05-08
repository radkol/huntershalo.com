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
    
    $selectCountries = $requestService->getAttribute("selectCountries");
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

                    <!-- SHIPPING AS BILLING CHECKBOX -->
                    <label for="shippingAsBilling">
                        <input data-address="<?php echo $selectedBillingAddress ? $selectedBillingAddress->uid : ''; ?>" id="shippingAsBilling" type="checkbox" <?php echo $preselectUseCheckbox ? "checked" : "" ; ?> name="shippingAsBilling" value="1" />
                        <?php echo $resourceService->getLabel("checkout.label.sameasbilling"); ?>
                    </label>

                    <!-- ALL SHIPPING ADDRESSES -->
                    <?php foreach ($shippingAddresses as $shippingAddress) : ?>

                        <label class="address-box <?php echo $selectedShippingAddress && $selectedShippingAddress->uid == $shippingAddress->uid ? 'selected' : ''; ?>">

                            <input <?php echo $preselectUseCheckbox ? "disabled" : "" ; ?> data-address="<?php echo $shippingAddress->uid; ?>" type="radio" name="selectedShipping" value="<?php echo $shippingAddress->uid; ?>" <?php echo $selectedShippingAddress && $selectedShippingAddress->uid == $shippingAddress->uid ? 'checked' : ''; ?> />

                            <!-- DISPLAY ADDRESS CONTENT -->
                            <?php $this->load->view("common/util/address/displayaddress", ["addr" => $shippingAddress]); ?>

                        </label>

                    <?php endforeach; ?>

                    <!-- SUBMIT SELECT SHIPPING ADDRESS -->
                    <button type="submit" name="continueshippingaddress" value="1" class="btn" ><?php echo $resourceService->getLabel("checkout.button.continue"); ?></button>

                </form>
            </div>


            <!-- NEW SHIPPING ADDRESS -->

            <div id="checkout-newshippingform" class="content_column"
                <?php if($preselectUseCheckbox) : ?>
                    style="display: none;"
                <?php endif ; ?>
            >

                <h5 class="content_title"><?php echo $module->shippingNewTitle; ?></h5>

                <?php $repopulateForm = (isset($addressSection) && $addressSection == "shipping") && !isset($newShippingAdded) ? TRUE : FALSE; ?>
                
                <?php $this->load->view("common/util/address/newaddress", 
                        array("fillValue" => $repopulateForm, 
                                "addressType" => "shipping",
                                "submitButtonLabel" => $resourceService->getLabel("checkout.button.saveandcontinue"),
                                "showErrors" => isset($addressSection) && $addressSection == "shipping"
                            )); ?>
            </div>

            <!-- END NEW SHIPPING ADDRESS -->

        </section>
    
    <?php endif; ?>

</section>