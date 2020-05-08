<section id="checkout-error" class="content_container">

    <section class="page_section">

        <h1 class="page_title"><?php echo $module->orderErrorHeading; ?></h1>

        <section class="system_message">
            <?php if($errorContent) : ?>
                <?php echo $errorContent->content; ?>
            <?php endif; ?>
        </section>


    </section>
</section>