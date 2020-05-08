<section class="content_container">
    <section class="page_section">

        <h1 class="page_title"><?php echo $module->orderHistoryHeading; ?></h1>
        <section class="basket_container account_container clearfix">

            <?php $this->load->view("account/incl/navigation"); ?>

            <div class="account_content">
                <?php $orderStatus = getOrderStatuses();
                    $statusToColor = [
                        "returned" => "color-red",
                        "shipped" => "color-green"
                    ];
                ?>
                <!-- BASKET TABLE -->
                <section id="order-history-grid">

                    <table>
                        <thead>
                        <th><?php echo $resourceService->getLabel("orderhistory.table.#"); ?></th>
                        <th><?php echo $resourceService->getLabel("orderhistory.table.date"); ?></th>
                        <th><?php echo $resourceService->getLabel("orderhistory.table.amount"); ?></th>
                        <th><?php echo $resourceService->getLabel("orderhistory.table.status"); ?></th>
                        <th>---</th>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order):; ?>
                                <?php $orderItemUrl = $navigationService->getWebPageUrl($page) . $navigationMap["ordersLink"]; ?>
                                <?php $orderItemUrl = $requestService->addParameter($orderItemUrl, "orderuid", $order->uid); ?>
                                <?php $orderStatusLabel = getOrderStatusLabel($order->status); ?>
                                <?php $statusColor = isset($statusToColor[$order->status]) ? $statusToColor[$order->status] : ''; ?>
                                <tr>

                                    <!-- uid -->
                                    <td data-label="<?php echo $resourceService->getLabel("orderhistory.table.#"); ?>"><?php echo $order->uid; ?></td>

                                    <!-- date -->
                                    <td data-label="<?php echo $resourceService->getLabel("orderhistory.table.date"); ?>"><?php echo getFormattedDateFieldValue($order->dateCreated, "d.m.Y"); ?></td>

                                    <!-- TOTAL -->
                                    <td data-label="<?php echo $resourceService->getLabel("orderhistory.table.amount"); ?>"><strong><?php echo withCurrency(withFormat($order->orderTotal)); ?></strong></td>

                                    <!-- TOTAL -->
                                    <td data-label="<?php echo $resourceService->getLabel("orderhistory.table.status"); ?>"><strong class="<?php echo $statusColor; ?>"><?php echo $orderStatusLabel; ?></strong></td>

                                    <!-- LINK TO DETAIL -->
                                    <td><a href="<?php echo $orderItemUrl; ?>" class="details">
                                            <span><?php echo $resourceService->getLabel("orderhistory.table.details"); ?></span>
                                            <i class=" icon-chevron-with-circle-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>

            </div>

        </section>
    </section>
</section>