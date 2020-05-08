<section id="breadcrumbs">
    <section class="content_container">
        <ul>
            <?php $homePage = $requestService->getAttribute("homePage"); ?>
            <!-- Always have this breadcrumb home page -->
            <li><a href="<?php echo $navigationService->getStaticUrl($homePage->url); ?>"><?php echo localizedValue($homePage, "name"); ?></a></li>

            <!-- Always have this breadcrumb current page , except if we are on the listings...-->
            <?php if(!$request->getAttribute("currentListingType") || ($requestService->getAttribute("searchItemType") && $requestService->getAttribute("searchItemType") == "Collection")) : ?>
                <li><a href="<?php echo $navigationService->getStaticUrl($page->url); ?>"><?php echo localizedValue($page, "name"); ?></a></li>
            <?php endif; ?>
                
            <!-- Collection breadcrumb -->
            <?php if($requestService->getAttribute("collection")) : ?>
                <?php $collection = $requestService->getAttribute("collection"); ?>

                <li>
                    <a title="<?php echo $collection->name; ?>" href="<?php echo $navigationService->getItemUrl($collection, 'Collection'); ?>">
                            <?php echo $collection->name; ?>
                    </a>
                </li>

            <!-- Category breadcrumb -->
            <?php elseif($requestService->getAttribute("category")) : ?>
                <?php $category = $requestService->getAttribute("category"); ?>

                <li><a title="<?php echo $category->name; ?>" href="<?php echo $navigationService->getItemUrl($category, 'Category'); ?>">
                    <?php echo $category->name; ?>
                </a></li>

            <!-- Collection / Category / Product breadcrumb -->
            <?php elseif($requestService->getAttribute("product")) : ?>
                <?php $product = $requestService->getAttribute("product");
                      $category = CategoryService::instance()->getCategory($product->category);
                      $collection = CollectionService::instance()->getCollection($product->collection); ?>

                <li><a title="<?php echo $category->name; ?>" href="<?php echo $navigationService->getItemUrl($category, 'Category'); ?>">
                    <?php echo $category->name; ?>
                </a></li>

                <li><a title="<?php echo $collection->name; ?>" href="<?php echo $navigationService->getItemUrl($collection, 'Collection'); ?>">
                    <?php echo $collection->name; ?>
                </a></li>


                <li><?php echo $product->name; ?></li>

            <?php endif; ?>

        </ul>

    </section>
</section>

<?php  CmsTemplateRenderer::instance()->renderZone("main"); ?>
