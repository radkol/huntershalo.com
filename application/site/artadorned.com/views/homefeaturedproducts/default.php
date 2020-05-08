<section id="home-products" class="page_section">
    <section class="content_container">

        <!-- FEATURED PRODUCTS TITLE AND SUBHEADING -->
        <h2 class="section_title">
            <?php echo $module->heading; ?>
            <span><?php echo $module->subHeading; ?></span>
        </h2>


        <!-- FEATURED PRODUCTS ITEMS -->
        <div class="products_listing clearfix">
        <?php foreach($products as $product) : ?>

            <?php $url = $navigationService->getItemUrl($product, "Product"); ?>

            <!-- FEATURED PRODUCTS SINGLE ITEM -->
            <a href="<?php echo $url; ?>" class="product-item">
                <span class="product-item-image">
                <?php if(isset($productImages[$product->id])) : ?>
                    <img src="<?php echo $resourceService->getAssetUrl($productImages[$product->id][0], 400, 600); ?>" />
                <?php endif; ?>
                </span>

                <strong class="product-item-title"><?php echo $product->name; ?></strong>
                <span class="product-item-price"><?php echo withCurrency(withFormat($product->price)); ?></span>
            </a>
        <?php endforeach; ?>
        </div>

    </section>
</section>