<section class="content_container">
    <div class="page_section">
        <!-- SEARCH TITLE -->
        <h1 class="page_title"><?php echo $module->title; ?></h1>

        <!-- SEARCH SUBTITLE -->
        <?php if($module->subtitle != NULL && strlen($module->subtitle)) : ?>
             <?php echo $module->subtitle; ?>
        <?php endif; ?>

        <!-- SEARCH FOR KEYWORD -->
        <section class="search_for"><?php echo $module->searchFor; ?> <strong>"<?php echo $searchQuery; ?>"</strong></section>

        <!-- NO RESULTS OR INVALID SEARCH -->
        <?php if(($pagination && !$pagination->totalCount) || $noresults) : ?>
            <section class="system_message"><?php echo $module->noResults; ?></section>
        <?php else : ?>
        <!-- SHOW THE GRID -->

            <!-- Top view options for the grid -->
            <?php $this->load->view("common/listing/listingtoppanel"); ?>

            <!-- Product Listing -->
            <?php $this->load->view("common/listing/listinggrid"); ?>

            <!-- Pagination  -->
            <?php $this->load->view("common/util/pagination"); ?>

        <?php endif; ?>
    </div>
</section>