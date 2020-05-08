<section class="page_section">
    <section id="newsletter">
        <section class="content_container">

            <div class="newsletter_container clearfix">
                <form action="<?php echo $module->formUrl; ?>" method="POST" name="subscriptionform" target="_blank" novalidate>
                    <label for="newsletter_email"><?php echo $module->title; ?></label>
                    <div class="form_elements">
                        <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="<?php echo $module->hiddenInputCode; ?>" tabindex="-1" value=""></div>
                        <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="<?php echo $resourceService->getLabel("newsletter.input.placeholder"); ?>" required>
                        <button type="submit" name="subscribe" id="mc-embedded-subscribe"><?php echo $resourceService->getLabel("checkout.button.continue"); ?></button>
                    </div>
                </form>

                <?php $testLen = 10; ?>
                <div class="social_buttons">
                    <?php if(strlen($resourceService->getConfig("social.follow.facebook")) > $testLen) : ?>
                        <a target="_blank" href="<?php echo $resourceService->getConfig("social.follow.facebook"); ?>"><i class="icon-facebook"></i> <span>Facebook</span></a>
                    <?php endif; ?>
                    <a target="_blank" href="<?php echo $resourceService->getConfig("social.follow.twitter"); ?>"><i class="icon-twitter"></i> <span>Twitter</span></a>
                    <?php if(strlen($resourceService->getConfig("social.follow.googleplus")) > $testLen) : ?>
                        <a target="_blank" href="<?php echo $resourceService->getConfig("social.follow.googleplus"); ?>"><i class="icon-google-plus"></i> <span>Google +</span></a>
                    <?php endif; ?>
                    <a target="_blank" href="<?php echo $resourceService->getConfig("social.follow.pinterest"); ?>"><i class="icon-pinterest"></i> <span>Pin it</span></a>
                    <a target="_blank" href="<?php echo $resourceService->getConfig("social.follow.instagram"); ?>"><i class="icon-instagram"></i> <span>Blog</span></a>
                </div>
            </div>

        </section>
    </section>
    
</section>
