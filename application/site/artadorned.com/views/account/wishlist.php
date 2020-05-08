<section class="content_container">
    <section class="page_section">

        <h1 class="page_title"><?php echo $module->wishlistHeading; ?></h1>
            <?php
                $cartService = CartService::instance();
                $collectionService = CollectionService::instance();
                $maxItemCount = $requestService->getAttribute("maxItemCount");
            ?>

        <section class="basket_container account_container clearfix">
            <?php $this->load->view("account/incl/navigation"); ?>
            <?php if(count($wishlistItems) < 1) : ?>
                <div class="account_content">
                    <section class="system_message"><?php echo $resourceService->getLabel("wishlist.noitems"); ?></section>
                </div>
            <?php else: ?>
                    <!-- WISHLIST TABLE -->
                    <section id="basket-grid">
                        <table>
                            <thead>
                                <th><?php echo $resourceService->getLabel("basket.table.item"); ?></th>
                                <th><?php echo $resourceService->getLabel("basket.table.qty"); ?></th>
                                <th><?php echo $resourceService->getLabel("basket.table.price"); ?></th>
                            </thead>
                            <tbody>
                                <?php $count = 0; foreach($wishlistItems as $wlItem): $count++; ?>
                                    <?php   $collection = $collectionService->getCollection($wlItem->collection); ?>
                                    <?php   $thumbImage = isset($wishlistItemsImages[$wlItem->id]) ? $wishlistItemsImages[$wlItem->id][0] : NULL;
                                            $wishlistPageUrl = $navigationService->getStaticUrl($page->url). WebsiteConstants::WISHLIST_URI; ?>
                                    <?php   $removeUrl = $requestService->addParameter($wishlistPageUrl, "wlaction", "remove"); ?>
                                    <?php   $removeUrl = $requestService->addParameter($removeUrl, "id", $wlItem->id);
                                            $moveUrl = $requestService->addParameter($wishlistPageUrl, "wlaction", "movetocart");
                                            $moveUrl = $requestService->addParameter($moveUrl, "id", $wlItem->id); ?>
                                <tr>
                                     <!-- ITEM -->
                                    <td class="item" data-label="<?php echo $resourceService->getLabel("basket.table.item"); ?>">

                                        <div class="item_container">
                                            <div class="product_image">
                                                <?php if($thumbImage) : ?>
                                                    <img src="<?php echo $resourceService->getAssetUrl($thumbImage, 400, 600); ?>" alt="<?php $wlItem->name; ?>" />
                                                <?php endif; ?>
                                            </div>

                                            <div class="product_details">
                                                <h3><?php echo $wlItem->name; ?></h3>
                                                <p><?php echo $collection->name; ?></p>

                                                <div class="item_buttons">
                                                    <a href="<?php echo $removeUrl; ?>" title="remove-<?php echo $wlItem->name; ?>" class="item_button">
                                                        <i class="icon-cross"></i> <?php echo $resourceService->getLabel("basket.button.remove"); ?>
                                                    </a>
                                                    <!--TODO STYLING-->
                                                    <a href="<?php echo $moveUrl; ?>" title="move-<?php echo $wlItem->name; ?>" class="item_button">
                                                        <i class="icon-cart"></i> <?php echo $resourceService->getLabel("button.movetobasket"); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- QTY -->
                                    <td data-label="<?php echo $resourceService->getLabel("basket.table.qty"); ?>">
                                        <select name="item-qty-<?php echo $count; ?>" data-action="update" data-item="<?php echo $wlItem->id; ?>" data-uri="<?php echo $navigationService->getStaticUrl($page->url); ?>">
                                            <?php for($i = 1 ; $i <= $maxItemCount; $i++) : ?>
                                                <option <?php echo ($i == 1) ? "selected='selected'" : ""; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </td>

                                    <!-- PRICE -->
                                    <td data-label="<?php echo $resourceService->getLabel("basket.table.price"); ?>"><?php echo withCurrency(withFormat($wlItem->price)); ?></td>

                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </section>
            <?php endif; ?>
        </section>
    </section>
</section>