<section id="section-contactform" class="content_container">
    <section class="page_section">

        <?php

            $formfieldserrors = array(
                ["fieldname" => "fullname", "labelvalue" => form_error("fullname")],
                ["fieldname" => "email", "labelvalue" => form_error("email")],
                ["fieldname" => "phone", "labelvalue" => form_error("phone")],
                ["fieldname" => "ordernumber", "labelvalue" => form_error("ordernumber")],
                ["fieldname" => "subject", "labelvalue" => form_error("subject")],
                ["fieldname" => "message", "labelvalue" => form_error("message")],
            ); 
            $formfields = array(
                ["fieldname" => "fullname" , "required" => TRUE, "fieldtype" => "text"],
                ["fieldname" => "email" ,"required" => TRUE, "fieldtype" => "email"],
                ["fieldname" => "phone" ,"required" => TRUE, "fieldtype" => "tel"],
                ["fieldname" => "ordernumber" ,"required" => TRUE, "fieldtype" => "text"],
                ["fieldname" => "subject" ,"required" => TRUE, "fieldtype" => "text"],
                ["fieldname" => "message" ,"required" => TRUE, "fieldtype" => "textarea"],
            );
                    

        ?>

        <section class="forms_layout_container clearfix">
            <div class="form_container">

                <h1 class="page_title"><?php echo $module->title; ?></h1>

                <!-- SUCCESS MESSAGE -->
                <?php if($showSuccess) : ?>
                    <div id="success" class="system_message">
                        <p>
                            <?php echo $module->successMessage; ?>
                        </p>
                    </div>
                <?php endif; ?>
                
                <?php if(!$showSuccess) : ?>
                
                    <!-- ERROR MESSAGES -->
                    <div id="errors">
                        <?php foreach($formfieldserrors as $data) : ?>
                            <?php $this->load->view("common/util/forminputlabel", [ "params" => $data]); ?>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- CONTACT FORM -->
                    <form action="<?php echo $navigationService->getWebPageUrl($page); ?>" method="POST" novalidate="true">
                        <?php foreach($formfields as $data) : ?>
                            <?php $this->load->view("common/util/forminput", [ "params" => $data]); ?>
                        <?php endforeach; ?>
                        <input type="submit" class="btn" name="contactformsubmit" value="<?php echo $resourceService->getLabel("button.send"); ?>" />
                    </form>
                    
                <?php endif; ?>
            </div>

            <!-- RIGHT PANEL -->

            <div class="signup_container">
                <div class="signup_content">
                    <?php echo $module->boxedContent; ?>
                </div>
                <h4><?php echo $module->boxedContentSubtitle; ?></h4>
                <span><i class="icon-check"></i>&nbsp;&nbsp;<?php echo $module->phone; ?></span> <br/>
                <span><i class="icon-mail"></i>&nbsp;&nbsp;<?php echo $module->email; ?></span>
            </div>

        </section>
    </section>

</section>