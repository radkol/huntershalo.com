<section id="checkout-confirmation" class="content_container">
    <section class="page_section">
        
        <h1 class="page_title"><?php echo $module->orderConfirmationHeading; ?></h1>
        <section class="system_message">
            <?php if($confirmationContent) : ?>
                <?php echo $confirmationContent->content; ?>
            <?php endif; ?>
            <div class="reference_number">
                <?php echo $resourceService->getLabel("orderconfirmation.referencenumber"); ?>: <strong><?php echo $order->uid; ?></strong>
            </div>
        </section>

    </section>
</section>