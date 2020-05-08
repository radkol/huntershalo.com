<?php

$formfields = array(
    ["fieldname" => "login" ,"required" => TRUE, "fieldtype" => "text"],
    ["fieldname" => "password" ,"required" => TRUE, "fieldtype" => "password"],
);

$formfieldserrors = array(
    ["fieldname" => "login", "labelvalue" => form_error("login")],
    ["fieldname" => "password", "labelvalue" => form_error("password")],
);
?>

<section id="account-login" class="content_container">
    <section class="page_section">

        <div class="forms_layout_container clearfix">

            <div class="form_container">
                <h1 class="page_title"><?php echo $module->loginTitle; ?></h1>

                <div id="errors">
                    <?php foreach($formfieldserrors as $data) : ?>
                        <?php $this->load->view("common/util/forminputlabel", [ "params" => $data ]); ?>
                    <?php endforeach; ?>
                </div>
                <form action="<?php echo $navigationService->getWebPageUrl($page); ?>" method="POST">
                    <?php foreach($formfields as $data) : ?>
                        <?php $this->load->view("common/util/forminput", [ "params" => $data ]); ?>
                    <?php endforeach; ?>
                    <input type="hidden" name="loginform" value="1" />
                    <input type="submit" class="btn" name="loginsubmit" value="<?php echo $resourceService->getLabel("myaccount.button.login"); ?>" />


                    <a href="<?php echo $navigationService->getWebPageUrl($page) . $forgotPasswordUri; ?>" class="forgotten_password_link">
                        <?php echo $resourceService->getLabel("account.link.forgotpassword"); ?>
                    </a>
                </form>
            </div>

            <div class="signup_container">

                <a href="<?php echo $navigationService->getStaticUrl($page->url . '/Register'); ?>" class="signup_btn signup">
                    <strong><?php echo $resourceService->getLabel("myaccount.title.donthaveaccount"); ?></strong>
                    <span><?php echo $resourceService->getLabel("myaccount.title.signuphere"); ?></span>
                    <i class="icon-chevron-thin-right arrow"></i>
                </a>

                <!--
                <a href="#" class="signup_btn facebook">
                    <i class="icon-facebook icon"></i>
                    <strong>Connect with<br /> Facebook</strong>
                    <i class="icon-chevron-thin-right arrow"></i>
                </a>
                -->

            </div>

        </div>


    </section>
</section>