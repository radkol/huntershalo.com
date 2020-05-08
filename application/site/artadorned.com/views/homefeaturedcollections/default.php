<?php if($module->showSection): ?>
<section id="home-collections" class="page_section">
    <section class="content_container">

        <!-- FEATURED COLLECTIONS TITLE AND SUBHEADING -->
        <h2 class="section_title">
            <?php echo $module->heading; ?>
            <span><?php echo $module->subHeading; ?></span>
        </h2>


       <!-- RENDER ALL COLLECTIONS -->
        <section class="collections clearfix">


            <!-- PRODUCT ITEMS CAROUSEL -->
            <section class="collections_carousel">

                <section class="flexslider">
                    <ul class="slides">

                    <!-- Iterate over products in the carousel -->
                    <?php foreach($products as $product) : ?>

                        <?php $url = $navigationService->getItemUrl($product, "Product"); ?>
                        <li>
                            <a href="<?php echo $url; ?>" class="collections_carousel_item">
                                <span class="collections_carousel_item_image"><img src="<?php echo $resourceService->getAssetUrl($productImages[$product->id][0], 400, 600); ?>" /></span>

                                <strong class="collections_carousel_item_title"><?php echo $product->name; ?></strong>
                                <span class="collections_carousel_item_description"><?php echo $product->shortDescription; ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>

                    </ul>
                </section>
            </section>


            <?php foreach($collections as $collection) : ?>
                <?php $url = $navigationService->getItemUrl($collection, "Collection"); ?>
                <section class="collection_item">
                    <a class="collection_item_content" href="<?php echo $url; ?>" style="background-image:url('<?php echo $resourceService->getAssetUrl($collectionImages[$collection->id], 1200, 1200); ?>')">
                        <img src="<?php echo $resourceService->getAssetUrl($collectionImages[$collection->id]); ?>" />
                        <span class="collection_item_details ">
                            <strong class="collection_item_title"><?php echo $collection->name ?></strong>
                            <span class="collection_item_description"><?php echo getStringWithWordLimit($collection->shortDescription, 6); ?></span>
                        </span>
                    </a>
                </section>
            <?php endforeach; ?>
        </section>

    </section>



</section>
<?php endif; ?>