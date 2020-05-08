<?php
    $expandable = FALSE;
    if($currentStep > 3) {
        $expandable = TRUE;
    }
?>

<!-- STEP 3 - SHIPPING DETAILS -->
<section id="checkout-payment-details" class="toggle-container

<?php if($currentStep == 4) : ?>
    active
<?php elseif ($expandable) : ?>
    closed
<?php else: ?>
    disabled
<?php endif; ?>

">


    <section class="toggle-handle">
        <?php echo $module->paymentTitle; ?>
    </section>
    <!-- SHIPPING DETAIL TITLE -->


    <?php if($expandable) : ?>
        <!-- EXISTING SHIPPING ADDRESSES SHOW IF EXPANDABLE -->
        <section class="toggle-content">
            <div class="content_column">
                <h5 class="content_title"><?php echo $module->paymentSubTitle; ?></h5>
                <a href="<?php $navigationService->getWebPageUrl($page); ?>?paypalpayment=true&key=<?php echo uniqid("paypal"); ?>" class="btn paypal">PAY NOW</a>
            </div>
        </section>
    <?php endif; ?>

</section>