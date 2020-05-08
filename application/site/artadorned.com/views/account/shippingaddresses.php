<section id="myshippingaddresses" class="content_container">
    <section class="page_section">

        <h1 class="page_title"><?php echo $module->shippingAddressHeading; ?></h1>
        <section class="account_container clearfix">

            <?php $this->load->view("account/incl/navigation"); ?>

            <div class="account_content">

                <!-- EXISTING BILLING ADDRESSES -->
                <div class="content_column">
                    <h5 class="content_title"><?php echo $module->existingAddressTitle; ?></h5>

                    <form action="<?php echo $navigationService->getWebPageUrl($page).$uriString; ?>" method="POST" role="form">
                        <?php foreach ($shippingAddresses as $shippingAddress) : ?>
                            <label class="address-box <?php echo $defaultAddress && $defaultAddress->id == $shippingAddress->id ? 'selected' : ''; ?>">
                                <input type="radio" name="selectedShipping" value="<?php echo $shippingAddress->id; ?>" <?php echo $defaultAddress && $defaultAddress->id == $shippingAddress->id ? 'checked' : ''; ?> />

                                <?php $this->load->view("common/util/address/displayaddress", ["addr" => $shippingAddress]); ?>

                                <!-- Show primary label -->
                                <?php if ($defaultAddress && $defaultAddress->uid == $shippingAddress->uid) : ?>
                                    <span class="primary_address"><?php echo $resourceService->getLabel("account.address.primary"); ?></span>
                                <?php endif; ?>

                            </label>
                        <?php endforeach; ?>

                        <!-- SELECT EXISTING BILLING AND STORE IT -->
                        <input type="hidden" name="addressType" value="shipping" />
                        <button type="submit" name="primaryshippingaddress" value="1" class="btn"><?php echo $resourceService->getLabel("account.button.makeprimary"); ?></button>
                        <button type="submit" name="removeshippingaddress" value="1" class="btn"><?php echo $resourceService->getLabel("basket.button.remove"); ?></button>

                    </form>
                </div>

                <div class="content_column">
                    <!-- ADD NEW BILLING ADDRESS -->
                    <h5 class="content_title"><?php echo $module->shippingNewTitle; ?></h5>

                    <?php $this->load->view("common/util/address/countryvalues"); ?>
                    <?php $this->load->view("common/util/address/newaddress",
                            array("fillValue" => $requestService->getAttribute("fillValue"),
                                    "addressType" => "shipping",
                                    "formUri" => $uriString,
                                    "submitButtonLabel" => $resourceService->getLabel("checkout.button.continue")
                                ));
                    ?>
                </div>

            </div>
        </section>
    </section>
</section>