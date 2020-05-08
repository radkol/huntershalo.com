<section id="checkout" class="content_container">
    
    <section class="page_section">
        
        <h1 class="page_title"><?php echo $module->title; ?></h1>
        
        <?php $this->load->view("common/util/address/countryvalues"); ?>
        
        <?php $this->load->view("checkout/incl/billingaddress"); ?>

        <?php $this->load->view("checkout/incl/shippingaddress"); ?>

        <?php $this->load->view("checkout/incl/shippingdetail"); ?>

        <?php $this->load->view("checkout/incl/payment"); ?>

    </section>
    
</section>