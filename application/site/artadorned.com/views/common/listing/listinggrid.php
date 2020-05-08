<div id="listing-grid" class="products_listing clearfix">
    <?php foreach ($pagination->recordSet as $product) : ?>

      <!-- Product Grid Item -->
        <?php $url = $navigationService->getItemUrl($product, "Product"); ?>

      <a href="<?php echo $url; ?>" class="product-item">
            <span class="product-item-image">
            <?php if (isset($productImages[$product->id]) && count($productImages[$product->id]) > 0) : ?>
              <img
                  src="<?php echo $resourceService->getAssetUrl($productImages[$product->id][0], 400, 600); ?>"
                  alt="<?php echo $product->name; ?>"/>
            <?php endif; ?>
            </span>

        <strong class="product-item-title"><?php echo $product->name; ?></strong>
        <span class="product-item-price">
                <?php if (!$product->available && $product->availableToRent): ?>
                  Rent Only
                <?php else: ?>
                    <?php echo withCurrency(withFormat($product->price)); ?>
                <?php endif; ?>
            </span>


      </a>
    <?php endforeach; ?>
</div>