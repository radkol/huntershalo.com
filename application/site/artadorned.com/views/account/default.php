<section id="myaccount" class="content_container">
    <section class="page_section">

        <h1 class="page_title"><?php echo $module->accountDetailsHeading; ?></h1>
        <section class="account_container clearfix">

            <?php $this->load->view("account/incl/navigation"); ?>

            <!-- CONTENT -->
            <div class="account_content">

                <div class="content_column wide">

                    <!-- ACCOUNT FORM FIELDS -->
                    <?php
                        
                        $formfieldserrors = array(
                            ["fieldname" => "firstName", "labelvalue" => form_error("firstName")],
                            ["fieldname" => "lastName", "labelvalue" => form_error("lastName")],
                            ["fieldname" => "phone", "labelvalue" => form_error("phone")],                            
                            ["fieldname" => "password", "labelvalue" => form_error("password")],
                            ["fieldname" => "repeatpassword", "labelvalue" => form_error("repeatpassword")],
                        );
                    ?>

                    <?php
                        $formfields = array(
                            ["fieldname" => "firstName" , "required" => false, "fieldtype" => "text", "presetValue" => $customer->firstName, "fillValue" => true],
                            ["fieldname" => "lastName" , "required" => false, "fieldtype" => "text", "presetValue" => $customer->lastName, "fillValue" => true],
                            ["fieldname" => "phone" , "required" => false, "fieldtype" => "tel", "presetValue" => $customer->phone, "fillValue" => true],
                            ["fieldname" => "password" , "required" => false, "fieldtype" => "password", "presetValue" => '', "fillValue" => false],
                            ["fieldname" => "repeatpassword" ,"required" => false, "fieldtype" => "password", "presetValue" => '', "fillValue" => false ],
                        );
                    ?>

                    <div id="errors">
                        <?php foreach($formfieldserrors as $data) : ?>
                            <?php $this->load->view("common/util/forminputlabel", [ "params" => $data]); ?>
                        <?php endforeach; ?>
                    </div>

                    <form action="<?php echo $navigationService->getWebPageUrl($page); ?>" method="POST">
                        <?php foreach($formfields as $data) : ?>
                            <div class="form_field <?php echo $data['fieldname']; ?>">
                                <?php $this->load->view("common/util/forminput", ["params" => $data]); ?>
                            </div>
                        <?php endforeach; ?>
                        <input type="hidden" name="updatedetails" value="1" />
                        <input type="submit" class="btn" name="updatedetailssubmit" value="<?php echo $resourceService->getLabel("checkout.button.continue"); ?>" />
                    </form>

                </div>

            </div>

        </section>
    </section>
</section>