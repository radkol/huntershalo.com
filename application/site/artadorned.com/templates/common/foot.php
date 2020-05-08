            </section>
            <footer>

                <section id="instagram_container">
                    <h3><i class="icon-instagram"></i> <?php echo $resourceService->getLabel('footer.instagram.hashtag'); ?></h3>
                    <?php $instagramService  = InstagramService::instance();
                          $images = $instagramService->getPhotos(10);
                    ?>
                    <section>
                        <?php foreach($images as $image) : ?>
                        <a href="<?php echo $image->link; ?>" target="_blank"><img src="<?php echo $image->url; ?>" width="206" height="206" /></a>
                        <?php endforeach; ?>
                    </section>
                </section>

                <section class="content_container">
                    <nav>
                        <section>
                            <h4><?php echo $resourceService->getLabel("footer.text.menu1"); ?></h4>
                            <ul>
                                <?php foreach($navigationService->getMenu('footerOne') as $menu) : ?>
                                    <li><a href="<?php echo $navigationService->getMenuNavItemUrl($menu); ?>"><?php echo getLocalizedValueForField($menu, "name"); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </section>

                        <section>
                            <h4><?php echo $resourceService->getLabel("footer.text.menu2"); ?></h4>
                            <ul>
                                <?php foreach($navigationService->getMenu('footerTwo') as $menu) : ?>
                                    <li><a href="<?php echo $navigationService->getMenuNavItemUrl($menu); ?>"><?php echo getLocalizedValueForField($menu, "name"); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </section>

                        <section>
                            <h4><?php echo $resourceService->getLabel("footer.text.menu3"); ?></h4>
                            <ul>
                                <?php foreach($navigationService->getMenu('footerThree') as $menu) : ?>
                                    <li><a href="<?php echo $navigationService->getMenuNavItemUrl($menu); ?>"><?php echo getLocalizedValueForField($menu, "name"); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </section>

                        <section>
                            <h4><?php echo $resourceService->getLabel("footer.text.menu4"); ?></h4>
                            <ul>
                                <?php foreach($navigationService->getMenu('footerFour') as $menu) : ?>
                                    <li><a href="<?php echo $navigationService->getMenuNavItemUrl($menu); ?>"><?php echo getLocalizedValueForField($menu, "name"); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </section>
                    </nav>

                    <aside>
                        <?php echo $resourceService->getLabel("footer.text.copyright"); ?>
                    </aside>
                </section>

            <?php
                //$this->output->enable_profiler(TRUE);
            ?>
            </footer>
        </section>

        <!-- ADD THIS
        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo $resourceService->getConfig("addthis.clientid"); ?>" > </script>
        -->

        <!-- Placed at the end of the document so the pages load faster -->
        <script type="text/javascript"src="<?php echo siteResourcePath("jquery-1.11.3.min.js","js"); ?>"> </script>
        <script type="text/javascript" src="<?php echo siteResourcePath("jquery.flexslider-min.js","js"); ?>"> </script>
        <script type="text/javascript" src="<?php echo siteResourcePath("fancySelect.js","js"); ?>"> </script>
        <script type="text/javascript" src="<?php echo siteResourcePath("app.js","js"); ?>"> </script>
        <script type="text/javascript" src="<?php echo siteResourcePath("revolutionjewels.js","js"); ?>"> </script>
    </body>
</html>