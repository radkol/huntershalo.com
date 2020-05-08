<section class="content_container">
    <section class="page_section">
        <h1 class="page_title"><?php echo $module->forgottenPasswordHeading; ?></h1>

        <div class="forgotten_password_form">
            <?php if($requestService->getAttribute("sendPassword")) : ?>
                <p><?php echo $module->forgottenPasswordSuccess; ?></p>
            <?php else: ?>
                <p><?php echo $module->forgottenPasswordDescription; ?></p>
                <?php
                    $formfieldserrors = array(["fieldname" => "email", "labelvalue" => form_error("email")]);                    
                ?>

                <div id="errors">
                    <?php foreach ($formfieldserrors as $data) : ?>
                        <?php $this->load->view("common/util/forminputlabel", ["params" => $data]); ?>
                    <?php endforeach; ?>
                </div>
                
                <form action="<?php echo $navigationService->getWebPageUrl($page) . $uriPart ;?>" method="POST" novalidate="true">
                    <?php $this->load->view("common/util/forminput", ["params" => array("fieldname" => "email", "fieldtype" => "email")]); ?>
                    <button class="btn" type="submit" name="forgottenpassword" value="1"><?php echo $resourceService->getLabel("checkout.button.continue"); ?></button>
                </form>

            <?php endif; ?>
        </div>
    </section>
</section>