<section class="content_container">
    <section class="page_section">
        <h1 class="page_title"><?php echo $module->setNewPasswordHeading; ?></h1>

        <div class="forgotten_password_form">
            <!-- NEW PASSWORD SUCCESS OR SHOW SET PASSWORD FORM -->
            <?php if($requestService->getAttribute("setPassword")) : ?>

                <!-- NEW PASSWORD SUCCESS -->
                <p><?php echo $module->setNewPasswordSuccess; ?></p>

            <?php else: ?>

                <!-- NEW PASSWORD FORM -->
                <p>
                    <?php echo $module->setNewPasswordDescription; ?>
                </p>

                <?php
                    $formfieldserrors = array(
                        ["fieldname" => "password", "labelvalue" => form_error("password")],
                        ["fieldname" => "repeatpassword", "labelvalue" => form_error("repeatpassword")],
                    ); 
                    $formfields = array(
                        ["fieldname" => "password" , "required" => TRUE, "fieldtype" => "password"],
                        ["fieldname" => "repeatpassword" ,"required" => TRUE, "fieldtype" => "password"],
                    );
                ?>

                <div id="errors">
                    <?php foreach ($formfieldserrors as $data) : ?>
                        <?php $this->load->view("common/util/forminputlabel", ["params" => $data]); ?>
                    <?php endforeach; ?>
                </div>

                <form action="<?php echo $navigationService->getWebPageUrl($page) . $uriPart ;?>" method="POST">
                    <?php foreach ($formfields as $data) : ?>
                        <?php $this->load->view("common/util/forminput", ["params" => $data]); ?>
                    <?php endforeach; ?>
                    <button class="btn" type="submit" name="setnewpassword" value="1"><?php echo $resourceService->getLabel("checkout.button.continue"); ?></button>
                </form>
            <?php endif; ?>
        </div>
    </section>
</section>