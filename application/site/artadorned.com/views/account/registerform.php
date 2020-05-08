<!-- FORM FIELDS AND FORM ERRORS -->
<?php

$this->load->view("common/util/address/countryvalues");
$selectCountries = $requestService->getAttribute("selectCountries");

$formfields = array(
    ["fieldname" => "email" ,"required" => TRUE, "fieldtype" => "email"],
    ["fieldname" => "password" , "required" => TRUE, "fieldtype" => "password"],
    ["fieldname" => "repeatpassword" ,"required" => TRUE, "fieldtype" => "password"],
    ["fieldname" => "firstName" , "required" => TRUE, "fieldtype" => "text"],
    ["fieldname" => "lastName" , "required" => TRUE, "fieldtype" => "text"],
    ["fieldname" => "phone" , "required" => TRUE, "fieldtype" => "tel"],
    ["fieldname" => "addressLine1" , "required" => TRUE, "fieldtype" => "text"],
    ["fieldname" => "addressLine2" , "required" => FALSE, "fieldtype" => "text"],
    ["fieldname" => "country" , "required" => TRUE, "fieldtype" => "select", "inputValues" => $selectCountries],
    ["fieldname" => "city" , "required" => TRUE, "fieldtype" => "text"],
    ["fieldname" => "postcode" , "required" => TRUE, "fieldtype" => "text"],
    ["fieldname" => "state" , "required" => FALSE, "fieldtype" => "text" ],
);

$formfieldserrors = array(
    ["fieldname" => "email", "labelvalue" => form_error("email")],
    ["fieldname" => "password", "labelvalue" => form_error("password")],
    ["fieldname" => "repeatpassword", "labelvalue" => form_error("repeatpassword")],
    ["fieldname" => "firstName", "labelvalue" => form_error("firstName")],
    ["fieldname" => "lastName", "labelvalue" => form_error("lastName")],
    ["fieldname" => "phone", "labelvalue" => form_error("phone")],
    ["fieldname" => "addressLine1", "labelvalue" => form_error("addressLine1")],
    ["fieldname" => "addressLine2", "labelvalue" => form_error("addressLine2")],
    ["fieldname" => "country", "labelvalue" => form_error("country")],
    ["fieldname" => "city", "labelvalue" => form_error("city")],
    ["fieldname" => "state", "labelvalue" => form_error("state")],
    ["fieldname" => "postcode", "labelvalue" => form_error("postcode")],
);

?>
<!-- END FORM FIELDS AND FORM ERRORS -->

<section id="account-registration" class="content_container">
    <section class="page_section">

        <div class="forms_layout_container clearfix">

            <div class="form_container">
                <h1 class="page_title"><?php echo $module->registerTitle; ?></h1>

                <div id="errors">
                    <?php foreach($formfieldserrors as $data) : ?>
                        <?php $this->load->view("common/util/forminputlabel", ["params" => $data]); ?>
                    <?php endforeach; ?>
                </div>

                <form action="<?php echo $navigationService->getWebPageUrl($page).'/Register'; ?>" method="POST" novalidate="true">
                    <?php foreach($formfields as $data) : ?>
                        <div class="form_field <?php echo $data["fieldname"]; ?>">
                            <?php $this->load->view("common/util/forminput", ["params" => $data]); ?>
                        </div>
                    <?php endforeach; ?>
                    <input type="hidden" name="registerform" value="1" />
                    <input type="submit" class="btn" name="registersubmit" value="<?php echo $resourceService->getLabel("myaccount.button.register"); ?>" />
                </form>
            </div>

            <div class="signup_container">

                <a href="<?php echo $navigationService->getStaticUrl($page->url); ?>" class="signup_btn signup">
                    <strong><?php echo $resourceService->getLabel("myaccount.title.haveaccount"); ?></strong>
                    <span><?php echo $resourceService->getLabel("myaccount.title.signinhere"); ?></span>
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
