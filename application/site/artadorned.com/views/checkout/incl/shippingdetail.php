<?php
    $expandable = FALSE;
    if($currentStep > 2) {
        $expandable = TRUE;
    }
?>

<!-- STEP 3 - SHIPPING DETAILS -->
<section id="checkout-shipping-details" class="toggle-container

<?php if($currentStep == 3) : ?>
    active
<?php elseif ($expandable) : ?>
    closed
<?php else: ?>
    disabled
<?php endif; ?>

">

    <section class="toggle-handle">
        <?php echo $module->shippingMethodTitle; ?>
    </section>

    <!-- SHIPPING DETAIL TITLE -->

    <?php if($expandable) : ?>
    <section class="toggle-content">

        <!-- EXISTING SHIPPING ADDRESSES SHOW IF EXPANDABLE -->
        <div class="content_column">
            <h5 class="content_title"><?php echo $module->shippingMethodSubTitle; ?></h5>

            <form action="<?php echo $navigationService->getWebPageUrl($page); ?>" method="POST" role="form">
                
                <?php foreach($shippingDetails as $shippingDetail) : ?>
                    <label class="address-box selected">
                        <input type="radio" name="selectedShippingDetail" value="<?php echo $shippingDetail->id; ?>" <?php echo $selectedShippingDetail->id == $shippingDetail->id ? "checked" : ''; ?> />

                        <address>
                            <?php echo $shippingDetail->name; ?><br>
                            <?php echo $shippingDetail->label; ?><br>
                            <?php echo withCurrency(withFormat($shippingDetail->shippingCost)); ?>
                        </address>

                    </label>
                <?php endforeach; ?>
                
                <!-- SELECT EXISTING SHIPPING AND STORE IT -->
                <button type="submit" name="continueshippingdetail" value="1" class="btn"><?php echo $resourceService->getLabel("checkout.button.continue"); ?></button>
            </form>
        </div>

    </section>
    <?php endif; ?>


</section>