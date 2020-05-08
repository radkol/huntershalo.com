<section id="cart">
    <?php $cartService = CartService::instance(); ?>
    <?php $cartPage = $requestService->getAttribute("cartPage"); ?>

    <a href="<?php echo $navigationService->getStaticUrl($cartPage->url); ?>" id="mini_basket_menu">
        <strong class="items_total_price">
            <i class="icon-cart"></i>
            <span><?php echo withCurrency(withFormat($cartService->getTotal())); ?></span>
        </strong>
        <span class="items_count">
            <?php echo $cartService->getItemsCount(); ?> <?php echo $resourceService->getLabel("header.cart.items"); ?>
        </span>
    </a>

    <?php if ($cartService->getItemsCount() > 0) : ?>
        <section id="cart_container">

            <?php $purchaseProducts = $cartService->getPurchaseProducts();
               $rentalProducts = $cartService->getRentalProducts();
               ?>
            <?php if (count($purchaseProducts) > 0) : ?>
              <h5>Items to Buy</h5>
              <ul>
                  <?php foreach ($purchaseProducts as $cartItem) : ?>
                      <li>
                          <a href="<?php echo $navigationService->getItemUrl($cartItem, "Product") ;?>" title="<?php echo $cartItem["name"]; ?>">
                              <?php $thumbImg = $cartItem["options"]["thumb"]; ?>

                              <span class="image_container">
                                  <?php if($thumbImg) : ?>
                                      <img src="<?php echo $thumbImg; ?>" alt="<?php echo $cartItem["name"];?> " width="50" height="50" />
                                  <?php endif; ?>
                              </span>

                              <span class="item_title"> <?php echo $cartItem["name"]; ?></span>
                              <span class="item_price"> <?php echo withCurrency(withFormat($cartItem["price"])); ?></span>
                          </a>
                      </li>
                  <?php endforeach; ?>
              </ul>
            <?php endif; ?>

            <?php if (count($rentalProducts) > 0) : ?>
              <h5>Items to Rent</h5>
              <ul>
                  <?php foreach ($rentalProducts as $cartItem) : ?>
                    <li>
                      <a href="<?php echo $navigationService->getItemUrl($cartItem, "Product") ;?>" title="<?php echo $cartItem["name"]; ?>">
                          <?php $thumbImg = $cartItem["options"]["thumb"]; ?>

                        <span class="image_container">
                                  <?php if($thumbImg) : ?>
                                    <img src="<?php echo $thumbImg; ?>" alt="<?php echo $cartItem["name"];?> " width="50" height="50" />
                                  <?php endif; ?>
                        </span>
                      </a>
                    </li>
                  <?php endforeach; ?>
              </ul>
            <?php endif; ?>

            <a title="<?php echo localizedValue($cartPage, "name"); ?>" href="<?php echo $navigationService->getStaticUrl($cartPage->url); ?>" class="show_cart">
                <?php echo $resourceService->getLabel("header.cart.viewallitems"); ?>
            </a>
        </section>
    <?php endif; ?>
</section>
