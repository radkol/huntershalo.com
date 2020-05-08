<section id="home-collections">
    <section class="content_container">
        <div class="page_section">

            <!-- ALL COLLECTIONS GRID -->
            <div class="collections collections_listing clearfix">
                <?php foreach($pagination->recordSet as $collection) : ?>

                    <?php $url = $navigationService->getItemUrl($collection, "Collection"); ?>
                    <section class="collection_item">
                    <a href="<?php echo $url; ?>" class="collection_item_content" style="background-image:url('<?php echo $resourceService->getAssetUrl($collectionsImages[$collection->id], 1200, 1200); ?>');">
                        <span class="collection_item_details ">
                            <span class="collection_item_title"><?php echo $collection->name; ?></span>
                            <span class="collection_item_description"><?php echo $collection->shortDescription; ?></span>
                        </span>

                    </a>
                    </section>

                <?php endforeach; ?>
            </div>

            <!-- Pagination  -->
            <?php $this->load->view("common/util/pagination"); ?>

        </div>
    </section>
</section>