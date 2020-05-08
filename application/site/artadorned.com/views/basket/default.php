<section class="content_container">
    <section class="page_section">

        <?php $cartService = CartService::instance();
              $collectionService = CollectionService::instance();
              $maxItemCount = $requestService->getAttribute("maxItemCount");
              $shippingDetail = $sessionService->getAttribute("shippingDetail");
              $checkoutPage = $requestService->getAttribute("checkoutPage");
        ?>

        <?php if($cartService->getItemsCount() < 1) : ?>
            <h1 class="page_title"><?php echo $module->title; ?></h1>
            <section class="system_message"><?php echo $resourceService->getLabel("basket.noitems"); ?></section>
        <?php else: ?>

            <section class="basket_container">

                <!-- BASKET TABLE -->
                <section id="basket-grid">
                    <h1 class="page_title"><?php echo $module->title; ?></h1>

                    <table>
                        <thead>
                            <th><?php echo $resourceService->getLabel("basket.table.item"); ?></th>
                            <th><?php echo $resourceService->getLabel("basket.table.qty"); ?></th>
                            <th><?php echo $resourceService->getLabel("basket.table.price"); ?></th>
                            <th><?php echo $resourceService->getLabel("basket.table.subtotal"); ?></th>
                        </thead>
                        <tbody>
                            <?php $count = 0; foreach($cartService->getCart() as $cartItem): $count++; ?>
                                <?php $collection = $collectionService->getCollection($cartItem["options"]["collection"]); ?>
                                <?php $thumbUrl = $cartItem["options"]["thumb"] ? $cartItem["options"]["thumb"] : NULL; ?>
                                <?php $removeUrl = $requestService->addParameter($navigationService->getStaticUrl($page->url), "action", "remove"); ?>
                                <?php $removeUrl = $requestService->addParameter($removeUrl, "id", $cartItem["rowid"]); ?>
                                <tr>
                                    <!-- ITEM -->
                                    <td class="item" data-label="<?php echo $resourceService->getLabel("basket.table.item"); ?>">

                                        <div class="item_container">
                                            <div class="product_image">
                                                <?php if($thumbUrl) : ?>
                                                    <img src="<?php echo $thumbUrl; ?>" alt="<?php $cartItem["name"]; ?>" width="90" height="50" />
                                                <?php endif; ?>
                                            </div>

                                            <div class="product_details">
                                                <h3><a href="<?php echo $navigationService->getItemUrl($cartItem, "Product"); ?>"><?php echo $cartItem["name"]; ?></a></h3>
                                                <p><?php echo $collection->name; ?></p>

                                                <div class="item_buttons">
                                                    <a href="<?php echo $removeUrl; ?>" title="remove-<?php echo $cartItem["name"]; ?>" class="item_button">
                                                        <i class="icon-cross"></i> <?php echo $resourceService->getLabel("basket.button.remove"); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- QTY -->
                                    <td data-label="<?php echo $resourceService->getLabel("basket.table.qty"); ?>">
                                        <select name="item-qty-<?php echo $count; ?>" data-action="update" data-item="<?php echo $cartItem["rowid"]; ?>" data-uri="<?php echo $navigationService->getStaticUrl($page->url); ?>">
                                            <?php for($i = 1 ; $i <= $maxItemCount; $i++) : ?>
                                                <option <?php echo ($i == $cartItem["qty"]) ? "selected='selected'" : ""; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </td>

                                    <!-- PRICE -->
                                    <td data-label="<?php echo $resourceService->getLabel("basket.table.price"); ?>"><?php echo withCurrency(withFormat($cartItem["price"])); ?></td>

                                    <!-- SUBTOTAL -->
                                    <td data-label="<?php echo $resourceService->getLabel("basket.table.subtotal"); ?>"><strong><?php echo withCurrency(withFormat($cartItem["subtotal"])); ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>

                <!-- BASKET SUMMARY CONTENT -->
                <section id="basket-summary">

                    <!-- ORDER SUMMARY -->
                    <section id="basket-order-summary" class="basket_summary_widget">
                        <h3><?php echo $module->orderSummaryTitle; ?></h3>

                        <table>
                            <tr>
                                <td class="label"><?php echo $resourceService->getLabel("basket.summary.subtotal"); ?></td>
                                <td class="value"><?php echo withCurrency(withFormat($cartService->getTotal())); ?></td>
                            </tr>

                            <tr>
                                <td class="label"><?php echo $resourceService->getLabel("basket.summary.shippinglabel"); ?></td>
                                <td class="value"><?php echo $shippingDetail->label; ?></td>
                            </tr>

                            <tr>
                                <td class="label"><?php echo $resourceService->getLabel("basket.summary.taxlabel"); ?></td>
                                <td class="value"><?php echo withCurrency(withFormat($shippingDetail->shippingTax)); ?></td>
                            </tr>

                        </table>

                        <a href="<?php echo $navigationService->getWebPageUrl($checkoutPage); ?>" class="checkout_btn"><?php echo $resourceService->getLabel("basket.button.checkout"); ?></a>
                    </section>

                    <!-- WISHLIST ITEMS -->
                    <?php if(count($wishlistItems) > 0) : ?>
                        <section id="basket-wishlist" class="basket_summary_widget">
                            <h3><?php echo $module->wishlistTitle; ?></h3>
                            <div id="wishlist-image-container">
                                <?php foreach($wishlistItems as $wlItem) : ?>
                                    <?php $wlItemImage = isset($wishlistItemsImages[$wlItem->id]) ? $wishlistItemsImages[$wlItem->id][0] : NULL; ?>
                                    <?php if($wlItemImage) : ?>
                                        <?php $url = $navigationService->getWebPageUrl($page); ?>
                                        <?php $url .= "?wlaction=movetocart&id={$wlItem->id}"; ?>
                                        <a href="<?php echo $url; ?>"><img src="<?php echo $resourceService->getAssetUrl($wlItemImage, 400, 600); ?>" alt="<?php echo $wlItem->name; ?>" title="<?php echo $wlItem->name; ?>" /></a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif; ?>
                </section>

            </section>

        <?php endif; ?>

    </section>
</section>