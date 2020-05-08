<!-- STEP 1 - BILLING AWLAYS EXPANDABLE SINCE IT IS FIRST STEP -->
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
            <h5 class="content_title"><?php echo $module->billingCurrentTitle; ?></h5>

            <?php if (isset($billingError)) : ?>
                <p><?php echo $resourceService->getLabel("checkout.error.selectbillingaddress"); ?></p>
            <?php endif; ?>

            <form action="<?php echo $navigationService->getWebPageUrl($page); ?>" method="POST" role="form">
                <?php foreach ($billingAddresses as $billingAddress) : ?>
                    <label class="address-box <?php echo $selectedBillingAddress && $selectedBillingAddress->uid == $billingAddress->uid ? 'selected' : ''; ?>">
                        <input type="radio" name="selectedBilling" value="<?php echo $billingAddress->uid; ?>" <?php echo $selectedBillingAddress && $selectedBillingAddress->uid == $billingAddress->uid ? 'checked' : ''; ?> />
                        <address>
                            <?php echo $billingAddress->addressLine1; ?><br>
                            <?php if ($billingAddress->addressLine2) : ?>
                                <?php echo $billingAddress->addressLine2; ?><br>
                            <?php endif; ?>
                            <?php echo $billingAddress->city; ?><br>
                            <?php echo $billingAddress->postcode; ?><br>
                            <?php echo $billingAddress->country; ?>
                        </address>
                    </label>
                <?php endforeach; ?>

                <!-- SELECT EXISTING BILLING AND STORE IT -->
                <button type="submit" name="continuebillingaddress" value="1" class="btn"><?php echo $resourceService->getLabel("checkout.button.continue"); ?></button>
            </form>
        </div>

        <!-- NEW BILLING ADDRESS -->

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


        <div class="content_column">

            <h5 class="content_title"><?php echo $module->billingNewTitle; ?></h5>

            <?php if(isset($addressSection) && $addressSection == "billing") : ?>
                <div id="errors">
                    <?php foreach($formfieldserrors as $fieldname => $errorMessage) : ?>
                        <?php $this->load->view("common/util/forminputlabel", array("fieldname" => $fieldname, "labelvalue" => $errorMessage)); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php $repopulateForm = (isset($addressSection) && $addressSection == "billing") && !isset($newBillingAdded) ? TRUE : FALSE; ?>


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
                <button type="submit" name="newbillingaddress" value="1" class="btn"><?php echo $resourceService->getLabel("checkout.button.saveandcontinue"); ?></button>
            </form>
        </div>


    </section>








</section>