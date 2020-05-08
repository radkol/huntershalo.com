
<!-- STEP 1 - BILLING ALWAYS EXPANDABLE SINCE IT IS FIRST STEP -->
<section id="checkout-billing" class="toggle-container
<?php if($currentStep == 1) : ?>
    active
<?php else: ?>
    closed
<?php endif; ?>

">

    <section class="toggle-handle">
        <?php echo $module->billingTitle; ?>
    </section>

    <section class="toggle-content">

        <!-- EXISTING BILLING ADDRESSES -->
        <div class="content_column">
            
            <!-- EXISTING BILLING ADDRESSES TITLE -->
            <h5 class="content_title"><?php echo $module->billingCurrentTitle; ?></h5>
            
            <!-- CUSTOMER ERROR IF NOTHING IS SELECTED -->
            <?php if (isset($billingError)) : ?>
                <label><?php echo $resourceService->getLabel("checkout.error.selectbillingaddress"); ?></label>
            <?php endif; ?>
                
            <!-- EXISTING BILLING ADDRESSES FORM -->    
            <form action="<?php echo $navigationService->getWebPageUrl($page); ?>" method="POST" role="form">
                
                <?php foreach ($billingAddresses as $billingAddress) : ?>
                
                    <label class="address-box <?php echo $selectedBillingAddress && $selectedBillingAddress->uid == $billingAddress->uid ? 'selected' : ''; ?>">
                        <input type="radio" name="selectedBilling" value="<?php echo $billingAddress->uid; ?>" <?php echo $selectedBillingAddress && $selectedBillingAddress->uid == $billingAddress->uid ? 'checked' : ''; ?> />
                        
                        <!-- DISPLAY ADDRESS CONTENT -->
                        <?php $this->load->view("common/util/address/displayaddress", ["addr" => $billingAddress]); ?>
                        
                    </label>
                
                <?php endforeach; ?>

                <!-- SELECT EXISTING BILLING AND STORE IT -->
                <button type="submit" name="continuebillingaddress" value="1" class="btn"><?php echo $resourceService->getLabel("checkout.button.continue"); ?></button>
            
            </form>
            
        </div>
        <!-- END EXISTING BILLING ADDRESSES -->
        
        <!-- NEW BILLING ADDRESS -->
        <div class="content_column">
            
            <!-- NEW BILLING ADDRESS TITLE -->
            <h5 class="content_title"><?php echo $module->billingNewTitle; ?></h5>

            <?php $repopulateForm = (isset($addressSection) && $addressSection == "billing") && !isset($newBillingAdded) ? TRUE : FALSE; ?>
            
            <?php $this->load->view("common/util/address/newaddress", 
                    array("fillValue" => $repopulateForm, 
                            "addressType" => "billing",
                            "submitButtonLabel" => $resourceService->getLabel("checkout.button.saveandcontinue"),
                            "showErrors" => isset($addressSection) && $addressSection == "billing"
                        )); ?>
        </div>
        <!-- END NEW BILLING ADDRESS -->
        
    </section>

</section>