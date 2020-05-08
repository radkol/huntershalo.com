<section class="content_container">
    <section class="page_section">
        <?php
            $orderStatus = getOrderStatuses();
            $orderStatusLabel = getOrderStatusLabel($order->status);
        ?>
        <h1 class="page_title"><?php echo $module->orderDetailHeading; ?><?php echo $order->uid; ?></h1>
        <section class="basket_container order_details account_container clearfix">

            <?php $this->load->view("account/incl/navigation"); ?>

            <div class="account_content">

                <!-- BACK LINK -->
                <a href="<?php echo $navigationService->getWebPageUrl($page) . $navigationMap["ordersLink"]; ?>" class="back_btn">
                    <i class="icon-chevron-small-left"></i> <?php echo $module->orderHistoryBack; ?>
                </a>

                <!-- ORDER DETAIL BOX -->
                <div class="order-detail-box clearfix">
                    <!-- ORDER DATE -->
                    <div class="order-detail order-date">
                        <strong><?php echo $resourceService->getLabel("orderhistory.table.date"); ?></strong>
                        <?php echo getFormattedDateFieldValue($order->dateCreated); ?>
                     </div>

                    <!-- ORDER STATUS -->
                    <div class="order-detail order-status">
                        <strong><?php echo $resourceService->getLabel("orderhistory.table.status"); ?></strong>
                        <?php echo $orderStatusLabel; ?>
                    </div>

                    <!-- ORDER SHIPPING ADDR -->
                    <div class="order-detail order-addr">
                        <strong><?php echo $resourceService->getLabel("orderhistory.table.shippingaddress"); ?></strong>
                        <?php $this->load->view("common/util/address/displayorderaddress", ["orderAddress" => $order->shippingAddress]); ?>
                    </div>

                    <!-- ORDER BILLING ADDR -->
                    <div class="order-detail order-addr">
                        <strong><?php echo $resourceService->getLabel("orderhistory.table.billingaddress"); ?></strong>
                        <?php $this->load->view("common/util/address/displayorderaddress", ["orderAddress" => $order->billingAddress]); ?>
                    </div>

                    <!-- ORDER BILLING ADDR -->
                    <div class="order-total clearfix">

                        <strong class="order-total-left">
                            <?php echo $resourceService->getLabel("orderhistory.table.amount"); ?>
                        </strong>

                        <strong class="order-total-right">
                            <?php echo withCurrency(withFormat($deliveryMethod->shippingCost + $deliveryMethod->shippingTax + $order->orderTotal)); ?>
                        </strong>

                    </div>
                </div>
                <!-- END ORDER DETAIL BOX -->

                <div style="clear:both;"></div>

                <!-- ENTRIES TABLE -->
                <section id="basket-grid">
                    <table>
                        <thead>
                        <th><?php echo $resourceService->getLabel("basket.table.item"); ?></th>
                        <th><?php echo $resourceService->getLabel("basket.table.qty"); ?></th>
                        <th><?php echo $resourceService->getLabel("basket.table.price"); ?></th>
                        </thead>
                        <tbody>
                            <?php $count = 0; foreach ($orderEntries as $orderEntry): $count++; ?>
                                <?php $thumbImage = isset($productImages[$orderEntry->productId]) ? $productImages[$orderEntry->productId][0] : NULL; ?>
                                <tr>
                                    <!-- ITEM -->
                                    <td class="item" data-label="<?php echo $resourceService->getLabel("basket.table.item"); ?>">
                                        <div class="item_container">
                                            <?php if ($thumbImage) : ?>
                                                <div class="product_image">
                                                    <img src="<?php echo $resourceService->getAssetUrl($thumbImage, 400, 600); ?>" alt="<?php $orderEntry->uid; ?>" />
                                                </div>
                                            <?php endif; ?>
                                            <div class="product_details">
                                                <h3><?php echo $orderEntry->productName; ?></h3>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- QTY -->
                                    <td data-label="<?php echo $resourceService->getLabel("basket.table.qty"); ?>">
                                        <?php echo $orderEntry->quantity; ?>
                                    </td>

                                    <!-- PRICE -->
                                    <td data-label="<?php echo $resourceService->getLabel("basket.table.price"); ?>"><?php echo withCurrency(withFormat($orderEntry->subtotal)); ?></td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>

            </div>

        </section>
    </section>
</section>