<section class="content_container">
    <div class="page_section">
        <div class="listing_container clearfix">

            <!-- LEFT COLUMN SIDE REFINEMENTS -->
            <a href="#" class="btn toggle_btn">Filters</a>
            <section id="filters" class="toggle_section">
                <?php $this->load->view("common/filters/categoryfilter", array("filterHeading" => $topLevelCategory->name)); ?>
                <?php $this->load->view("common/filters/refinementsfilter"); ?>
            </section>

            <!-- Grid  -->
            <section id="listing">
                <!-- Top view options for the grid -->
                <?php $this->load->view("common/listing/listingtoppanel"); ?>

                <!-- Product Listing -->
                <?php $this->load->view("common/listing/listinggrid"); ?>

                <!-- Pagination  -->
                <?php $this->load->view("common/util/pagination"); ?>
            </section>

        </div>
    </div>
</section>