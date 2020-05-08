<section class="page_section">
    <section id="home-newsletter">

        <section class="content_container">

        <?php echo $module->title; ?>

        <form>
            <input type="text" name="email" />
            <button type="submit" name="newsletter-submit" >Submit</button>
        </form>

        <a href="<?php echo $resourceService->getConfig("facebook.link"); ?>"><i class="icon-facebook"></i> <span>Facebook</span></a> |
        <a href="<?php echo $resourceService->getConfig("twitter.link"); ?>"><i class="icon-twitter"></i> <span>Twitter</span></a> |
        <a href="<?php echo $resourceService->getConfig("googleplus.link"); ?>"><i class="icon-google_plus"></i> <span>Google +</span></a> |
        <a href="<?php echo $resourceService->getConfig("pinit.link"); ?>"><i class="icon-pinterest"></i> <span>Pin it</span></a> |
        <a href="<?php echo $resourceService->getConfig("blog.link"); ?>"><i class="icon-instagram"></i> <span>Blog</span></a>
            </section>

    </section>
</section>