<section id="breadcrumbs">
    <section class="content_container">
        <ul>
            <?php $homePage = $requestService->getAttribute("homePage"); ?>
            <!-- Always have this breadcrumb home page -->
            <li><a href="<?php echo $navigationService->getStaticUrl($homePage->url); ?>"><?php echo localizedValue($homePage, "name"); ?></a></li>

            <!-- Always have this breadcrumb current page -->
            <li><a href="<?php echo $navigationService->getStaticUrl($page->url); ?>"><?php echo localizedValue($page, "name"); ?></a></li>
        </ul>
    </section>
</section>
<?php  CmsTemplateRenderer::instance()->renderZone("main"); ?>
